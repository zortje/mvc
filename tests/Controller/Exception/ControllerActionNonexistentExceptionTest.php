<?php
declare(strict_types = 1);

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
     */
    public function testMessage()
    {
        $this->expectException(ControllerActionNonexistentException::class);
        $this->expectExceptionMessage('foo');
        
        throw new ControllerActionNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(ControllerActionNonexistentException::class);
        $this->expectExceptionMessage('Controller Foo action bar is nonexistent');
        
        throw new ControllerActionNonexistentException(['Foo', 'bar']);
    }
}
