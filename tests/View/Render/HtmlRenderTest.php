<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\View\Render;

use Zortje\MVC\View\Render\HtmlRender;

/**
 * Class HtmlRenderTest
 *
 * @package            Zortje\MVC\Tests\View\Render
 *
 * @coversDefaultClass Zortje\MVC\View\Render\HtmlRender
 */
class HtmlRenderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $variables = [
            'foo' => 'bar'
        ];

        $htmlRender = new HtmlRender($variables);

        $reflector = new \ReflectionClass($htmlRender);

        $methodProperty = $reflector->getProperty('variables');
        $methodProperty->setAccessible(true);
        $this->assertSame($variables, $methodProperty->getValue($htmlRender));
    }

    /**
     * @covers ::render
     */
    public function testRender()
    {
        $htmlRender = new HtmlRender(['foo' => 'bar']);

        $output = $htmlRender->render([
            '_view'   => 'tests/View/Test/Fixture/test.view',
            '_layout' => 'tests/View/Layout/Fixture/test.layout'
        ]);

        $this->assertSame('<layout><view>bar</view></layout>', $output);
    }

    /**
     * @covers ::renderFile
     */
    public function testRenderFile()
    {
        $htmlRender = new HtmlRender(['foo' => 'bar']);

        $reflector = new \ReflectionClass($htmlRender);

        $method = $reflector->getMethod('renderFile');
        $method->setAccessible(true);

        $output = $method->invoke($htmlRender, 'tests/View/Test/Fixture/test.view');

        $this->assertSame('<view>bar</view>', $output);
    }

    /**
     * @covers ::renderFile
     */
    public function testRenderFileInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File "foo.bar" is nonexistent (Working directory: ' . getcwd() . ')');

        $htmlRender = new HtmlRender([]);

        $reflector = new \ReflectionClass($htmlRender);

        $method = $reflector->getMethod('renderFile');
        $method->setAccessible(true);

        $method->invoke($htmlRender, 'foo.bar');
    }
}
