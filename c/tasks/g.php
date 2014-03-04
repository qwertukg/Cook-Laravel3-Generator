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
		if ($this->getTemplate($template))
		{
			if ($arguments = $this->getArguments($arguments))
			{
				$constructor = new Constructor($arguments);

				pp($constructor->arg(1)->item(0)->param(1)->get());
				
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

	protected function getTemplate($template, $templatesPath = null)
	{
		$templatesPath = ($templatesPath) ?: Config::get('c::generator.templates_path');

		if (File::exists($templatesPath.$template))
		{
			return $templatesPath.$template;
		}

		return false;
	}

	protected function getArguments(array $arguments)
	{
		if ($arguments)
		{
			return $arguments[0];
		}

		return false;
	}

}