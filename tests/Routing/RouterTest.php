<?php

namespace Zortje\MVC\Tests\Routing;

use Zortje\MVC\Routing\Router;

/**
 * Class RouterTest
 *
 * @package            Zortje\MVC\Tests\Routing
 *
 * @coversDefaultClass Zortje\MVC\Routing\Router
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::connect
     * @covers ::route
     */
    public function testConnect()
    {
        $router = new Router();
        $router->connect('/login', 'logins', 'index');

        $expected = ['controller' => 'logins', 'action' => 'index'];

        $this->assertSame($expected, $router->route('/login'));
    }

    /**
     * @covers ::connect
     *
     * @expectedException Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
     * @expectedExceptionMessage Route /login is already connected
     */
    public function testAlreadyConnectedException()
    {
        $router = new Router();
        $router->connect('/login', 'logins', 'index');
        $router->connect('/login', 'logins', 'index');
    }
}
