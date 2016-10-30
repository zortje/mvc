<?php
declare(strict_types = 1);

/**
 * Configuration
 */
return [

    /**
     * App
     */
    'App.Path'          => realpath(dirname(__FILE__)) . '/src/',

    /**
     * Router
     */
    'Router'            => include 'routes.default.php',

    /**
     * User
     */
    //'User.SignIn.Controller.Class' => 'SignInsController',
    //'User.SignIn.Controller.Action' => 'form',

    /**
     * Cookie
     */
    'Cookie.TTL'        => '+1 hour',
    'Cookie.Signer.Key' => 'super-secret-key'
];
