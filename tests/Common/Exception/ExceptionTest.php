<?php

namespace Zortje\MVC\Tests\Common\Exception;

use Zortje\MVC\Common\Exception\Exception;

/**
 * Class ExceptionTest
 *
 * @package            Zortje\MVC\Tests\Common\Exception
 *
 * @coversDefaultClass Zortje\MVC\Common\Exception\Exception
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Common\Exception\Exception
     * @expectedExceptionMessage foo
     * @expectedExceptionCode    0
     */
    public function testMessage()
    {
        throw new Exception('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Common\Exception\Exception
     * @expectedExceptionMessage
     * @expectedExceptionCode 0
     */
    public function testMessageArray()
    {
        throw new Exception(['foo']);
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Common\Exception\Exception
     * @expectedExceptionMessage
     * @expectedExceptionCode 42
     */
    public function testCode()
    {
        throw new Exception(null, 42);
    }
}
