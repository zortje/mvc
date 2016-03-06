<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Routing\Exception;

use Zortje\MVC\Routing\Exception\RouteNonexistentException;

/**
 * Class RouteNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Routing\Exception
 *
 * @coversDefaultClass Zortje\MVC\Routing\Exception\RouteNonexistentException
 */
class RouteNonexistentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Routing\Exception\RouteNonexistentException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new RouteNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Routing\Exception\RouteNonexistentException
     * @expectedExceptionMessage Route /foo is not connected
     */
    public function testMessageArray()
    {
        throw new RouteNonexistentException(['/foo']);
    }
}
