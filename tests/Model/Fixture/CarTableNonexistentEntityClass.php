<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Table;

/**
 * Class CarTableNonexistentEntityClass
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarTableNonexistentEntityClass extends Table
{

    protected $tableName = 'cars';

    protected $entityClass = 'NonexistentClass';
}
