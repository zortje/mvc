<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Table;

/**
 * Class CarTableNoTableName
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarTableNoTableName extends Table
{

    protected $entityClass = CarEntity::class;
}
