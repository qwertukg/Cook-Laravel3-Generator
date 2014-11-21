<?php namespace Cook;

use Laravel\Database\Schema;
use Laravel\IoC;

class Generator extends Schema {
	
	/**
	 * Begin a fluent operation on constructor.
	 *
	 * @param  string   $constructorName
	 * @param  Closure  $callback
	 * @return void
	 */
	public static function table($constructorName, $callback)
	{
		call_user_func($callback, $constructor = new Constructor($constructorName));

		IoC::resolve('ConstructorStorage')->addTable($constructor);

		return static::execute($constructor);
	}

}