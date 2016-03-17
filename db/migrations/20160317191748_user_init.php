<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class UserInit
 */
class UserInit extends AbstractMigration
{
    /**
     * Change
     */
    public function change()
    {
        /**
         * users
         */
        $users = $this->table('users', ['collation' => 'utf8mb4_unicode_ci']);

        $users->addColumn('email', 'string', ['length' => 128]);
        $users->addColumn('password_hash', 'string', ['length' => 255]);
        $users->addColumn('modified', 'datetime');
        $users->addColumn('created', 'datetime');

        $users->create();

        $users->changeColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $users->update();

        /**
         * user_password_resets
         */
        $users = $this->table('user_password_resets', ['collation' => 'utf8mb4_unicode_ci']);

        $users->addColumn('user_id', 'integer', ['signed' => false]);
        $users->addColumn('token_hash', 'string', ['length' => 255]);
        $users->addColumn('created', 'datetime');

        $users->create();

        $users->changeColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $users->update();
    }
}
