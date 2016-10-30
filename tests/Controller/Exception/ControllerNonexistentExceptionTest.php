<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerNonexistentException;

/**
 * Class ControllerNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerNonexistentException
 */
class ControllerNonexistentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(ControllerNonexistentException::class);
        $this->expectExceptionMessage('foo');

        throw new ControllerNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(ControllerNonexistentException::class);
        $this->expectExceptionMessage('Controller Foo is nonexistent');

        throw new ControllerNonexistentException(['Foo']);
    }
}
