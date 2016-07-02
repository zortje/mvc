<?php
declare(strict_types = 1);

require_once '../vendor/autoload.php';

/**
 * Error handling
 */
// @todo Remove usage of ini_set

ini_set('error_reporting', E_ALL); // @todo should be set to maximum error reporting in php.ini
ini_set('display_errors', true); // @todo should be false in php.ini
ini_set('log_errors', true); // @todo should be true in php.ini
ini_set('error_log', sprintf('../../shared/errors-%s.log', date('Y-m-d')));

// @todo Add bugsnag snippet for error logging

/**
 * Configuration and routing
 */
$configuration = new \Zortje\MVC\Configuration\Configuration(include '../config.default.php');

/**
 * Request
 */
$cookie = new \Zortje\MVC\Storage\Cookie\Cookie($configuration, !empty($_COOKIE['token']) ? $_COOKIE['token'] : '');

$request = new Zortje\MVC\Network\Request($cookie, $_SERVER, $_POST);

/**
 * Dispatch
 */
$pdo = new \PDO('mysql:host=127.0.0.1;dbname=my_app', 'root', 'password');

$dispatcher = new Zortje\MVC\Routing\Dispatcher($pdo, $configuration);

// @todo $logger = new \Monolog\Logger('mvc');
// @todo $logger->pushHandler(new \Monolog\Handler\StreamHandler('path/to/log/mvc.log', \Monolog\Logger::DEBUG));

// @todo $dispatcher->setLogger($logger);


/**
 * Response
 */
// @todo add example for calling dispatch with user object
$response = $dispatcher->dispatch($request);

foreach ($response->getHeaders() as $header) {
    header($header);
}

$cookie = $response->getCookie();

if (!is_null($cookie)) {
    setcookie('token', $cookie->getTokenString(), time() + 3600, $path = '/', $domain = '', $secureOnly = true, $httpOnly = true);
}

echo $response->getOutput();
