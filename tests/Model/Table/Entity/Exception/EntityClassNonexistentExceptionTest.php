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
     *
     * @expectedException Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException
     * @expectedExceptionMessage foo
     */
    public function testMessage()
    {
        throw new EntityClassNonexistentException('foo');
    }

    /**
     * @covers ::__construct
     *
     * @expectedException Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException
     * @expectedExceptionMessage Subclass Foo defined entity class Bar is nonexistent
     */
    public function testMessageArray()
    {
        throw new EntityClassNonexistentException(['Foo', 'Bar']);
    }
}
