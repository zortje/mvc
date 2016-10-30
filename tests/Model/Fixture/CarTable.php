<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Table;

/**
 * Class CarTable
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarTable extends Table
{

    protected $tableName = 'cars';

    protected $entityClass = CarEntity::class;
}
