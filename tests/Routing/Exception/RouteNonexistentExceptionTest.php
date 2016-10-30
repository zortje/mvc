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
     */
    public function testMessage()
    {
        $this->expectException(RouteNonexistentException::class);
        $this->expectExceptionMessage('foo');

        throw new RouteNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(RouteNonexistentException::class);
        $this->expectExceptionMessage('Route /foo is not connected');

        throw new RouteNonexistentException(['/foo']);
    }
}
