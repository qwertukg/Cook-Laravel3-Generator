<?php namespace Cook\Constructor;

use Laravel\Fluent;
use Cook\Constructor;

class Storage extends Fluent {

	protected $currentBundle;

	public function addBundle($bundle)
	{
		$this->currentBundle = $bundle;

		if (! $this->$bundle)
		{
			$this->$bundle = new static;
		}
	}

	public function addTable(Constructor $constructor)
	{
		if (! $this->currentBundle) 
		{
			throw new \Exception('Undefinded current bundle. Invoke Cook\Constructor\Storage::addBundle() befor Cook\Constructor\Storage::addTable().', 1);
		}

		$bundleName = $this->currentBundle;
		$constructorName = $constructor->name;

		$this->$bundleName->$constructorName = $constructor;
	}

	public function show()
	{
		print_r($this);
	}

}