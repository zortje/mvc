<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity\Exception;

use Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException;

/**
 * Class InvalidValueTypeForEntityPropertyExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\Exception\InvalidValueTypeForEntityPropertyException
 */
class InvalidValueTypeForEntityPropertyExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('foo');
        
        throw new InvalidValueTypeForEntityPropertyException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(InvalidValueTypeForEntityPropertyException::class);
        $this->expectExceptionMessage('Entity property expected value type to be "string", got "integer" instead');
        
        throw new InvalidValueTypeForEntityPropertyException(['string', 'integer']);
    }
}
