<?php namespace Cook;

use Laravel\Database\Schema\Table;
use Laravel\Fluent;
use Laravel\IoC;

class Constructor extends Table {

	// Add bundle name to constructor.
	public $bundle;

	// Up name property to top.
	public $name;

	// Add container for new properties.
	public $static = array();

	// Add container for new elements.
	public $elements = array();

	// Get not setting properties getting from static container.
	public function __get($key)
	{
		// If property name is 'root' return all constructors.
		if ($key === 'root') 
		{
			return IoC::resolve('ConstructorStorage')->constructors;
		}

		// If new property is exist, get this from static container.
		if (array_key_exists($key, $this->static))
		{
			return $this->static[$key];
		}
	}

	// Add not setting properties to static container.
	public function __set($key, $value)
	{
		$this->static[$key] = $value;
	}

	// Determine is property static.
	public function __isset($key)
	{
		return isset($this->static[$key]);
	}

	// Remove property from static container.
	public function __unset($key)
	{
		unset($this->static[$key]);
	}

	// Add element to container with type and name;
	public function __call($type, $name)
	{
		$name = (count($name) > 0) ? $name[0] : true;

		return $this->elements[$name] = new Fluent(compact('type', 'name'));
	}

}