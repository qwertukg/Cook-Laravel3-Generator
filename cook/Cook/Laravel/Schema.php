<?php namespace Cook\Laravel;

use Cook\Generator;
use Exception;
use Laravel\Database\Schema as LaravelSchema;
use Laravel\IoC;

class Schema extends LaravelSchema {

	public static function execute($table)
	{
		try 
		{
			$constructor = IoC::resolve('Constructor')->setTable($table);

			$template = IoC::resolve('Template')->setConstructor($constructor);

			$generator = IoC::resolve('Generator')->setTemplate($template);
				
			$generator->run();
		} 
		catch (Exception $e)
		{
			if ($e->getCode() !== 500)
			{
				throw $e;
			}
		}

		parent::execute($table);
	}

}