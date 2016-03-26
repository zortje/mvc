<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;

/**
 * Class ControllerInvalidSuperclassExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException
 */
class ControllerInvalidSuperclassExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(ControllerInvalidSuperclassException::class);
        $this->expectExceptionMessage('foo');

        throw new ControllerInvalidSuperclassException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(ControllerInvalidSuperclassException::class);
        $this->expectExceptionMessage('Controller Foo is not a subclass of Controller');

        throw new ControllerInvalidSuperclassException(['Foo']);
    }
}
