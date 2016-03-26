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
     * @covers ::getPath
     * @covers ::getPost
     * @covers ::getCookie
     */
    public function testConstruct()
    {
        $cookie  = new Cookie($this->configuration);
        $server  = ['HTTPS' => 'https', 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/'];
        $post    = ['User.Email' => 'user@example.com'];
        $request = new Request($cookie, $server, $post);

        $reflector = new \ReflectionClass($request);

        $urlProperty = $reflector->getProperty('url');
        $urlProperty->setAccessible(true);
        $this->assertSame('https://www.example.com', $urlProperty->getValue($request));
        $this->assertSame('', $request->getPath());

        $postProperty = $reflector->getProperty('post');
        $postProperty->setAccessible(true);
        $this->assertSame($post, $postProperty->getValue($request));
        $this->assertSame($post, $request->getPost());

        $cookieProperty = $reflector->getProperty('cookie');
        $cookieProperty->setAccessible(true);
        $this->assertSame($cookie, $cookieProperty->getValue($request));
        $this->assertSame($cookie, $request->getCookie());
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
     * @covers ::getPost
     */
    public function testGetPost()
    {
        $post    = ['User.Email' => 'user@example.com'];
        $request = new Request(new Cookie($this->configuration), [], $post);

        $this->assertSame($post, $request->getPost());
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
}
