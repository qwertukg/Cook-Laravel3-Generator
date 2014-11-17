<?php namespace Cook;

use ReflectionClass;
use Laravel\Str;

class Replacer {

	public static function renameFile($replacerObject, Constructor $constructor)
	{
		if (method_exists($replacerObject, 'renameFile')) 
		{
			return $replacerObject->renameFile($constructor);
		}
	}

	public static function runCommand($replacerObject, $method, Constructor $constructor)
	{
		if (method_exists($replacerObject, $method)) 
		{
			return $replacerObject->$method($constructor);
		}
	}

}