<?php

namespace Zortje\MVC\Tests\Routing;

use Zortje\MVC\Routing\Router;

/**
 * Class RouterTest
 *
 * @package Zortje\MVC\Tests\Routing
 */
class RouterTest extends \PHPUnit_Framework_TestCase {

	public function testConnect() {
		$router = new Router();
		$router->connect('/login', 'logins', 'index');

		$expected = ['controller' => 'logins', 'action' => 'index'];

		$this->assertSame($expected, $router->route('/login'));
	}
}
