<?php

class Default2_Replacer {

	public function replace_labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name' => '$column->kz',");
		}

		return $c->result->get();
	}

}