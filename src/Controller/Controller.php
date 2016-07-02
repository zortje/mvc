<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Network\Response;
use Zortje\MVC\View\Render\HtmlRender;

/**
 * Class Controller
 *
 * @package Zortje\MVC\Controller
 */
class Controller
{

    /**
     * Controller action is publicly accessible
     */
    const ACTION_PUBLIC = 0;

    /**
     * Controller action requires authentication
     * Will redirect to sign in page if not authenticated
     */
    const ACTION_PROTECTED = 1;

    /**
     * Controller action requires authentication
     * Will result in an 404 if not authenticated
     */
    const ACTION_PRIVATE = 2;

    /**
     * @var array Controller action access rules
     */
    protected $access = [];

    /**
     * @var \PDO PDO
     */
    protected $pdo;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Entity|null User
     */
    protected $user;

    /**
     * @var string Controller action
     */
    protected $action;

    /**
     * @var array View variables
     */
    protected $variables = [];

    /**
     * @var bool Should render view for controller action
     */
    protected $render = true;

    /**
     * @var string File path for layout template file
     */
    protected $layout;

    /**
     * @var string File path for view template file
     */
    protected $view;

    /**
     * @var string Content type
     */
    protected $contentType = 'html';

    /**
     * @var array Headers for output
     */
    protected $headers = [];

    /**
     * Controller constructor.
     *
     * @param \PDO          $pdo
     * @param Configuration $configuration
     * @param Request       $request
     * @param Entity|null   $user
     */
    public function __construct(\PDO $pdo, Configuration $configuration, Request $request, Entity $user = null)
    {
        $this->pdo           = $pdo;
        $this->configuration = $configuration;
        $this->request       = $request;
        $this->user          = $user;
    }

    /**
     * @return string Controller name without namespace
     */
    public function getShortName(): string
    {
        return str_replace('Controller', null, (new \ReflectionClass($this))->getShortName());
    }

    /**
     * @param string $action Controller action
     *
     * @throws ControllerActionNonexistentException
     * @throws ControllerActionPrivateInsufficientAuthenticationException
     * @throws ControllerActionProtectedInsufficientAuthenticationException
     */
    public function setAction(string $action)
    {
        /**
         * Check if method exists and that access has been defined
         */
        if (!method_exists($this, $action) || !isset($this->access[$action])) {
            throw new ControllerActionNonexistentException([get_class($this), $action]);
        }

        /**
         * Check controller action access level if user is not authenticated
         */
        if (!$this->user) {
            if ($this->access[$action] === self::ACTION_PRIVATE) {
                throw new ControllerActionPrivateInsufficientAuthenticationException([get_class($this), $action]);
            } elseif ($this->access[$action] === self::ACTION_PROTECTED) {
                throw new ControllerActionProtectedInsufficientAuthenticationException([get_class($this), $action]);
            }
        }

        /**
         * Set controller action
         */
        $this->action = $action;
    }

    /**
     * Call action
     *
     * @return Response
     *
     * @throws \LogicException If controller action is not set
     */
    public function callAction(): Response
    {
        if (!isset($this->action)) {
            throw new \LogicException('Controller action must be set before being called');
        }

        /**
         * Before controller action hook
         */
        if ($this->beforeAction()) {
            /**
             * Call controller action
             */
            $action = $this->action;

            $this->$action();

            /**
             * After controller action hook
             */
            $this->afterAction();
        }

        /**
         * Render view
         */
        if ($this->render && $this->contentType === 'html') {
            if ($this->request->getCookie()->exists('Flash.Message') && $this->request->getCookie()->exists('Flash.Type')) {
                $this->set('_flash', [
                    'message' => $this->request->getCookie()->get('Flash.Message'),
                    'type'    => $this->request->getCookie()->get('Flash.Type')
                ]);

                $this->request->getCookie()->remove('Flash.Message');
                $this->request->getCookie()->remove('Flash.Type');
            }

            /**
             * Set content type header
             */
            $this->headers['content-type'] = 'Content-Type: text/html; charset=utf-8';

            /**
             * Render output
             */
            $render = new HtmlRender($this->variables);

            $output = $render->render(['_view' => $this->getViewTemplate(), '_layout' => $this->getLayoutTemplate()]);
        } elseif ($this->render && $this->contentType === 'json') {
            /**
             * Set content type header
             */
            $this->headers['content-type'] = 'Content-Type: application/javascript;';

            /**
             * Render output
             */
            $output = json_encode([$this->variables]);

        } else {
            $output = '';
        }

        return new Response($this->headers, $this->request->getCookie(), $output);
    }

    /**
     * Before controller action hook
     *
     * Called right before controller action is called
     *
     * If FALSE is returned, the action will not be called
     *
     * @return bool
     */
    protected function beforeAction(): bool
    {
        return true;
    }

    /**
     * After controller action hook
     *
     * Called right after controller action is called, before rendering of the view
     *
     * Only called if action is called
     */
    protected function afterAction()
    {
    }

    /**
     * Set view variable
     *
     * @param string $variable
     * @param mixed  $value
     */
    protected function set(string $variable, $value)
    {
        $this->variables[$variable] = $value;
    }

    /**
     * Set flash message
     *
     * Recommended types: error, warning, success & info
     *
     * @param string $message Flash message
     * @param string $type    Flash type
     */
    protected function setFlash(string $message, string $type)
    {
        $cookie = $this->request->getCookie();

        $cookie->set('Flash.Message', $message);
        $cookie->set('Flash.Type', $type);
    }

    /**
     * Set a redirect header in the response
     *
     * @param string $url URL for redirect
     */
    protected function redirect(string $url)
    {
        $this->headers['locaction'] = "Location: $url";

        /**
         * Disable rendering if redirecting
         */
        $this->render = false;
    }

    /**
     * Get layout template
     *
     * @return string Layout template file path
     */
    protected function getLayoutTemplate(): string
    {
        $layout = $this->layout;

        if (empty($layout)) {
            $layout = 'View/Layout/default';
        }

        return "{$this->configuration->get('App.Path')}$layout.layout";
    }

    /**
     * Get view template
     *
     * @return string View template file path
     */
    protected function getViewTemplate(): string
    {
        $view = $this->view;

        if (empty($view)) {
            $view = sprintf('View/%s/%s', $this->getShortName(), $this->action);
        }

        return "{$this->configuration->get('App.Path')}$view.view";
    }

    /**
     * Set response code
     *
     * Supports 200 OK, 403 Forbidden, 404 Not Found & 500 Internal Server Error
     *
     * @param int $code HTTP response code
     *
     * @throws \InvalidArgumentException If unsupported code is provided
     */
    protected function setResponseCode(int $code)
    {
        switch ($code) {
            case 200:
                $text = 'OK';
                break;

            case 403:
                $text = 'Forbidden';
                break;

            case 404:
                $text = 'Not Found';
                break;

            case 500:
                $text = 'Internal Server Error';
                break;

            default:
                throw new \InvalidArgumentException("HTTP status '$code' is not implemented");
        }

        /**
         * Set header
         */
        // @todo test that running response code multiple times only results in one response code header
        $this->headers['response_code'] = "HTTP/1.1 $code $text";
    }
}
