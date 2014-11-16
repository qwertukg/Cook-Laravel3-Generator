<?php

class Model_Replacer {

	public function rename($c)
	{
		return $c->Table;
	}

	public function replace_accessible($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name',");
		}

		return $c->result->get();
	}

	public function replace_rules($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name' => 'required',");
		}

		return $c->result->get();
	}

	public function replace_relations($c)
	{
		foreach ($c->relations() as $relation) 
		{
			$c->result->addLn('public function ' . $relation->name . '()');

			$c->result->addLn('{');

			$c->result->addLn("\t" . 'return $this->belongs_to(IoC::resolve(\'' . $relation->Name . "Model'));");

			$c->result->addLn('}');
		}

		return $c->result->get();
	}

}