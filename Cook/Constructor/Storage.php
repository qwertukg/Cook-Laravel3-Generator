<?php namespace Cook\Constructor;

use Laravel\Fluent;
use Cook\Constructor;

class Storage extends Fluent {

	protected $__storage = array();
	protected $__result = array();

	protected $currentBundle;

	public function addBundle($bundle)
	{
		$this->currentBundle = $bundle;

		if (!isset($this->__storage[$bundle]))
		{
			$this->__storage[$bundle] = array();
		}
	}

	public function addTable(Constructor $constructor)
	{
		if (! $this->currentBundle) 
		{
			throw new \Exception('Undefinded current bundle. Invoke Cook\Constructor\Storage::addBundle() befor Cook\Constructor\Storage::addTable().', 1);
		}

		$this->__storage[$this->currentBundle][] = $constructor;
	}

	public function show()
	{
		$this->merge();

		print_r($this);
	}

	protected function merge()
	{
		foreach ($this->__storage as $bundleName => $constructors) 
		{
			$this->$bundleName = new static;

			unset($this->$bundleName->__storage, $this->$bundleName->__result, $this->$bundleName->currentBundle);

			foreach ($constructors as $constructor) 
			{
				$constructorName = $constructor->name;

				foreach ($constructor->commands as $command) 
				{
					$columns = ($command->columns) ? $command->columns : $constructor->columns;

					$this->{$command->type}($columns);

					$constructor->columns = $this->__result;

					$this->$bundleName->$constructorName = $constructor;
				}
			}
		}

		unset($this->__storage, $this->__result, $this->currentBundle);
	}

	protected function create($columns)
	{
		foreach ($columns as $column) 
		{
			$this->__result[$column->name] = $column;
		}
	}

	protected function add($columns)
	{
		foreach ($columns as $column) 
		{
			$this->__result[$column->name] = $column;
		}
	}

	protected function drop($columns)
	{
		$this->__result = array();
	}

	protected function drop_column($columns)
	{
		foreach ($columns as $column) 
		{
			unset($this->__result[$column]);
		}
	}

}