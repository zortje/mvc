<?php

namespace Zortje\MVC\Tests\Model;

use Zortje\MVC\Model\EntityProperty;

/**
 * Class EntityPropertyTest
 *
 * @package Zortje\MVC\Tests\Model
 */
class EntityPropertyTest extends \PHPUnit_Framework_TestCase {

	public function testFormatValueStringToString() {
		$property = new EntityProperty('string');

		$this->assertSame('foo', $property->formatValue('foo'));
	}

	public function testFormatValueStringToInteger() {
		$property = new EntityProperty('integer');

		$this->assertSame(42, $property->formatValue('42'));
	}

	public function testFormatValueStringToFloat() {
		$property = new EntityProperty('float');

		$this->assertSame(3.14159265359, $property->formatValue('3.14159265359'));
	}

	public function testFormatValueStringToDateTime() {
		$property = new EntityProperty('DateTime');

		$this->assertEquals(new \DateTime('2015-05-03 01:15:42'), $property->formatValue('2015-05-03 01:15:42'));
	}

}
