<?php namespace Cook\Constructor;

use Laravel\Fluent;
use Cook\Constructor;

class Storage extends Fluent {

	public $constructors = array();

	protected $storage = array();

	protected $columns = array();

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

	public function merge()
	{
		foreach ($this->storage as $bundleName => $tables) 
		{
			foreach ($tables as $tableName => $constructors) 
			{
				if (!isset($this->columns[$tableName]))
				{
					$this->columns[$tableName] = array();
				}

				foreach ($constructors as $constructor) 
				{
					foreach ($constructor->commands as $command) 
					{
						$columns = ($command->columns) ? $command->columns : $constructor->columns;

						$this->{$command->type}($columns, $tableName);
					}

					$constructor->bundle = $bundleName;
					$constructor->columns = $this->columns[$constructor->name];
					$this->constructors[$tableName] = $constructor;
					
					unset($constructor->commands, $constructor->connection, $constructor->engine);
				}
			}
		}
	}

	protected function create($columns, $tableName)
	{
		foreach ($columns as $column) 
		{
			$this->columns[$tableName][$column->name] = $column;
		}
	}

	protected function add($columns, $tableName)
	{
		foreach ($columns as $column) 
		{
			$this->columns[$tableName][$column->name] = $column;
		}
	}

	protected function drop($columns, $tableName)
	{
		$this->columns[$tableName] = array();
	}

	protected function drop_column($columns, $tableName)
	{
		foreach ($columns as $column) 
		{
			unset($this->columns[$tableName][$column]);
		}
	}

}