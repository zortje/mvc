<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Routing;

use Zortje\MVC\Routing\Router;
use Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException;
use Zortje\MVC\Routing\Exception\RouteNonexistentException;

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
        $router->connect('\/signin', 'signins', 'signin');

        $expected = ['controller' => 'signins', 'action' => 'signin', 'arguments' => []];

        $this->assertSame($expected, $router->route('/signin'));
    }

    /**
     * @covers ::connect
     * @covers ::route
     */
    public function testConnectParameter()
    {
        $router = new Router();
        $router->connect('\/users\/(\d+)', 'users', 'user');

        $expected = ['controller' => 'users', 'action' => 'user', 'arguments' => ['42']];

        $this->assertSame($expected, $router->route('/users/42'));
    }

    /**
     * @covers ::connect
     */
    public function testAlreadyConnectedException()
    {
        $this->expectException(RouteAlreadyConnectedException::class);
        $this->expectExceptionMessage('Route \/signin is already connected');

        $router = new Router();
        $router->connect('\/signin', 'signins', 'signin');
        $router->connect('\/signin', 'signins', 'signin');
    }

    /**
     * @covers ::route
     */
    public function testNotConnected()
    {
        $this->expectException(RouteNonexistentException::class);
        $this->expectExceptionMessage('Route /signin is not connected');

        $router = new Router();

        $router->route('/signin');
    }
}
