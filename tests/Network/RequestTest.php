<?php

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Network\Request;

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
     * @covers ::getPath
     */
    public function testGetPath()
    {
        $request = new Request('https://www.example.com/cars', []);
        $this->assertEquals('/cars', $request->getPath(), 'Single component path without slash');

        $request = new Request('https://www.example.com/cars/', []);
        $this->assertEquals('/cars', $request->getPath(), 'Single component path with slash');

        $request = new Request('https://www.example.com/cars/ford', []);
        $this->assertEquals('/cars/ford', $request->getPath(), 'Two component path without slash');

        $request = new Request('https://www.example.com/cars/ford/', []);
        $this->assertEquals('/cars/ford', $request->getPath(), 'Two component path with slash');
    }

    /**
     * @covers ::getPath
     */
    public function testGetPathEmptyPath()
    {
        $request = new Request('https://www.example.com', []);
        $this->assertEquals('', $request->getPath(), 'Empty path without slash');

        $request = new Request('https://www.example.com/', []);
        $this->assertEquals('', $request->getPath(), 'Empty path with slash');
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $request = new Request('https://www.example.com/', []);

        $reflector = new \ReflectionClass($request);

        $url = $reflector->getProperty('url');
        $url->setAccessible(true);
        $this->assertSame('https://www.example.com', $url->getValue($request));

        $post = $reflector->getProperty('post');
        $post->setAccessible(true);
        $this->assertSame([], $post->getValue($request));
    }
}
