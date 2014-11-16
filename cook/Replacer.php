<?php namespace Cook;

use ReflectionClass;

class Replacer {

	public static function rename($class, Constructor $constructor)
	{
		$className = $class.'_Replacer';

		$reflection = new ReflectionClass($className);

		if ($reflection->hasMethod('rename'))
		{
			return call_user_func(array($className, 'rename'), $constructor);
		}
	}

	public static function run($class, $method, Constructor $constructor)
	{
		$className = $class.'_Replacer';
		$methodName = 'replace_'.$method;

		$reflection = new ReflectionClass($className);

		if ($reflection->hasMethod($methodName))
		{
			return call_user_func(array($className, $methodName), $constructor);
		}
		else
		{
		}
	}

}