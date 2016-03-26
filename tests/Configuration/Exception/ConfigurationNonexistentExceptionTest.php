<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Configuration\Exception;

use Zortje\MVC\Configuration\Exception\ConfigurationNonexistentException;

/**
 * Class ConfigurationNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Configuration\Exception
 *
 * @coversDefaultClass Zortje\MVC\Configuration\Exception\ConfigurationNonexistentException
 */
class ConfigurationNonexistentExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testMessage()
    {
        $this->expectException(ConfigurationNonexistentException::class);
        $this->expectExceptionMessage('foo');

        throw new ConfigurationNonexistentException('foo');
    }

    public function testMessageArray()
    {
        $this->expectException(ConfigurationNonexistentException::class);
        $this->expectExceptionMessage('Configuration Foo is nonexistent');

        throw new ConfigurationNonexistentException(['Foo']);
    }
}
