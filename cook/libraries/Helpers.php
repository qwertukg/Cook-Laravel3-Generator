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
		print_r($var);

		if ($exit)
		{
			exit();
		}
	}

	public static function dd($var, $exit = true)
	{
		var_dump($var);

		if ($exit)
		{
			exit();
		}
	}

}