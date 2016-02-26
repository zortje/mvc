<?php

namespace Zortje\MVC\Tests\Controller;

use Zortje\MVC\Controller\ControllerFactory;
use Zortje\MVC\Tests\Controller\Fixture\CarsController;

/**
 * Class ControllerTest
 *
 * @package            Zortje\MVC\Tests\Controller
 *
 * @coversDefaultClass Zortje\MVC\Controller\Controller
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
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
     * @covers ::setAction
     */
    public function testSetAction()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('index');

        $reflector = new \ReflectionClass($carsController);

        $action = $reflector->getProperty('action');
        $action->setAccessible(true);
        $this->assertSame('index', $action->getValue($carsController));
    }

    /**
     * @covers ::setAction
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerActionNonexistentException
     * @expectedExceptionMessage Controller Zortje\MVC\Tests\Controller\Fixture\CarsController action nonexistent is nonexistent
     */
    public function testSetActionNonexistent()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('nonexistent');
    }

    /**
     * @covers ::setAction
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerActionProtectedInsufficientAuthenticationException
     * @expectedExceptionMessage Controller Zortje\MVC\Tests\Controller\Fixture\CarsController protected action hidden requires authentication
     */
    public function testSetActionUnauthenticatedProtected()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('hidden');
    }

    /**
     * @covers ::setAction
     *
     * @expectedException Zortje\MVC\Controller\Exception\ControllerActionPrivateInsufficientAuthenticationException
     * @expectedExceptionMessage Controller Zortje\MVC\Tests\Controller\Fixture\CarsController private action add requires authentication
     */
    public function testSetActionUnauthenticatedPrivate()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('add');
    }

    /**
     * @covers ::getViewTemplate
     */
    public function testGetViewTemplate()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('index');

        $reflector = new \ReflectionClass($carsController);

        $action = $reflector->getProperty('view');
        $action->setAccessible(true);
        $action->setValue($carsController, '../tests/View/Cars/Fixture/index');

        $method = $reflector->getMethod('getViewTemplate');
        $method->setAccessible(true);

        $this->assertSame('../tests/View/Cars/Fixture/index.view', $method->invoke($carsController));
    }

    /**
     * @covers ::getViewTemplate
     */
    public function testGetViewTemplateViewNotSet()
    {
        $controllerFactory = new ControllerFactory($this->pdo, '/src/', null);

        $carsController = $controllerFactory->create(CarsController::class);
        $carsController->setAction('index');

        $reflector = new \ReflectionClass($carsController);

        $method = $reflector->getMethod('getViewTemplate');
        $method->setAccessible(true);

        $this->assertSame('/src/View/Cars/index.view', $method->invoke($carsController));
    }
}
