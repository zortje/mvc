<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

use Zortje\MVC\User\User;
use Zortje\MVC\User\UserTable;
use Zortje\MVC\User\UserAuthenticator;

/**
 * Class SignInsController
 *
 * @package Zortje\MVC\Controller
 */
class SignInsController extends Controller
{
    protected $access = [
        'form'   => Controller::ACTION_PUBLIC,
        'signIn' => Controller::ACTION_PUBLIC
    ];

    /**
     * Form for sign in
     */
    protected function form()
    {
        // @todo Implement
    }

    /**
     * Perform sign in
     */
    protected function signIn()
    {
        if (empty($this->post['User.email']) || empty($this->post['User.password'])) {
            // @todo Set flash message: Please fill in both username and password

            $this->redirect('/form');
            return false;
        }

        /**
         * Get user from database
         */
        $userTable = new UserTable($this->pdo);

        $users = $userTable->findBy('email', $this->post['User.email']);

        if (count($users) !== 1) {
            // @todo Set flash message: Incorrect email or password (User dosnt exists or more than one user with that email)

            $this->redirect('/form');
            return false;
        }

        /**
         * @var User $user
         */
        $user = $users[0];

        /**
         * Authenticate sign in
         */
        $userAuthenticator = new UserAuthenticator($this->pdo);

        if ($userAuthenticator->signIn($user, $this->post['User.password']) == false) {
            // @todo Set flash message: Incorrect email or password (Incorrect password)

            $this->redirect('/form');
            return false;
        } else {
            // @todo signed in, redirect to dashboard

            // @todo Set flash message: Logged in successfully

            // @todo redirect after successful sign in
            // @todo if SignIn.onSuccess.controller & SignIn.onSuccess.action is present

            $this->redirect('/dashboard');
            return false;
        }
    }
}
