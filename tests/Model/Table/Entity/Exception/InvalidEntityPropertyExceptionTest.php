<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity\Exception;

use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;

/**
 * Class InvalidEntityPropertyExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException
 */
class InvalidEntityPropertyExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('foo');
        
        throw new InvalidEntityPropertyException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(InvalidEntityPropertyException::class);
        $this->expectExceptionMessage('Entity Foo does not have a property named bar');
        
        throw new InvalidEntityPropertyException(['Foo', 'bar']);
    }
}
