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
		$this->storage = new Storage;

		$this->root = Bundle::path($template->constructor->bundleName);
		
		$this->templateRoot = $template->root;

		$this->templates = $template->templates;

		$this->constructor = $template->constructor;

		$this->setTemplateResult();

		$this->setTemplateResultPaths();

		return $this;
	}

	public function run()
	{
		$this->write();

		// print_r($this);
	}

	protected function setTemplateResult()
	{
		foreach ($this->templates as $template) 
		{
			ksort($template->replacers);
			ksort($template->tokens);
			
			$template->result = str_replace($template->tokens, array_values($template->replacers), $template->content);
		}
	}

	protected function setTemplateResultPaths()
	{
		foreach ($this->templates as $template) 
		{
			$name = ($template->newName) ? $template->newName : $template->name;

			$template->resultPath = $this->normalizePath($this->root . DS . $template->path);
			$template->resultPathWithFilename = $this->normalizePath($this->root . DS . $template->path . DS . $name . EXT);
			$template->resultPathFromBundle = $this->normalizePath($this->constructor->bundleName . DS . $template->path . DS . $name . EXT);
		}
	}

	protected function up()
	{
		foreach ($this->templates as $template) 
		{
			if (!is_dir($template->resultPath))
			{
				File::mkdir($template->resultPath);
			}

			if (!File::exists($template->resultPathWithFilename))
			{
				if ($this->storage->log($template->resultPathFromBundle, $template->result))
				{
					File::put($template->resultPathWithFilename, $template->result);

					echo 'Cook: ' . $template->resultPathFromBundle . ' created!' . PHP_EOL;
				}
			}
		}
	}

	protected function down()
	{
		foreach ($this->templates as $template) 
		{
			if (File::exists($template->resultPathWithFilename))
			{
				if ($this->storage->delete($template->resultPathFromBundle, File::get($template->resultPathWithFilename)))
				{
					File::delete($template->resultPathWithFilename);

					echo 'Cook: ' . $template->resultPathFromBundle . ' deleted!' . PHP_EOL;
				}
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