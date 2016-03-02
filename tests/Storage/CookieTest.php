<?php

namespace Zortje\MVC\Tests\Storage;

use Zortje\MVC\Storage\Cookie\Cookie;

/**
 * Class CookieTest
 *
 * @package            Zortje\MVC\Tests\Storage
 *
 * @coversDefaultClass Zortje\MVC\Storage\Cookie\Cookie
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $cookie = new Cookie('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0amVcL212YyIsImV4cCI6MTQ1Njk1OTI4OSwiZm9vIjoiYmFyIn0.NdrXG2zL3o2BDREHhWy-kdnHrfOHbEvm0iCvfGtUxOw');

        $reflector = new \ReflectionClass($cookie);

        $property = $reflector->getProperty('values');
        $property->setAccessible(true);

        $expected = [
            'foo' => 'bar'
        ];

        $this->assertSame($expected, $property->getValue($cookie));
    }

    /**
     * @covers ::__construct
     */
    public function testConstructInvalidSignature()
    {
        /**
         * Just one letter changed
         */
        $cookie = new Cookie('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0bmVcL212YyIsImV4cCI6MTQ1Njg3MTY3MSwiZm9vIjoiYmFyIn0.JYhjaKBJAZAfdT-6kVypFDSLH9uhqwYRoDDTLOvQSgI');

        $reflector = new \ReflectionClass($cookie);

        $property = $reflector->getProperty('values');
        $property->setAccessible(true);

        $expected = [];

        $this->assertSame($expected, $property->getValue($cookie));
    }

    /**
     * @covers ::set
     */
    public function testSet()
    {
        $cookie = new Cookie();
        $cookie->set('foo', 'bar');

        $reflector = new \ReflectionClass($cookie);

        $property = $reflector->getProperty('values');
        $property->setAccessible(true);

        $expected = [
            'foo' => 'bar'
        ];

        $this->assertSame($expected, $property->getValue($cookie));
    }

    /**
     * @covers ::set
     * @covers ::get
     */
    public function testGet()
    {
        $cookie = new Cookie();
        $cookie->set('foo', 'bar');

        $this->assertSame('bar', $cookie->get('foo'));
    }

    /**
     * @covers ::getTokenString
     */
    public function testGetTokenString()
    {
        $cookie = new Cookie();

        $cookie->set('foo', 'bar');

        // @todo failes due to usage of NOW time in JWT

        $this->markTestIncomplete();

        $this->assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0amVcL212YyIsImV4cCI6MTQ1Njk1OTI4OSwiZm9vIjoiYmFyIn0.NdrXG2zL3o2BDREHhWy-kdnHrfOHbEvm0iCvfGtUxOw', $cookie->getTokenString());
    }
}
