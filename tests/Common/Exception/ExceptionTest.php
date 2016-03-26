<?php
declare(strict_types = 1);

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
     */
    public function testMessage()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('foo');
        $this->expectExceptionCode(0);

        throw new Exception('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        throw new Exception(['foo']);
    }

    /**
     * @covers ::__construct
     */
    public function testCode()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(42);

        throw new Exception('', 42);
    }
}
