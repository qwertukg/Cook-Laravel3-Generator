<?php namespace Cook;

use Laravel\Bundle;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Input\ArgvInput;
use Laravel\File;

class Generator {

	// Bundle name where migration is set
	public $root;

	// Where root folder of current template
	public $templateRoot;

	// Constructor container
	public $constructor;

	// Templates collection
	public $templates;

	public function setTemplate(Template $template)
	{
		$this->templateRoot = $template->root;

		$this->templates = $template->templates;

		$this->constructor = $template->constructor;

		$this->root = Bundle::path($template->constructor->bundleName);

		$this->setTemplateResultAndPath();

		return $this;
	}

	public function run()
	{
		$this->write();

		// print_r($this);
	}

	protected function setTemplateResultAndPath() // WTF!
	{
		foreach ($this->templates as $i => $template) 
		{
			$template->result = str_replace($template->tokens, $template->replacers, $template->content);
			$template->path = substr(str_replace($this->templateRoot, '', $template->root), 1);
		}
	}

	protected function up()
	{
		foreach ($this->templates as $template) 
		{
			$name = ($template->newName) ? $template->newName : $template->name;

			$path = $this->root . DS . $template->path . DS . $name . EXT;

			if (!is_dir($this->root . DS . $template->path))
			{
				File::mkdir($this->root . DS . $template->path);
			}

			if (!File::exists($path))
			{
				File::put($path, $template->result);
				
				echo 'Cook: ' . $this->normalizePath($this->constructor->bundleName . DS . $template->path . DS . $name . EXT) . ' created!' . PHP_EOL;
			}
		}
	}

	protected function down()
	{
		foreach ($this->templates as $template) 
		{
			$name = ($template->newName) ? $template->newName : $template->name;

			$path = $this->root . DS . $template->path . DS . $name . EXT;

			if ($template->path and is_dir($this->root . DS . $template->path))
			{
				File::rmdir($this->root . DS . $template->path);

				echo 'Cook: ' . $this->normalizePath($this->constructor->bundleName . DS . $template->path . DS . $name . EXT) . ' deleted!' . PHP_EOL;
			}

			if (File::exists($path))
			{
				File::delete($path);

				echo 'Cook: ' . $this->normalizePath($this->constructor->bundleName . DS . $template->path . DS . $name . EXT) . ' deleted!' . PHP_EOL;
			}
		}
	}

	protected function write()
	{
		echo '------------------------------------------------------------------------' . PHP_EOL;

		if ($this->constructor->command === 'create')
		{
			$this->up();
		}

		if ($this->constructor->command === 'drop')
		{
			$this->down();
		}

		echo '------------------------------------------------------------------------' . PHP_EOL;
	}

	protected function normalizePath($path)
	{
		$path =  preg_replace('#[/\\\\]+#', DS, $path);

		return $path;
	}

}