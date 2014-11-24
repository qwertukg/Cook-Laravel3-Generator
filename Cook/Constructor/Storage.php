<?php namespace Cook\Constructor;

use Laravel\Fluent;
use Cook\Constructor;

class Storage extends Fluent {

	// Constructors result container.
	public $constructors = array();

	// Storage of basic constructos.
	protected $storage = array();

	// Storage of basic constructos.
	protected $columns = array();

	protected $currentBundle;

	public function addBundle($bundle)
	{
		// Set the current bundle.
		$this->currentBundle = $bundle;

		if (!isset($this->storage[$bundle]))
		{
			// Add bundle to storage.
			$this->storage[$bundle] = array();
		}
	}

	public function addConstructor(Constructor $constructor)
	{
		if ($this->currentBundle) 
		{
			// Add constructor to storage with current bundle.
			$this->storage[$this->currentBundle][$constructor->name][] = $constructor;
		}
	}

	public function merge()
	{
		foreach ($this->storage as $bundleName => $tables) 
		{
			foreach ($tables as $constructorName => $constructors) 
			{
				if (!isset($this->columns[$constructorName]))
				{
					// If curent constructor name is not in columns container, add this.
					$this->columns[$constructorName] = array();
				}

				foreach ($constructors as $constructor) 
				{
					foreach ($constructor->commands as $command) 
					{
						// Determine columns, from constructor or command columns.
						$columns = ($command->columns) ? $command->columns : $constructor->columns;

						// Run each command for current constructor and write result to columns container.
						$this->{$command->type}($columns, $constructorName);
					}

					// Unset useless properties.
					unset($constructor->commands, $constructor->connection, $constructor->engine);
					
					if (!isset($this->constructors[$constructorName])) 
					{
						// If this constructor not in constructors container, add result columns to this constructor.
						$constructor->columns = $this->columns[$constructor->name];
						$constructor->bundle = $bundleName;

						// Add this constructor to constructors container.
						$this->constructors[$constructorName] = $constructor;
					}
					else
					{
						// If this constructor in constructors container, change columns on this constructor.
						$this->constructors[$constructorName]->columns = $this->columns[$constructor->name];
						$this->constructors[$constructorName]->bundle = $bundleName;
					}
				}
			}
		}
	}

	protected function create($columns, $constructorName)
	{
		foreach ($columns as $column) 
		{
			// Create new columns in columns container.
			$this->columns[$constructorName][$column->name] = $column;
		}
	}

	protected function add($columns, $constructorName)
	{
		foreach ($columns as $column) 
		{
			// Add new columns to columns container.
			$this->columns[$constructorName][$column->name] = $column;
		}
	}

	protected function drop($columns, $constructorName)
	{
		// Clear current columns container.
		$this->columns[$constructorName] = array();
	}

	protected function drop_column($columns, $constructorName)
	{
		foreach ($columns as $column) 
		{
			// Removes columns from columns container.
			unset($this->columns[$constructorName][$column]);
		}
	}

}