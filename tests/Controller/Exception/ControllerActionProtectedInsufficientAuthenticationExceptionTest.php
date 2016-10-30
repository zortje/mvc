<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException;

/**
 * Class ControllerActionProtectedInsufficientAuthenticationExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException
 */
class ControllerActionProtectedInsufficientAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(ControllerActionProtectedInsufficientAuthenticationException::class);
        $this->expectExceptionMessage('foo');

        throw new ControllerActionProtectedInsufficientAuthenticationException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(ControllerActionProtectedInsufficientAuthenticationException::class);
        $this->expectExceptionMessage('Controller Foo protected action bar requires authentication');

        throw new ControllerActionProtectedInsufficientAuthenticationException(['Foo', 'bar']);
    }
}
