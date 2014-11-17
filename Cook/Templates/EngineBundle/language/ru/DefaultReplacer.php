<?php namespace Cook\Templates\EngineBundle\Language\Ru;

class DefaultReplacer {

	public function labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name' => '$column->ru',");
		}

		return $c->result->get();
	}

}