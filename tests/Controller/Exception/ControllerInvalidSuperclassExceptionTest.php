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
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new ControllerInvalidSuperclassException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException
     * @expectedExceptionMessage Controller Foo is not a subclass of Controller
     */
    public function testMessageArray()
    {
        throw new ControllerInvalidSuperclassException(['Foo']);
    }
}
