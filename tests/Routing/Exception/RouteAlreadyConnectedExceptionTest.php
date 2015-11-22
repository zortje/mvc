<?php

namespace Zortje\MVC\Tests\Routing\Exception;

use Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException;

/**
 * Class RouteAlreadyConnectedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Routing\Exception
 *
 * @coversDefaultClass Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
 */
class RouteAlreadyConnectedExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new RouteAlreadyConnectedException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Routing\Exception\RouteAlreadyConnectedException
     * @expectedExceptionMessage Route /foo is already connected
     */
    public function testMessageArray()
    {
        throw new RouteAlreadyConnectedException(['/foo']);
    }
}
