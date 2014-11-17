<?php

class Default_Replacer {

	public function replace_labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name' => '$column->ru',");
		}

		return $c->result->get();
	}

}