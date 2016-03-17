<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Network\Response;
use Zortje\MVC\Storage\Cookie\Cookie;

/**
 * Class ResponseTest
 *
 * @package            Zortje\MVC\Tests\Network
 *
 * @coversDefaultClass Zortje\MVC\Network\Response
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @covers ::__construct
     * @covers ::getHeaders
     * @covers ::getCookie
     * @covers ::getOutput
     */
    public function testConstruct()
    {
        $cookie = new Cookie(new Configuration([]));

        $response = new Response(['foo', 'bar'], $cookie, 'Lorem ipsum');

        $reflector = new \ReflectionClass($response);

        $headersProperty = $reflector->getProperty('headers');
        $headersProperty->setAccessible(true);
        $this->assertSame(['foo', 'bar'], $headersProperty->getValue($response));
        $this->assertSame(['foo', 'bar'], $response->getHeaders());

        $cookieProperty = $reflector->getProperty('cookie');
        $cookieProperty->setAccessible(true);
        $this->assertSame($cookie, $cookieProperty->getValue($response));
        $this->assertSame($cookie, $response->getCookie());

        $outputProperty = $reflector->getProperty('output');
        $outputProperty->setAccessible(true);
        $this->assertSame('Lorem ipsum', $outputProperty->getValue($response));
        $this->assertSame('Lorem ipsum', $response->getOutput());
    }

    /**
     * @covers ::getHeaders
     */
    public function testGetHeaders()
    {
        $response = new Response(['foo', 'bar'], new Cookie(new Configuration([])), '');

        $this->assertSame(['foo', 'bar'], $response->getHeaders());
    }

    /**
     * @covers ::getCookie
     */
    public function testGetCookie()
    {
        $cookie = new Cookie(new Configuration([]));

        $response = new Response([], $cookie, '');

        $this->assertSame($cookie, $response->getCookie());
    }

    /**
     * @covers ::getOutput
     */
    public function testGetOutput()
    {
        $response = new Response([], new Cookie(new Configuration([])), 'Lorem ipsum');

        $this->assertSame('Lorem ipsum', $response->getOutput());
    }
}
