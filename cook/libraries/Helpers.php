<?php namespace cook\libraries;

class Helpers {

	public static function show($string)
	{
		echo PHP_EOL.$string.PHP_EOL;
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