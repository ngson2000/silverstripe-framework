<?php

namespace SilverStripe\ORM\FieldType;

use CheckboxSetField;
use Config;
use SilverStripe\ORM\DB;

/**
 * Represents an multi-select enumeration field.
 * @package framework
 * @subpackage orm
 */
class DBMultiEnum extends DBEnum {
	public function __construct($name, $enum = NULL, $default = NULL) {
		// MultiEnum needs to take care of its own defaults
		parent::__construct($name, $enum, null);

		// Validate and assign the default
		$this->default = null;
		if($default) {
			$defaults = preg_split('/ *, */',trim($default));
			foreach($defaults as $thisDefault) {
				if(!in_array($thisDefault, $this->enum)) {
					user_error("Enum::__construct() The default value '$thisDefault' does not match "
						. "any item in the enumeration", E_USER_ERROR);
					return;
				}
			}
			$this->default = implode(',',$defaults);
		}
	}

	public function requireField(){
		// @todo: Remove mysql-centric logic from this
		$charset = Config::inst()->get('SilverStripe\ORM\Connect\MySQLDatabase', 'charset');
		$collation = Config::inst()->get('SilverStripe\ORM\Connect\MySQLDatabase', 'collation');
		$values=array(
			'type'=>'set',
			'parts'=>array(
				'enums'=>$this->enum,
				'character set'=> $charset,
				'collate'=> $collation,
				'default'=> $this->default,
				'table'=>$this->tableName,
				'arrayValue'=>$this->arrayValue
			)
		);

		DB::require_field($this->tableName, $this->name, $values);

	}


	/**
	 * Return a {@link CheckboxSetField} suitable for editing this field
	 */
	public function formField($title = null, $name = null, $hasEmpty = false, $value = "", $form = null,
			$emptyString = null) {

		if(!$title) $title = $this->name;
		if(!$name) $name = $this->name;

		$field = new CheckboxSetField($name, $title, $this->enumValues($hasEmpty), $value, $form);

		return $field;
	}
}
