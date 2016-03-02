<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Storage\Cookie\Cookie;
use Zortje\MVC\User\User;

/**
 * Class ControllerFactory
 *
 * @package Zortje\MVC\Controller
 */
class ControllerFactory
{

    /**
     * @var \PDO PDO
     */
    protected $pdo;

    /**
     * @var array Post
     */
    protected $post;

    /**
     * @var Cookie Cookie
     */
    protected $cookie;

    /**
     * @var User|null User
     */
    protected $user;

    /**
     * @var string App file path
     */
    protected $appPath;

    /**
     * ControllerFactory constructor.
     *
     * @param \PDO      $pdo
     * @param array     $post
     * @param Cookie    $cookie
     * @param string    $appPath
     * @param User|null $user
     */
    public function __construct(\PDO $pdo, array $post, Cookie $cookie, string $appPath, User $user = null)
    {
        $this->pdo     = $pdo;
        $this->post    = $post;
        $this->cookie  = $cookie;
        $this->user    = $user;
        $this->appPath = $appPath;
    }

    /**
     * Initialize controller
     *
     * @param string $controller Controller class name
     *
     * @return Controller Controller object
     *
     * @throws ControllerInvalidSuperclassException
     * @throws ControllerNonexistentException
     */
    public function create(string $controller): Controller
    {
        if (!class_exists($controller)) {
            throw new ControllerNonexistentException([$controller]);
        } elseif (!is_subclass_of($controller, Controller::class)) {
            throw new ControllerInvalidSuperclassException([$controller]);
        }

        /**
         * @var Controller $controller
         */
        $controller = new $controller($this->pdo, $this->post, $this->cookie, $this->appPath, $this->user);

        return $controller;
    }
}
