<?php
declare(strict_types = 1);

namespace Zortje\MVC\User;

use Zortje\MVC\Model\Table\Table;

/**
 * Class UserTable
 *
 * @package Zortje\MVC
 */
class UserTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $tableName = 'users';

    /**
     * {@inheritdoc}
     */
    protected $entityClass = User::class;
}
