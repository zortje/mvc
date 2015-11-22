<?php

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
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerNonexistentException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new ControllerNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerNonexistentException
     * @expectedExceptionMessage Controller Foo is nonexistent
     */
    public function testMessageArray()
    {
        throw new ControllerNonexistentException(['Foo']);
    }
}
