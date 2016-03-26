<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity\Exception;

use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNotDefinedException;

/**
 * Class EntityClassNotDefinedExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\Exception\EntityClassNotDefinedException
 */
class EntityClassNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(EntityClassNotDefinedException::class);
        $this->expectExceptionMessage('foo');

        throw new EntityClassNotDefinedException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(EntityClassNotDefinedException::class);
        $this->expectExceptionMessage('Subclass Foo does not have a entity class defined');

        throw new EntityClassNotDefinedException(['Foo']);
    }
}
