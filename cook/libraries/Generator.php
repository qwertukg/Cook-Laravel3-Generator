<?php namespace cook\libraries;

use Laravel\File;
use Laravel\Config;
use FilesystemIterator;

class Generator {

	public $template;
	public $files = array();

	protected $arguments;
	protected $counter = 0;

	public function __construct($template, $arguments)
	{
		$this->template = $template;
		$this->arguments = $arguments;
	}

	public function run()
	{
		$this->iterator($this->template, $this, 'getTokens');

		print_r($this);
	}

	protected function getTokens($path)
	{
		$templatesExtention = Config::get('cook::generator.templates_extension');
		$tokenPrefix = Config::get('cook::generator.token_prefix');
		$tokenPostfix = Config::get('cook::generator.token_postfix');

		if (File::extension($path) == $templatesExtention)
		{
			foreach (explode(PHP_EOL, File::get($path)) as $string) 
			{
				if ($token = Constructor::takeBetween($string, $tokenPrefix, $tokenPostfix))
				{
					$this->files[$this->counter]['path'] = $path;
					$this->files[$this->counter]['tokens'][] = $token;
				}
			}
			
			$this->counter++;
		}
	}

	// all callbacks must have file path as firs parameter
	protected function iterator($path, $object, $callback, array $parameters = null)
	{
		$items = new FilesystemIterator($path);

		foreach ($items as $itemPath => $item)
		{
			if ($item->isDir())
			{
				$this->iterator($itemPath, $object, $callback, $parameters);
			}
			else
			{
				if ($parameters)
				{
					array_unshift($parameters, $itemPath);
				}
				else
				{
					$parameters = array($itemPath);
				}

				call_user_func_array(array($object, $callback), $parameters);
			}
		}
	}

	public static function getTemplate($template, $templatesPath = null)
	{
		$templatesPath = ($templatesPath) ?: Config::get('cook::generator.templates_path');

		if (File::exists($templatesPath.$template))
		{
			return $templatesPath.$template;
		}

		return false;
	}

}