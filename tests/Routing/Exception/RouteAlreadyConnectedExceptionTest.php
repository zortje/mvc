<?php
declare(strict_types = 1);

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
     */
    public function testMessage()
    {
        $this->expectException(RouteAlreadyConnectedException::class);
        $this->expectExceptionMessage('foo');
        
        throw new RouteAlreadyConnectedException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(RouteAlreadyConnectedException::class);
        $this->expectExceptionMessage('Route /foo is already connected');
        
        throw new RouteAlreadyConnectedException(['/foo']);
    }
}
