<?php namespace Cook\Templates\EngineBundle\Language\Kz;

class DefaultReplacer {

	public function labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name' => '$column->kz',");
		}

		return $c->result->get();
	}

}