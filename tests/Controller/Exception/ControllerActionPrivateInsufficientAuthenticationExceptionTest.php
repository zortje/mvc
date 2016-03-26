<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Controller\Exception;

use Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException;

/**
 * Class ControllerActionPrivateInsufficientAuthenticationExceptionTest
 *
 * @package            Zortje\MVC\Tests\Controller\Exception
 *
 * @coversDefaultClass Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException
 */
class ControllerActionPrivateInsufficientAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(ControllerActionPrivateInsufficientAuthenticationException::class);
        $this->expectExceptionMessage('foo');

        throw new ControllerActionPrivateInsufficientAuthenticationException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(ControllerActionPrivateInsufficientAuthenticationException::class);
        $this->expectExceptionMessage('Controller Foo private action bar requires authentication');

        throw new ControllerActionPrivateInsufficientAuthenticationException(['Foo', 'bar']);
    }
}
