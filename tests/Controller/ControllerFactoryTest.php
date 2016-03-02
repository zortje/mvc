<?php

namespace Zortje\MVC\Tests\Controller;

use Zortje\MVC\Controller\ControllerFactory;
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

    public function setUp()
    {
        $this->pdo = new \PDO("mysql:host=127.0.0.1;dbname=tests", 'root', '');
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        $controllerFactory = new ControllerFactory($this->pdo, [], new Cookie(), '');

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

        $controllerFactory = new ControllerFactory($this->pdo, [], new Cookie(), '');

        $controllerFactory->create('NonexistentController');
    }

    /**
     * @covers ::create
     */
    public function testCreateInvalidSuperclass()
    {
        $this->expectException(ControllerInvalidSuperclassException::class);
        $this->expectExceptionMessage('Controller Zortje\MVC\Tests\Model\Fixture\CarEntity is not a subclass of Controller');

        $controllerFactory = new ControllerFactory($this->pdo, [], new Cookie(), '');

        $controllerFactory->create(CarEntity::class);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $user = new User('', '');

        $controllerFactory = new ControllerFactory($this->pdo, [], new Cookie(), '/var/www/html/', $user);

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
