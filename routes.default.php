<?php
declare(strict_types = 1);

/**
 * Routes
 */
$router = new Zortje\MVC\Routing\Router();
$router->connect('\/cars', \Zortje\MVC\Tests\Controller\Fixture\CarsController::class, 'index');

return $router;
