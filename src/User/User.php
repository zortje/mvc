<?php
declare(strict_types = 1);

namespace Zortje\MVC\User;

use Zortje\MVC\Model\Table\Entity\Entity;

/**
 * Class User
 *
 * @package Zortje\MVC\Model
 */
class User extends Entity
{

    protected static $columns = [
        'email'         => 'string',
        'password_hash' => 'string',
    ];

    /**
     * User constructor.
     *
     * @param string $email
     * @param string $passwordHash
     */
    public function __construct(string $email, string $passwordHash)
    {
        parent::__construct(null, new \DateTime(), new \DateTime());

        $this->set('email', $email);
        $this->set('password_hash', $passwordHash);
    }
}
