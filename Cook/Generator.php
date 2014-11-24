<?php namespace Cook;

use Laravel\Database\Schema;
use Laravel\IoC;

class Generator extends Schema {

	// binds each executed Constructor with current bundles in storage
	public static function execute($constructor)
	{
		IoC::resolve('ConstructorStorage')->addConstructor($constructor);
		
		return parent::execute($constructor);
	}

	/**
	 * Begin a fluent schema operation on a database table.
	 *
	 * @param  string   $table
	 * @param  Closure  $callback
	 * @return void
	 */
	public static function table($table, $callback)
	{
		call_user_func($callback, $table = new Constructor($table));

		return static::execute($table);
	}

	/**
	 * Create a new database table schema.
	 *
	 * @param  string   $table
	 * @param  Closure  $callback
	 * @return void
	 */
	public static function create($table, $callback)
	{
		$table = new Constructor($table);

		// To indicate that the table is new and needs to be created, we'll run
		// the "create" command on the table instance. This tells schema it is
		// not simply a column modification operation.
		$table->create();

		call_user_func($callback, $table);

		return static::execute($table);
	}

	/**
	 * Drop a database table from the schema.
	 *
	 * @param  string  $table
	 * @param  string  $connection
	 * @return void
	 */
	public static function drop($table, $connection = null)
	{
		$table = new Constructor($table);

		$table->on($connection);

		// To indicate that the table needs to be dropped, we will run the
		// "drop" command on the table instance and pass the instance to
		// the execute method as calling a Closure isn't needed.
		$table->drop();

		return static::execute($table);
	}

}