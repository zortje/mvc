<?php
declare(strict_types = 1);

namespace Zortje\MVC\User;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Storage\Cookie\Cookie;
use Zortje\MVC\Storage\Cookie\Exception\CookieUndefinedIndexException;

/**
 * Class UserAuthenticator
 *
 * @package Zortje\MVC\User
 */
class UserAuthenticator
{

    /**
     * @var \PDO PDO
     */
    protected $pdo;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * UserAuthenticator constructor.
     *
     * @param \PDO          $pdo
     * @param Configuration $configuration
     */
    public function __construct(\PDO $pdo, Configuration $configuration)
    {
        $this->pdo           = $pdo;
        $this->configuration = $configuration;
    }

    /**
     * Authenticate user object from cookie
     *
     * @param Cookie $cookie Cookie object
     *
     * @return User|null User if a user ID is sent in cookie
     */
    public function userFromCookie(Cookie $cookie): User
    {
        try {
            $userId = $cookie->get('User.id');

            $userTable = new UserTable($this->pdo);

            $users = $userTable->findBy('id', $userId);

            if (count($users) === 1) {
                return $users[0];
            } else {
                return null;
            }
        } catch (CookieUndefinedIndexException $e) {
            return null;
        }
    }

    /**
     * Sign in an user
     *
     * @param User   $user     User object
     * @param string $password User password
     *
     * @return bool Returns true if sign in was successful, otherwise false will be returned
     */
    public function signIn(User $user, string $password): bool
    {
        /**
         * Verify password
         */
        if (password_verify($password, $user->get('password_hash')) === false) {
            /**
             * Return false to indicate incorrect credentials
             */
            return false;
        } else {
            /**
             * Check if a new hashing algorithm is available or the cost has changed
             * If so, create a new hash and replace the old one in the user
             */
            $options = ['cost' => $this->configuration->get('User.Password.Cost')];

            if (password_needs_rehash($user->get('password_hash'), PASSWORD_DEFAULT, $options)) {
                /**
                 * Create new password hash
                 */
                $user->set('password_hash', password_hash($password, PASSWORD_DEFAULT, $options));

                /**
                 * Update user in database
                 */
                $userTable = new UserTable($this->pdo);
                $userTable->update($user);
            }

            /**
             * Regenerate session ID to prevent session fixation attacks
             */
            session_regenerate_id();

            /**
             * Set User id in session
             */
            $cookie->set('User.id', $user->get('id'));

            /**
             * Return true to indicate a successful sign in
             */
            return true;
        }
    }
}
