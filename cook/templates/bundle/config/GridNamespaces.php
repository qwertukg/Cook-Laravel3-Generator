<?php namespace cook\templates\bundle\config;

use cook\libraries\Generator;
use cook\libraries\iPartial;
use cook\libraries\Helpers;

class GridNamespaces extends Generator implements iPartial {

	public function fill()
	{
		/* Here come's code for fill token GridNamespaces in tpl file in this folder */

		return $this->arguments;
	}

}