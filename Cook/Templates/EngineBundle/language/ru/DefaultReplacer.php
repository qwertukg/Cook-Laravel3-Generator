<?php namespace Cook\Templates\EngineBundle\Language\Ru;

class DefaultReplacer {

	public function renameFile($c)
	{
		return $c->tables;
	}

	public function labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn(Q.$column->name.Q.' => '.Q.$column->ru.Q.',');
		}

		return $c->result->get();
	}

}