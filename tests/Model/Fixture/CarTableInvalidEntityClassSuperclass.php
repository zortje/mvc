<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Table;

/**
 * Class CarTableInvalidEntityClassSuperclass
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarTableInvalidEntityClassSuperclass extends Table
{

    protected $tableName = 'cars';

    protected $entityClass = EntityInvalidSuperclass::class;
}
