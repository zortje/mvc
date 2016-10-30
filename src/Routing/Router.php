<?php
declare(strict_types = 1);

namespace Zortje\MVC\Routing;

use Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException;
use Zortje\MVC\Routing\Exception\RouteNonexistentException;

/**
 * Class Router
 *
 * @package Zortje\MVC\Routing
 */
class Router
{

    /**
     * @var array Routes
     */
    protected $routes = [];

    /**
     * Connects a new route in the router
     *
     * @param string $route      Route
     * @param string $controller Controller
     * @param string $action     Action
     *
     * @throws RouteAlreadyConnectedException When route is already connected
     */
    public function connect(string $route, string $controller, string $action)
    {
        if (isset($this->routes[$route]) === true) {
            throw new RouteAlreadyConnectedException([$route]);
        }

        $this->routes[$route] = [
            'controller' => $controller,
            'action'     => $action
        ];
    }

    /**
     * Route to get controller and action
     *
     * @param string $route Route
     *
     * @return array Controller and action
     *
     * @throws RouteNonexistentException When route is not connected
     */
    public function route(string $route): array
    {
        foreach ($this->routes as $pattern => $result) {
            if (preg_match("/$pattern/", $route, $matches)) {
                array_shift($matches);

                $result['arguments'] = $matches;

                return $result;
            }
        }

        /**
         * Throw exception if no match for route is found
         */
        throw new RouteNonexistentException([$route]);
    }
}
