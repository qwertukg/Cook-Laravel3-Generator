<?php namespace Cook;

use Laravel\Database\Schema\Table;
use Laravel\Fluent;

class Constructor extends Table {

	public $bundle;

	public $name;

	public $static = array();

	public $elements = array();

	public function __get($key)
	{
		if (array_key_exists($key, $this->static))
		{
			return $this->static[$key];
		}
	}

	public function __set($key, $value)
	{
		$this->static[$key] = $value;
	}

	public function __isset($key)
	{
		return isset($this->static[$key]);
	}

	public function __unset($key)
	{
		unset($this->static[$key]);
	}

	public function __call($type, $name)
	{
		$name = (count($name) > 0) ? $name[0] : true;

		return $this->elements[$name] = new Fluent(compact('type', 'name'));
	}

}