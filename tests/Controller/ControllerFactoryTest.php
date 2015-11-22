<?php

namespace Zortje\MVC\Tests\Controller;

use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Model\User;
use Zortje\MVC\Tests\Controller\Fixture\CarsController;
use Zortje\MVC\Tests\Model\Fixture\CarEntity;

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

    public function setUp()
    {
        $this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=myapp_test", 'root', '');
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        $controllerFactory = new ControllerFactory($this->pdo, null, null);

        $controller = $controllerFactory->create(CarsController::class);

        $this->assertSame(get_class($controller), CarsController::class);
    }

    /**
     * @covers ::create
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerNonexistentException
     * @expectedExceptionMessage Controller NonexistentController is nonexistent
     */
    public function testCreateNonexistent()
    {
        $controllerFactory = new ControllerFactory($this->pdo, null, null);

        $controllerFactory->create('NonexistentController');
    }

    /**
     * @covers ::create
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerInvalidSuperclassException
     * @expectedExceptionMessage Controller Zortje\MVC\Tests\Model\Fixture\CarEntity is not a subclass of Controller
     */
    public function testCreateInvalidSuperclass()
    {
        $controllerFactory = new ControllerFactory($this->pdo, null, null);

        $controllerFactory->create(CarEntity::class);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $user = new User(null, new \DateTime(), new \DateTime());

        $controllerFactory = new ControllerFactory($this->pdo, '/var/www/html/', $user);

        $reflector = new \ReflectionClass($controllerFactory);

        $pdoProperty = $reflector->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $this->assertSame($this->pdo, $pdoProperty->getValue($controllerFactory));

        $appPathProperty = $reflector->getProperty('appPath');
        $appPathProperty->setAccessible(true);
        $this->assertSame('/var/www/html/', $appPathProperty->getValue($controllerFactory));

        $userProperty = $reflector->getProperty('user');
        $userProperty->setAccessible(true);
        $this->assertSame($user, $userProperty->getValue($controllerFactory));
    }
}
