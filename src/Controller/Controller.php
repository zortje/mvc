<?php

namespace Zortje\MVC\Controller;

use Zortje\MVC\Model\User;
use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;
use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;
use Zortje\MVC\View\Render\HtmlRender;

/**
 * Class Controller
 *
 * @package Zortje\MVC\Controller
 */
class Controller {

	/**
	 * Controller action is publicly accessible
	 */
	const ACTION_PUBLIC = 0;

	/**
	 * Controller action requires authentication
	 * Will redirect to login page if not authenticated
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
	 * @var string App file path
	 */
	protected $appPath;

	/**
	 * @var null|User User
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
	 * @var array Headers for output
	 *
	 * @todo JSON content type
	 *
	 * Content-Type: application/javascript; charset=utf-8
	 */
	protected $headers = [
		'content-type' => 'Content-Type: text/html; charset=utf-8'
	];

	/**
	 * @param string $action Controller action
	 *
	 * @throws ControllerActionNonexistentException
	 * @throws ControllerActionPrivateInsufficientAuthenticationException
	 * @throws ControllerActionProtectedInsufficientAuthenticationException
	 */
	public function setAction($action) {
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
	 * @return array<string,array|string>|false Headers and output if render is enabled, otherwise FALSE
	 *
	 * @throws \LogicException If controller action is not set
	 */
	public function callAction() {
		if (!isset($this->action)) {
			throw new \LogicException('Controller action must be set before being called');
		}

		/**
		 * Before controller action hook
		 */
		$this->beforeAction();

		/**
		 * Call controller action
		 */
		$action = $this->action;

		$this->$action();

		/**
		 * After controller action hook
		 */
		$this->afterAction();

		/**
		 * Render view
		 */
		if ($this->render) {
			$render = new HtmlRender($this->variables);

			$output = $render->render(['_view' => $this->getViewTemplate(), '_layout' => $this->getLayoutTemplate()]);

			return [
				'headers' => $this->headers,
				'output'  => $output
			];
		}

		return false;
	}

	/**
	 * Before controller action hook
	 *
	 * Called right before controller action is called
	 */
	protected function beforeAction() {
		/**
		 * Set New Relic transaction name
		 */
		if (extension_loaded('newrelic')) {
			newrelic_name_transaction(sprintf('%s/%s', $this->getShortName(), $this->action));
		}
	}

	/**
	 * After controller action hook
	 *
	 * Called right after controller action is called, but before rendering of the view
	 */
	protected function afterAction() {
	}

	/**
	 * Set view variable
	 *
	 * @param string $variable
	 * @param mixed  $value
	 */
	protected function set($variable, $value) {
		$this->variables[$variable] = $value;
	}

	/**
	 * Get layout template
	 *
	 * @return string Layout template file path
	 */
	protected function getLayoutTemplate() {
		$layout = $this->layout;

		if (empty($layout)) {
			$layout = 'View/Layout/default';
		}

		return "{$this->appPath}$layout.layout";
	}

	/**
	 * Get view template
	 *
	 * @return string View template file path
	 */
	protected function getViewTemplate() {
		$view = $this->view;

		if (empty($view)) {
			$view = sprintf('View/%s/%s', $this->getShortName(), $this->action);
		}

		return "{$this->appPath}$view.view";
	}

	/**
	 * @return string Controller name without namespace
	 */
	protected function getShortName() {
		return str_replace('Controller', null, (new \ReflectionClass($this))->getShortName());
	}

	/**
	 * @param \PDO      $pdo
	 * @param string    $appPath
	 * @param null|User $user
	 */
	public function __construct(\PDO $pdo, $appPath, User $user = null) {
		$this->pdo     = $pdo;
		$this->appPath = $appPath;
		$this->user    = $user;
	}

}
