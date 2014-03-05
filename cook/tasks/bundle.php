<?php

use cook\libraries\Constructor as C;
use cook\libraries\Generator as G;
use cook\libraries\Helpers as H;

class Cook_Bundle_Task {

	public function run($arguments)
	{
		return H::show('Here comes cook help');
	}

	public function __call($name, $arguments)
	{
		if ($template = G::getTemplate($this->getTask()))
		{
			if ($arguments = C::getArguments($arguments))
			{
				$constructor = new C($name, $arguments);

				$generator = new G($template, $constructor);

				return $generator->run();
			}

			return H::show('Provide some arguments');
		}
		
		return H::show('Template is not exist');
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