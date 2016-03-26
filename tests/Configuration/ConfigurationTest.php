<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Configuration;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Configuration\Exception\ConfigurationNonexistentException;

/**
 * Class ConfigurationTest
 *
 * @package            Zortje\MVC\Tests\Configuration
 *
 * @coversDefaultClass Zortje\MVC\Configuration\Configuration
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $config = [
            'foo' => 'bar'
        ];

        $configuration = new Configuration($config);

        $reflector = new \ReflectionClass($configuration);

        $configurationsProperty = $reflector->getProperty('configurations');
        $configurationsProperty->setAccessible(true);
        $this->assertSame($config, $configurationsProperty->getValue($configuration));
    }

    /**
     * @covers ::__construct
     */
    public function testConstructEmpty()
    {
        $configuration = new Configuration();

        $reflector = new \ReflectionClass($configuration);

        $configurationsProperty = $reflector->getProperty('configurations');
        $configurationsProperty->setAccessible(true);
        $this->assertEmpty($configurationsProperty->getValue($configuration));
    }

    /**
     * @covers ::set
     */
    public function testSet()
    {
        $configuration = new Configuration();

        $reflector = new \ReflectionClass($configuration);

        $configurationsProperty = $reflector->getProperty('configurations');
        $configurationsProperty->setAccessible(true);
        $this->assertEmpty($configurationsProperty->getValue($configuration));

        $configuration->set('foo', 'bar');

        $this->assertSame(['foo' => 'bar'], $configurationsProperty->getValue($configuration));
    }

    /**
     * @covers ::exists
     */
    public function testExists()
    {
        $configuration = new Configuration();

        $this->assertFalse($configuration->exists('foo'));

        $configuration->set('foo', 'bar');

        $this->assertTrue($configuration->exists('foo'));
    }

    /**
     * @covers ::get
     */
    public function testGet()
    {
        $configuration = new Configuration();
        $configuration->set('foo', 'bar');

        $this->assertSame('bar', $configuration->get('foo'));
    }

    /**
     * @covers ::get
     */
    public function testGetNonexistent()
    {
        $this->expectException(ConfigurationNonexistentException::class);
        $this->expectExceptionMessage('Configuration foo is nonexistent');

        $configuration = new Configuration();
        $configuration->get('foo');
    }
}
