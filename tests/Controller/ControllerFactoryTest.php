<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Controller;

use Zortje\MVC\Configuration\Configuration;
use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Network\Request;
use Zortje\MVC\Storage\Cookie\Cookie;
use Zortje\MVC\User\User;
use Zortje\MVC\Tests\Controller\Fixture\CarsController;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;
use Zortje\MVC\Controller\Exception\ControllerNonexistentException;
use Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException;

/**
 * Class ControllerFactoryTest
 *
 * @package            Zortje\MVC\Tests\Controller
 *
 * @coversDefaultClass Zortje\MVC\Controller\ControllerFactory
 */
class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        $this->pdo           = new \PDO("mysql:host=127.0.0.1;dbname=tests", 'root', '');

        $this->configuration = new Configuration([]);
        $this->configuration->set('App.Path', '/var/www/html/');

        $this->request = new Request(new Cookie($this->configuration));
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $user = new User('', '');

        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $this->request, $user);

        $reflector = new \ReflectionClass($controllerFactory);

        $pdoProperty = $reflector->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $this->assertSame($this->pdo, $pdoProperty->getValue($controllerFactory));

        $configProperty = $reflector->getProperty('configuration');
        $configProperty->setAccessible(true);
        $this->assertSame($this->configuration, $configProperty->getValue($controllerFactory));

        $requestProperty = $reflector->getProperty('request');
        $requestProperty->setAccessible(true);
        $this->assertSame($this->request, $requestProperty->getValue($controllerFactory));

        $userProperty = $reflector->getProperty('user');
        $userProperty->setAccessible(true);
        $this->assertSame($user, $userProperty->getValue($controllerFactory));
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $this->request);

        $controller = $controllerFactory->create(CarsController::class);

        $this->assertSame(get_class($controller), CarsController::class);
    }

    /**
     * @covers ::create
     */
    public function testCreateNonexistent()
    {
        $this->expectException(ControllerNonexistentException::class);
        $this->expectExceptionMessage('Controller NonexistentController is nonexistent');

        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $this->request);

        $controllerFactory->create('NonexistentController');
    }

    /**
     * @covers ::create
     */
    public function testCreateInvalidSuperclass()
    {
        $message = 'Controller Zortje\MVC\Tests\Model\Fixture\CarEntity is not a subclass of Controller';

        $this->expectException(ControllerInvalidSuperclassException::class);
        $this->expectExceptionMessage($message);

        $controllerFactory = new ControllerFactory($this->pdo, $this->configuration, $this->request);

        $controllerFactory->create(CarEntity::class);
    }
}
