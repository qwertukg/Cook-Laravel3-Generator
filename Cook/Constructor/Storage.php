<?php namespace Cook\Constructor;

use Laravel\Fluent;
use Cook\Constructor;

class Storage extends Fluent {

	protected $storage = array();

	protected $columns = array();

	protected $constructors = array();

	protected $currentBundle;

	public function addBundle($bundle)
	{
		$this->currentBundle = $bundle;

		if (!isset($this->storage[$bundle]))
		{
			$this->storage[$bundle] = array();
		}
	}

	public function addConstructor(Constructor $constructor)
	{
		if ($this->currentBundle) 
		{
			$this->storage[$this->currentBundle][$constructor->name][] = $constructor;
		}
	}

	public function show()
	{
		$this->merge();

		print_r( $this->constructors );
	}

	protected function merge()
	{
		foreach ($this->storage as $bundleName => $tables) 
		{
			$this->columns = array();

			foreach ($tables as $tableName => $constructors) 
			{
				foreach ($constructors as $constructor) 
				{
					foreach ($constructor->commands as $command) 
					{
						$columns = ($command->columns) ? $command->columns : $constructor->columns;

						$this->{$command->type}($columns);
					}

					unset($constructor->commands, $constructor->connection, $constructor->engine);

					$constructor->columns = $this->columns;
					$constructor->bundle = $bundleName;
				}

				$this->constructors[$tableName] = $constructor;

				unset($this->storage[$bundleName][$tableName]);
			}
		}
	}

	protected function create($columns)
	{
		foreach ($columns as $column) 
		{
			$this->columns[$column->name] = $column;
		}
	}

	protected function add($columns)
	{
		foreach ($columns as $column) 
		{
			$this->columns[$column->name] = $column;
		}
	}

	protected function drop($columns)
	{
		$this->columns = array();
	}

	protected function drop_column($columns)
	{
		foreach ($columns as $column) 
		{
			unset($this->columns[$column]);
		}
	}

}