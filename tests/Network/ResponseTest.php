<?php

namespace Zortje\MVC\Tests\Network;

use Zortje\MVC\Network\Response;

/**
 * Class ResponseTest
 *
 * @package            Zortje\MVC\Tests\Network
 *
 * @coversDefaultClass Zortje\MVC\Network\Response
 */
class ResponseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers ::output
	 */
	public function testOutput() {
		$response = new Response([], 'Lorem ipsum');

		$this->assertSame('Lorem ipsum', $response->output());
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$response = new Response(['foo', 'bar'], 'Lorem ipsum');

		$reflector = new \ReflectionClass($response);

		$headers = $reflector->getProperty('headers');
		$headers->setAccessible(true);
		$this->assertSame(['foo', 'bar'], $headers->getValue($response));

		$output = $reflector->getProperty('output');
		$output->setAccessible(true);
		$this->assertSame('Lorem ipsum', $output->getValue($response));
	}

}
