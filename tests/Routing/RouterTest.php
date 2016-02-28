<?php

namespace Zortje\MVC\Tests\Routing;

use Zortje\MVC\Routing\Router;
use Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException;

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
        $router->connect('/signin', 'signins', 'signin');

        $expected = ['controller' => 'signins', 'action' => 'signin'];

        $this->assertSame($expected, $router->route('/signin'));
    }

    /**
     * @covers ::connect
     */
    public function testAlreadyConnectedException()
    {
        $this->expectException(RouteAlreadyConnectedException::class);
        $this->expectExceptionMessage('Route /signin is already connected');

        $router = new Router();
        $router->connect('/signin', 'signins', 'signin');
        $router->connect('/signin', 'signins', 'signin');
    }
}
