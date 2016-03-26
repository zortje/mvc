<?php
declare(strict_types = 1);

namespace Zortje\MVC\Routing;

use Monolog\Logger;
use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\Controller\NotFoundController; // @todo this is a user implemented controller and should be removed after user stuff is cleaned up
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;
use Zortje\MVC\Routing\Exception\RouteNonexistentException;

/**
 * Class Dispatcher
 *
 * @package Zortje\MVC\Routing
 */
class Dispatcher
{

    /**
     * @var \PDO PDO
     */
    protected $pdo;

    /**
     * @var Configuration Configuration
     */
    protected $configuration;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Dispatcher constructor.
     *
     * @param \PDO          $pdo
     * @param Configuration $configuration
     */
    public function __construct(\PDO $pdo, Configuration $configuration)
    {
        $this->pdo           = $pdo;
        $this->configuration = $configuration;
    }

    /**
     * Set logger to be used for any logging that could occure in the dispatching process
     *
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request     $request Request object
     * @param Entity|null $user
     *
     * @return Response Reponse object
     *
     * @throws \Exception If unexpected exception is thrown
     */
    public function dispatch(Request $request, Entity $user = null): Response
    {
        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $request, $user);

        /**
         * Figure out what controller to use and what action to call
         */
        try {
            /**
             * @var Router $router
             */
            $router = $this->configuration->get('Router');

            list($controllerName, $action) = array_values($router->route($request->getPath()));

            /**
             * Validate and initialize controller
             */
            $controller = $controllerFactory->create($controllerName);
        } catch (RouteNonexistentException $e) {
            /**
             * Log nonexistent route (404)
             */
            if ($this->logger) {
                $this->logger->addWarning('Route not connected', [
                    'path' => $request->getPath()
                ]);
            }

            $controller = $controllerFactory->create(NotFoundController::class);
            $action     = 'index';
        }

        /**
         * Validate and set controller action
         */
        try {
            $controller->setAction($action);
        } catch (ControllerActionProtectedInsufficientAuthenticationException $e) {
            /**
             * Log unauthed protected controller action (403)
             */
            if ($this->logger) {
                $this->logger->addWarning('Unauthenticated attempt to access protected action', [
                    'path'       => $request->getPath(),
                    'controller' => $controller->getShortName(),
                    'action'     => $action
                ]);
            }

            if ($this->configuration->exists('User.SignIn.Controller.Class') && $this->configuration->exists('User.SignIn.Controller.Action')) {
                /**
                 * Save what controller and action was requested and then redirect to sign in form
                 */
                // @todo test that this works
                $request->getCookie()->set('SignIn.onSuccess.path', $request->getPath());

                $controller = $controllerFactory->create($this->configuration->get('User.SignIn.Controller.Class'));
                $controller->setAction($this->configuration->get('User.SignIn.Controller.Action'));
            } else {
                $controller = $controllerFactory->create(NotFoundController::class);
                $controller->setAction('index');
            }
        } catch (ControllerActionPrivateInsufficientAuthenticationException $e) {
            /**
             * Log unauthed private controller action (403)
             */
            if ($this->logger) {
                $this->logger->addWarning('Unauthenticated attempt to access private action', [
                    'path'       => $request->getPath(),
                    'controller' => $controller->getShortName(),
                    'action'     => $action
                ]);
            }
        }

        /**
         * Create response from controller action headers and output
         */
        $response = $controller->callAction();

        /**
         * Performance logging
         */
        if ($this->logger) {
            $time = number_format((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2);

            $this->logger->addDebug("Dispatched request in $time ms", ['path' => $request->getPath()]);
        }

        return $response;
    }
}
