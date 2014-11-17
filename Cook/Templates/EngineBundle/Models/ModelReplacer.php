<?php namespace Cook\Templates\EngineBundle\Models;

class ModelReplacer {

	public function renameFile($c)
	{
		return $c->Table;
	}

	public function accessible($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn("'$column->name',");
		}

		return $c->result->get();
	}

	public function rules($c)
	{
		foreach ($c->columns() as $column) 
		{
			$rule = ($column->rule) ? $column->rule : 'required';

			$c->result->addLn("'$column->name' => '$rule',");
		}

		return $c->result->get();
	}

	public function relations($c)
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