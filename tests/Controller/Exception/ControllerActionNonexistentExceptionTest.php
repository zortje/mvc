<?php

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerActionNonexistentException;

/**
 * Class ControllerActionNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerActionNonexistentException
 */
class ControllerActionNonexistentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerActionNonexistentException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new ControllerActionNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerActionNonexistentException
     * @expectedExceptionMessage Controller Foo action bar is nonexistent
     */
    public function testMessageArray()
    {
        throw new ControllerActionNonexistentException(['Foo', 'bar']);
    }
}
