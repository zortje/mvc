<?php
declare(strict_types = 1);

namespace Zortje\MVC\Routing;

use Monolog\Logger;
use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\SignInsController;
use Zortje\MVC\Controller\NotFoundController;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;
use Zortje\MVC\Routing\Exception\RouteNonexistentException;
use Zortje\MVC\User\UserAuthenticator;

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
     * @param Request $request Request object
     *
     * @return Response Reponse object
     *
     * @throws \Exception If unexpected exception is thrown
     */
    public function dispatch(Request $request): Response
    {
        /**
         * Figure out what controller to use and what action to call
         */
        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $request, $this->getUserFromRequest($request));

        try {
            /**
             * @var Router $router
             */
            $router = $this->configuration->get('Router');

            list($controllerName, $action) = array_values($router->route($request->getPath()));

            /**
             * Validate and initialize controller
             */
            try {
                $controller = $controllerFactory->create($controllerName);
            } catch (\Exception $e) {
                if ($e instanceof ControllerNonexistentException || $e instanceof ControllerInvalidSuperclassException) {
                    /**
                     * Log invalid superclass
                     */
                    if ($this->logger && $e instanceof ControllerInvalidSuperclassException) {
                        $this->logger->addCritical('Controller must be an subclass of Zortje\MVC\Controller', [
                            'path'       => $request->getPath(),
                            'controller' => $controllerName
                        ]);
                    }

                    /**
                     * Log nonexistent
                     */
                    if ($this->logger && $e instanceof ControllerNonexistentException) {
                        $this->logger->addCritical('Controller is nonexistent', [
                            'path'       => $request->getPath(),
                            'controller' => $controllerName
                        ]);
                    }

                    $controller = $controllerFactory->create(NotFoundController::class);
                    $action     = 'index';
                } else {
                    throw $e;
                }
            }
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

            /**
             * Save what controller and action was requested and then redirect to sign in form
             */
            $cookie = $request->getCookie();

            $cookie->set('SignIn.onSuccess.controller', $controller->getShortName());
            $cookie->set('SignIn.onSuccess.action', $action);

            $controller = $controllerFactory->create(SignInsController::class);
            $controller->setAction('form');
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

            $controller = $controllerFactory->create(NotFoundController::class);
            $controller->setAction('index');
        } catch (ControllerActionNonexistentException $e) {
            /**
             * Log nonexistent controller action
             */
            if ($this->logger) {
                $this->logger->addCritical('Controller action is nonexistent', [
                    'path'       => $request->getPath(),
                    'controller' => $controller->getShortName(),
                    'action'     => $action
                ]);
            }

            $controller = $controllerFactory->create(NotFoundController::class);
            $controller->setAction('index');
        }

        /**
         * Create response from controller action headers and output
         */
        list($headers, $output) = array_values($controller->callAction());

        /**
         * Performance logging
         */
        if ($this->logger) {
            $time = number_format((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2);

            $this->logger->addDebug("Dispatched request in $time ms", ['path' => $request->getPath()]);
        }

        return new Response($headers, $request->getCookie(), $output);
    }

    protected function getUserFromRequest(Request $request)
    {
        /**
         * Authenticate user from cookie
         */
        $cookie = $request->getCookie();

        $userAuthenticator = new UserAuthenticator($this->pdo, $this->configuration);

        $user = $userAuthenticator->userFromCookie($cookie);

        return $user;
    }
}
