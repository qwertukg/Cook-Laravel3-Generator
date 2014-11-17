<?php namespace Cook;

use ReflectionClass;
use Laravel\Str;

class Replacer {

	public static function renameFile($replacerClassName, Constructor $constructor)
	{
		$className = Str::title($replacerClassName) . '_Replacer';

		$reflection = new ReflectionClass($className);

		if ($reflection->hasMethod('rename'))
		{
			return call_user_func(array($className, 'rename'), $constructor);
		}
	}

	public static function runCommand($replacerClassName, $method, Constructor $constructor)
	{
		$className = Str::title($replacerClassName) . '_Replacer';
		$methodName = 'replace_'.$method;

		$reflection = new ReflectionClass($className);

		if ($reflection->hasMethod($methodName))
		{
			return call_user_func(array($className, $methodName), $constructor);
		}
	}

}