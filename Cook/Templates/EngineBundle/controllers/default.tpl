<?php

class <controllerPrefix>_Admin_Default_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->model = IoC::resolve('<Table>Model');

		<with>
	}

} 

