<?php

use cook\libraries\Constructor;
use cook\libraries\Generator;
use cook\libraries\Helpers;

class Cook_Bundle_Task {

	public function run($arguments)
	{
		return Helpers::show('Here comes cook help');
	}

	public function __call($name, $arguments)
	{
		$template = $this->getTask();

		if ($template = Generator::getTemplate($template))
		{
			if ($arguments = Constructor::getArguments($arguments))
			{
				$constructor = new Constructor($name, $arguments);

				pp($constructor);

				$generator = new Generator($template, $constructor);

				return $generator->run();
			}

			return Helpers::show('Provide some arguments');
		}
		
		return Helpers::show('Template is not exist');
	}

	protected function getTask()
	{
		$task = explode('_', __CLASS__);

		if (isset($task[1]))
		{
			return mb_strtolower($task[1]);
		}

		return false;
	}

}