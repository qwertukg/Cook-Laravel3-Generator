<?php namespace Cook;

use Laravel\Database\Schema\Table;
use Laravel\Fluent;

class Constructor extends Table {
	
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

	/**
	 * Create a new fluent command instance.
	 *
	 * @param  string  $type
	 * @param  array   $parameters
	 * @return Fluent
	 */
	protected function command($type, $parameters = array())
	{
		$parameters = array_merge(compact('type'), $parameters);

		if (isset($parameters['name']))
		{
			$object = $this->commands[$parameters['name']] = new Fluent($parameters);
		}
		else
		{
			$object = $this->commands[] = new Fluent($parameters);
		}

		return $object;
	}

	/**
	 * Create a new fluent column instance.
	 *
	 * @param  string  $type
	 * @param  array   $parameters
	 * @return Fluent
	 */
	protected function column($type, $parameters = array())
	{
		$parameters = array_merge(compact('type'), $parameters);

		if (isset($parameters['name']))
		{
			$object = $this->columns[$parameters['name']] = new Fluent($parameters);
		}
		else
		{
			$object = $this->columns[] = new Fluent($parameters);
		}

		return $object;
	}

}