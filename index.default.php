<?php
declare(strict_types = 1);

require_once 'vendor/autoload.php';

/**
 * Error handling
 */
ini_set('error_reporting', E_ALL); // @todo should be set to maximum error reporting in php.ini
ini_set('display_errors', true); // @todo should be false in php.ini
ini_set('log_errors', true); // @todo should be true in php.ini
ini_set('error_log', sprintf('../../shared/errors-%s.log', date('Y-m-d')));

// @todo Add bugsnag snippet for error logging
// @todo Remove usage of ini_set

/**
 * Configuration and routing
 */
$configuration = new \Zortje\MVC\Configuration\Configuration(include 'config.default.php');

/**
 * Request
 */
$cookie = null;

if (!empty($_COOKIE['token'])) {
    $cookie = new \Zortje\MVC\Storage\Cookie\Cookie($configuration, $_COOKIE['token']);
}

$request = new Zortje\MVC\Network\Request($cookie, $_SERVER, $_POST);

/**
 * Dispatch
 */
$pdo = new \PDO('mysql:host=127.0.0.1;dbname=my_app', 'root', 'password');

$dispatcher = new Zortje\MVC\Routing\Dispatcher($pdo, $configuration);

$response = $dispatcher->dispatch($request);

/**
 * Output
 *
 * @var \Zortje\MVC\Storage\Cookie\Cookie $cookie
 */
list($headers, $cookie, $output) = array_values($response->output());

foreach ($headers as $header) {
    header($header);
}

setcookie('token', $cookie->getTokenString(), time() + 3600, '/', '', true, true);

echo $output;
