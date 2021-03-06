<?php


use SilverStripe\ORM\FieldType\DBField;


/**
 * @package framework
 * @subpackage tests
 */
class TimeTest extends SapphireTest {

	public function testNice() {
		$time = DBField::create_field('Time', '17:15:55');
		$this->assertEquals('5:15pm', $time->Nice());

		Config::inst()->update('SilverStripe\\ORM\\FieldType\\DBTime', 'nice_format', 'H:i:s');
		$this->assertEquals('17:15:55', $time->Nice());
	}

}
