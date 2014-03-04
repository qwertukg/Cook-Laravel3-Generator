<?php namespace c\libraries;

use Laravel\Config;

class Constructor {

	public $arguments = array();
	public $item;
	public $params;

	public function __construct(array $arguments)
	{
		$this->parseArguments($arguments);
	}

	protected function parseArguments(array $arguments)
	{
		$itemsDevider = Config::get('c::default.items_devider');
		$paramsDevider = Config::get('c::default.params_devider');
		$paramsPrefix = Config::get('c::default.params_prefix');
		$paramsPostfix = Config::get('c::default.params_postfix');

		foreach ($arguments as $argumentKey => $argument)
		{
			foreach (explode($itemsDevider, $argument) as $itemKey => $item)
			{
				if ($params = static::takeBetween($item, $paramsPrefix, $paramsPostfix))
				{
					$this->item = static::takeBefore($item);
					$this->params = explode($paramsDevider, $params);
				}
				else
				{
					$this->item = $item;
				}

				$this->arguments[$argumentKey][$itemKey] = array(
					'item' => static::takeBefore($item),
					'params' => explode($paramsDevider, $params),
				);
			}
		}

		return $this->arguments;
	}

	public static function takeBetween($string, $from = false, $to = false)
	{
		$from = ($from) ?: Config::get('c::default.params_prefix');
		$to = ($to) ?: Config::get('c::default.params_postfix');

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

	public static function takeBefore($string, $before = false)
	{
		$before = ($before) ?: Config::get('c::default.params_prefix');

		$to = strpos($string, $before);

		if ($to !== false)
		{
			return substr($string, 0, $to);
		}

		return false;
	}


	public static function takeAfter($string, $after = false)
	{
		$after = ($after) ?: Config::get('c::default.params_postfix');

		$from = strrpos($string, $after);

		if ($from !== false)
		{
			return substr($string, $from + 1);
		}

		return false;
	}


	public static function hasString($haystack, $needle)
	{
		if (strpos($haystack, $needle) !== false)
		{
			return true;
		}

		return false;
	}
	
}