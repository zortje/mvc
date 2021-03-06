<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Routing;

use Monolog\Logger;
use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Routing\Dispatcher;
use Zortje\MVC\Routing\Router;
use Zortje\MVC\Storage\Cookie\Cookie;
use Zortje\MVC\Tests\Controller\Fixture\CarsController;

/**
 * Class DispatcherTest
 *
 * @package            Zortje\MVC\Tests\Routing
 *
 * @coversDefaultClass Zortje\MVC\Routing\Dispatcher
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PDO
     */
    private $pdo;

    public function setUp()
    {
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=tests', 'root', '');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('SET NAMES utf8');
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $configuration = new Configuration();

        $dispatcher = new Dispatcher($this->pdo, $configuration);

        $reflector = new \ReflectionClass($dispatcher);

        $pdoProperty = $reflector->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $this->assertSame($this->pdo, $pdoProperty->getValue($dispatcher));

        $configurationProperty = $reflector->getProperty('configuration');
        $configurationProperty->setAccessible(true);
        $this->assertSame($configuration, $configurationProperty->getValue($dispatcher));
    }

    /**
     * @covers ::setLogger
     */
    public function testSetLogger()
    {
        $configuration = new Configuration();

        $logger = new Logger('log');

        $dispatcher = new Dispatcher($this->pdo, $configuration);
        $dispatcher->setLogger($logger);

        $reflector = new \ReflectionClass($dispatcher);

        $loggerProperty = $reflector->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertSame($logger, $loggerProperty->getValue($dispatcher));
    }

    /**
     * @covers ::dispatch
     */
    public function testDispatch()
    {
        $router = new Router();
        $router->connect('\/cars', CarsController::class, 'index');

        $configuration = new Configuration();
        $configuration->set('Router', $router);
        $configuration->set('App.Path', realpath(dirname(__FILE__)) . '/../../src/');

        $dispatcher = new Dispatcher($this->pdo, $configuration);

        $cookie = new Cookie($configuration);

        $request = new Request($cookie, ['REQUEST_URI' => '/cars'], []);

        $response = $dispatcher->dispatch($request);

        $this->assertSame(['content-type' => 'Content-Type: text/html; charset=utf-8'], $response->getHeaders());
        $this->assertSame($cookie, $response->getCookie());
        $this->assertSame('<h1>Auto</h1><p>Ford Model T</p><p>Ford Model A</p>', $response->getOutput());
    }
}
