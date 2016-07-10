<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Storage;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Storage\Cookie\Cookie;
use Zortje\MVC\Storage\Cookie\Exception\CookieUndefinedIndexException;

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
     * @var Configuration
     */
    private $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration();
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $cookie = new Cookie($this->configuration,
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0amVcL212YyIsImV4cCI6MTQ1Njk1OTI4OSwiZm9vIjoiYmFyIn0.NdrXG2zL3o2BDREHhWy-kdnHrfOHbEvm0iCvfGtUxOw');

        $reflector = new \ReflectionClass($cookie);

        $property = $reflector->getProperty('values');
        $property->setAccessible(true);

        $expected = [
            'foo' => 'bar'
        ];

        // @todo generate new tokens to test
        $this->markTestIncomplete();

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
        $cookie = new Cookie($this->configuration,
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0bmVcL212YyIsImV4cCI6MTQ1Njg3MTY3MSwiZm9vIjoiYmFyIn0.JYhjaKBJAZAfdT-6kVypFDSLH9uhqwYRoDDTLOvQSgI');

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
        $cookie = new Cookie($this->configuration);
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
     * @covers ::exists
     */
    public function testExists()
    {
        $cookie = new Cookie($this->configuration);

        $this->assertFalse($cookie->exists('foo'));

        $cookie->set('foo', 'bar');

        $this->assertTrue($cookie->exists('foo'));
    }

    /**
     * @covers ::remove
     */
    public function testRemove()
    {
        $cookie = new Cookie($this->configuration);
        $cookie->set('foo', 'bar');

        $cookie->remove('foo');

        $this->assertFalse($cookie->exists('foo'));
    }

    /**
     * @covers ::set
     * @covers ::get
     */
    public function testGet()
    {
        $cookie = new Cookie($this->configuration);
        $cookie->set('foo', 'bar');

        $this->assertSame('bar', $cookie->get('foo'));
    }

    /**
     * @covers ::get
     */
    public function testGetUndefined()
    {
        $this->expectException(CookieUndefinedIndexException::class);
        $this->expectExceptionMessage('Cookie key foo is undefined');

        $cookie = new Cookie($this->configuration);

        $cookie->get('foo');
    }

    /**
     * @covers ::getTokenString
     */
    public function testGetTokenString()
    {
        $cookie = new Cookie($this->configuration);

        $cookie->set('foo', 'bar');

        // @todo failes due to usage of NOW time in JWT

        $this->markTestIncomplete();

        $expected = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ6b3J0amVcL212YyIsImV4cCI6MTQ1Njk1OTI4OSwiZm9vIjoiYmFyIn0.NdrXG2zL3o2BDREHhWy-kdnHrfOHbEvm0iCvfGtUxOw';

        $this->assertSame($expected, $cookie->getTokenString());
    }

    /**
     * @covers ::parseAndValidateToken
     */
    public function testParseAndValidateToken()
    {
        $this->markTestIncomplete();
    }
}
