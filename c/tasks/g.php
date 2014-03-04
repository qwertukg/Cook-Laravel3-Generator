<?php

use Laravel\File;
use c\libraries\Constructor;
use c\libraries\Generator;

class C_G_Task {

	protected $arguments = array();
	
	public function run($arguments)
	{
		echo "Run G methods list with help\n";
	}

	public function __call($template, $arguments)
	{
		if ($this->issetTemplate($template))
		{
			if ($arguments = $this->issetArguments($arguments))
			{
				$constructor = new Constructor($arguments);

				dd($constructor);
				
				$generator = new Generator($template, $constructor);

				$generator->run();
			}
			else
			{
				echo "Run $template generator help\n";
			}
		}
		else
		{
			echo "Template is not exist\n";
		}
	}

	protected function issetTemplate($template, $templatesPath = null)
	{
		$templatesPath = ($templatesPath) ?: Config::get('c::default.templates_path');

		if (File::exists($templatesPath.$template))
		{
			return true;
		}

		return false;
	}

	protected function issetArguments(array $arguments)
	{
		if ($arguments)
		{
			return $arguments[0];
		}

		return false;
	}

}