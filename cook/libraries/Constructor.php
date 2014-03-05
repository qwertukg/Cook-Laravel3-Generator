<?php namespace cook\libraries;

use Laravel\Config;
use Laravel\Str;

class Constructor {

	/**
	 * Determine storage for name and arguments.
	 *
	 */
	public $name;
	public $arguments = array();

	/**
	 * Argument key for current constructor condition.
	 *
	 */
	protected $argumentKey;
	protected $itemKey;
	protected $parameterKey;

	/**
	 * Set dish name and prepare arguments for future work
	 *
	 */
	public function __construct($name, array $arguments)
	{
		$this->name = Str::lower($name);

		$this->parseArguments($name, $arguments);
	}

	/**
	 * Parse arguments, given from Artisan CLI, to Constructor format and fills them the arguments property.
	 *
	 * @param 	string 	$name
	 * @param 	array 	$arguments
	 * @return 	void
	 */
	protected function parseArguments($name, array $arguments)
	{
		// Get options from config.
		$itemsDevider = Config::get('cook::constructor.items_devider');
		$parametersDevider = Config::get('cook::constructor.parameters_devider');

		foreach ($arguments as $argumentKey => $argument)
		{
			foreach (explode($itemsDevider, $argument) as $itemKey => $item)
			{
				if ($parameters = static::takeBetween($item))
				{
					$item = static::takeBefore($item);
					$parameters = explode($parametersDevider, $parameters);
				}

				// Fills the arguments.
				$this->arguments[$argumentKey][$itemKey] = array(
					'item' => $item,
					'parameters' => $parameters,
				);
			}
		}
	}

	/**
	 * Shortcuts for getting methods.
	 *
	 */
	public function arg($key) { return $this->argument($key); }
	public function param($key) { return $this->parameter($key); }

	/**
	 * Set current argument key.
	 *
	 * @param 	integer 	$key
	 * @return 	object
	 */
	public function argument($key)
	{
		if (isset($this->arguments[$key]))
		{
			$this->argumentKey = $key;
		}

		return $this;
	}

	/**
	 * Set current argument item key.
	 *
	 * @param $key integer
	 * @return object
	 */
	public function item($key)
	{
		if ($this->argumentKey !== null)
		{
			if (isset($this->arguments[$this->argumentKey][$key]))
			{
				$this->itemKey = $key;
			}
		}

		return $this;
	}

	/**
	 * Set current argument item parameter key.
	 *
	 * @param 	integer 	$key
	 * @return 	object
	 */
	public function parameter($key)
	{
		if ($this->argumentKey !== null)
		{
			if ($this->itemKey !== null)
			{
				if (isset($this->arguments[$this->argumentKey][$this->itemKey]['parameters'][$key]))
				{
					$this->parameterKey = $key;
				}
			}
		}

		return $this;
	}

	/**
	 * Getting an argument, item or parameter from arguments by sets keys.
	 *
	 * @return 	value
	 */
	public function get()
	{
		if ($this->argumentKey !== null and $this->itemKey !== null and $this->parameterKey !== null)
		{
			return $this->arguments[$this->argumentKey][$this->itemKey]['parameters'][$this->parameterKey];
		}
		elseif ($this->argumentKey !== null and $this->itemKey !== null)
		{
			return $this->arguments[$this->argumentKey][$this->itemKey];
		}
		elseif ($this->argumentKey !== null)
		{
			return $this->arguments[$this->argumentKey];
		}

		return false;
	}

	/**
	 * Check arguments from CLI.
	 *
	 * @param 	array 	$arguments
	 * @return 	array
	 */
	public static function getArguments(array $arguments)
	{
		if ($arguments)
		{
			return $arguments[0];
		}

		return false;
	}

	/**
	 * Get substring between devider symbols.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$from
	 * @param 	string 	$to
	 * @param 	bool 	$multi 	Set this as true if $string may have more then one token.
	 * @return 	string|array
	 */
	public static function takeBetween($string, $from = null, $to = null, $multi = false)
	{
		// Get options from config.
		$from = ($from) ?: Config::get('cook::constructor.parameters_prefix');
		$to = ($to) ?: Config::get('cook::constructor.parameters_postfix');

		$result = array();

		foreach (explode($to, $string) as $subString)
		{
			$subString .= $to;

			$start = strpos($subString, $from);
			$end = strpos($subString, $to);

			if ($start !== false and $end !== false)
			{
				$length = $end - $start;
				$value = substr($subString, $start, $length);
				$value = str_replace($from, '', $value);
				$value = str_replace($to, '', $value);

				$result[] = $value;

				// If false, return value as string.
				if (!$multi)
				{
					return $value;
				}
			}

		}

		return ($result) ? $result : false;
	}

	/**
	 * Get substring before devider symbol.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$before
	 * @return 	string
	 */
	public static function takeBefore($string, $before = null)
	{
		// Get options from config.
		$before = ($before) ?: Config::get('cook::constructor.parameters_prefix');

		$to = strpos($string, $before);

		if ($to !== false)
		{
			return substr($string, 0, $to);
		}

		return false;
	}

	/**
	 * Get substring after devider symbol.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$after
	 * @return 	string
	 */
	public static function takeAfter($string, $after = null)
	{
		// Get options from config.
		$after = ($after) ?: Config::get('cook::constructor.parameters_postfix');

		$from = strrpos($string, $after);

		if ($from !== false)
		{
			return substr($string, $from + 1);
		}

		return false;
	}

	/**
	 * Determine is a substring is set.
	 *
	 * @param 	string 	$haystack
	 * @param 	string 	$needle
	 * @return 	bool
	 */
	public static function hasString($haystack, $needle)
	{
		if (strpos($haystack, $needle) !== false)
		{
			return true;
		}

		return false;
	}
	
}