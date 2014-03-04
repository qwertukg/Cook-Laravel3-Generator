<?php namespace c\libraries;

use Laravel\Config;

class Constructor {

	/**
	 * Determine arguments storage.
	 *
	 */
	public $arguments = array();

	/**
	 * Argument key for current constructor condition.
	 *
	 */
	protected $argumentKey;
	protected $itemKey;
	protected $parameterKey;

	/**
	 * Prepare arguments for future work
	 *
	 */
	public function __construct(array $arguments)
	{
		$this->parseArguments($arguments);
	}

	/**
	 * Parse arguments, given from Artisan CLI, to Constructor format and fills them the arguments property.
	 *
	 * @param 	array 	$arguments
	 * @return 	array
	 */
	protected function parseArguments(array $arguments)
	{
		// Get options from config.
		$itemsDevider = Config::get('c::constructor.items_devider');
		$parametersDevider = Config::get('c::constructor.parameters_devider');
		$parametersPrefix = Config::get('c::constructor.parameters_prefix');
		$parametersPostfix = Config::get('c::constructor.parameters_postfix');

		foreach ($arguments as $argumentKey => $argument)
		{
			foreach (explode($itemsDevider, $argument) as $itemKey => $item)
			{
				if ($parameters = static::takeBetween($item, $parametersPrefix, $parametersPostfix))
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

		return $this->arguments;
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
	 * Get substring between devider simbols.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$from
	 * @param 	string 	$to
	 * @return 	string
	 */
	public static function takeBetween($string, $from = false, $to = false)
	{
		// Get options from config.
		$from = ($from) ?: Config::get('c::default.parameters_prefix');
		$to = ($to) ?: Config::get('c::default.parameters_postfix');

		$start = strpos($string, $from);
		$end = strpos($string, $to);

		if ($start !== false and $end !== false)
		{
			$length = $end - $start;
			$value = substr($string, $start, $length);
			$value = str_replace($from, '', $value);
			$value = str_replace($to, '', $value);

			return $value;
		}

		return false;
	}

	/**
	 * Get substring before devider simbol.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$before
	 * @return 	string
	 */
	public static function takeBefore($string, $before = false)
	{
		// Get options from config.
		$before = ($before) ?: Config::get('c::default.parameters_prefix');

		$to = strpos($string, $before);

		if ($to !== false)
		{
			return substr($string, 0, $to);
		}

		return false;
	}

	/**
	 * Get substring after devider simbol.
	 *
	 * @param 	string 	$string
	 * @param 	string 	$after
	 * @return 	string
	 */
	public static function takeAfter($string, $after = false)
	{
		// Get options from config.
		$after = ($after) ?: Config::get('c::default.parameters_postfix');

		$from = strrpos($string, $after);

		if ($from !== false)
		{
			return substr($string, $from + 1);
		}

		return false;
	}

	/**
	 * Determine is a substring is set in string
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