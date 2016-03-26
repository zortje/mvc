<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Table\Entity\Exception;

use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException;

/**
 * Class EntityClassNonexistentExceptionTest
 *
 * @package            Zortje\MVC\Tests\Model\Table\Entity\Exception
 *
 * @coversDefaultClass Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException
 */
class EntityClassNonexistentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testMessage()
    {
        $this->expectException(EntityClassNonexistentException::class);
        $this->expectExceptionMessage('foo');

        throw new EntityClassNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     */
    public function testMessageArray()
    {
        $this->expectException(EntityClassNonexistentException::class);
        $this->expectExceptionMessage('Subclass Foo defined entity class Bar is nonexistent');

        throw new EntityClassNonexistentException(['Foo', 'Bar']);
    }
}
