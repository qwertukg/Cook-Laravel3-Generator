<?php namespace cook\libraries;

class Helpers {

	public static function show($string, $exit = false)
	{
		echo PHP_EOL.$string.PHP_EOL;

		if ($exit)
		{
			exit();
		}
	}

	public static function pp($var, $exit = true)
	{
		echo PHP_EOL;
		print_r($var);
		echo PHP_EOL;

		if ($exit)
		{
			exit();
		}
	}

	public static function dd($var, $exit = true)
	{
		echo PHP_EOL;
		var_dump($var);
		echo PHP_EOL;

		if ($exit)
		{
			exit();
		}
	}

}