<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Storage\Cookie\Cookie;

/**
 * Class RequestTest
 *
 * @package            Zortje\MVC\Tests\Network
 *
 * @coversDefaultClass Zortje\MVC\Network\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Configuration
     */
    private $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration();
    }

    /**
     * @covers ::__construct
     * @covers ::getMethod
     * @covers ::getPath
     * @covers ::getAcceptHeader
     * @covers ::getAuthorizationHeader
     * @covers ::getCookie
     * @covers ::getPost
     */
    public function testConstruct()
    {
        $cookie = new Cookie($this->configuration);

        $server = [
            'HTTPS'              => 'https',
            'HTTP_HOST'          => 'www.example.com',
            'HTTP_ACCEPT'        => 'application/json',
            'HTTP_AUTHORIZATION' => 'foo',
            'REQUEST_URI'        => '/'
        ];

        $post = [
            'User.Email' => 'user@example.com'
        ];

        $request = new Request($cookie, $server, $post);

        $reflector = new \ReflectionClass($request);

        $methodProperty = $reflector->getProperty('method');
        $methodProperty->setAccessible(true);
        $this->assertSame('GET', $methodProperty->getValue($request));
        $this->assertSame('GET', $request->getMethod());

        $urlProperty = $reflector->getProperty('url');
        $urlProperty->setAccessible(true);
        $this->assertSame('https://www.example.com', $urlProperty->getValue($request));
        $this->assertSame('', $request->getPath());

        $expectedHeaders = [
            'Accept'        => 'application/json',
            'Authorization' => 'foo'
        ];

        $headersProperty = $reflector->getProperty('headers');
        $headersProperty->setAccessible(true);
        $this->assertSame($expectedHeaders, $headersProperty->getValue($request));
        $this->assertSame('application/json', $request->getAcceptHeader());
        $this->assertSame('foo', $request->getAuthorizationHeader());

        $cookieProperty = $reflector->getProperty('cookie');
        $cookieProperty->setAccessible(true);
        $this->assertSame($cookie, $cookieProperty->getValue($request));
        $this->assertSame($cookie, $request->getCookie());

        $postProperty = $reflector->getProperty('post');
        $postProperty->setAccessible(true);
        $this->assertSame($post, $postProperty->getValue($request));
        $this->assertSame($post, $request->getPost());
    }

    /**
     * @covers ::getMethod
     */
    public function testGetMethod()
    {
        $request = new Request(new Cookie($this->configuration));
        $this->assertSame('GET', $request->getMethod());

        $request = new Request(new Cookie($this->configuration), ['REQUEST_METHOD' => 'HEAD']);
        $this->assertSame('HEAD', $request->getMethod());

        $request = new Request(new Cookie($this->configuration), ['REQUEST_METHOD' => 'POST']);
        $this->assertSame('POST', $request->getMethod());

        $request = new Request(new Cookie($this->configuration), ['REQUEST_METHOD' => 'PUT']);
        $this->assertSame('PUT', $request->getMethod());
    }

    /**
     * @covers ::getPath
     */
    public function testGetPath()
    {
        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/cars'];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('/cars', $request->getPath(), 'Single component path without slash');

        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/cars/'];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('/cars', $request->getPath(), 'Single component path with slash');

        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/cars/ford'];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('/cars/ford', $request->getPath(), 'Two component path without slash');

        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/cars/ford/'];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('/cars/ford', $request->getPath(), 'Two component path with slash');
    }

    /**
     * @covers ::getPath
     */
    public function testGetPathEmptyPath()
    {
        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => ''];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('', $request->getPath(), 'Empty path without slash');

        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/'];
        $request = new Request(new Cookie($this->configuration), $server);
        $this->assertEquals('', $request->getPath(), 'Empty path with slash');
    }

    /**
     * @covers ::getAcceptHeader
     */
    public function testGetAcceptHeader()
    {
        $request = new Request(new Cookie($this->configuration), ['HTTP_ACCEPT' => 'application/json']);
        $this->assertSame('application/json', $request->getAcceptHeader());
    }

    /**
     * @covers ::getAuthorizationHeader
     */
    public function testGetAuthorizationHeader()
    {
        $request = new Request(new Cookie($this->configuration), ['HTTP_AUTHORIZATION' => 'foo']);
        $this->assertSame('foo', $request->getAuthorizationHeader());
    }

    /**
     * @covers ::getCookie
     */
    public function testGetCookie()
    {
        $cookie  = new Cookie($this->configuration);
        $request = new Request($cookie);

        $this->assertSame($cookie, $request->getCookie());
    }

    /**
     * @covers ::getPost
     */
    public function testGetPost()
    {
        $post    = ['User.Email' => 'user@example.com'];
        $request = new Request(new Cookie($this->configuration), [], $post);

        $this->assertSame($post, $request->getPost());
    }

    /**
     * @covers ::createUrlFromServerArray
     */
    public function testCreateUrlFromServerArray()
    {
        $request = new Request(new Cookie($this->configuration));

        $reflector = new \ReflectionClass($request);

        $method = $reflector->getMethod('createUrlFromServerArray');
        $method->setAccessible(true);

        /**
         * HTTPS
         */
        $this->assertSame('http://www.example.com', $method->invoke($request, [], false));
        $this->assertSame('http://www.example.com', $method->invoke($request, [
            'HTTP_HOST'   => 'www.example.com',
            'REQUEST_URI' => ''
        ], false));
        $this->assertSame('http://www.example.com/foo/bar', $method->invoke($request, [
            'HTTP_HOST'   => 'www.example.com',
            'REQUEST_URI' => '/foo/bar'
        ], false));

        /**
         * HTTP
         */
        $this->assertSame('https://www.example.com', $method->invoke($request, [], true));
        $this->assertSame('https://www.example.com', $method->invoke($request, [
            'HTTP_HOST'   => 'www.example.com',
            'REQUEST_URI' => ''
        ], true));
        $this->assertSame('https://www.example.com/foo/bar', $method->invoke($request, [
            'HTTP_HOST'   => 'www.example.com',
            'REQUEST_URI' => '/foo/bar'
        ], true));
    }

    /**
     * @covers ::parseHeaders
     */
    public function testParseHeaders()
    {
        $request = new Request(new Cookie($this->configuration));

        $reflector = new \ReflectionClass($request);

        $method = $reflector->getMethod('parseHeaders');
        $method->setAccessible(true);

        $server = [
            'HTTP_ACCEPT'        => 'application/json',
            'HTTP_AUTHORIZATION' => 'foo'
        ];

        $expected = [
            'Accept'        => 'application/json',
            'Authorization' => 'foo'
        ];

        $this->assertSame($expected, $method->invoke($request, $server));
    }
}
