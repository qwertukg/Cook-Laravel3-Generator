<?php namespace c\libraries;

use Laravel\File;
use Laravel\Config;
use FilesystemIterator;

class Generator {

	public $template;
	public $arguments;
	public $tokens = array();

	private $counter = 0;

	public function __construct($template, $arguments)
	{
		$constructor = new Constructor;

		$this->template = $template;
		$this->arguments = $constructor->parseArguments($arguments);
	}

	public function run()
	{
		$this->getTokens($this->template, true);

		print_r($this);
	}

	protected function getTokens($template, $firstCall = false)
	{
		$templatesPath = Config::get('c::generator.templates_path');
		$templatesExtention = Config::get('c::generator.templates_extension');
		$tokenPrefix = Config::get('c::generator.token_prefix');
		$tokenPostfix = Config::get('c::generator.token_postfix');

		if ($firstCall)
		{
			$template = $templatesPath.$template;
		}
		
		$items = new FilesystemIterator($template);

		foreach ($items as $path => $item)
		{
			if ($item->isDir())
			{
				$this->getTokens($path);
			}
			elseif (File::extension($path) == $templatesExtention)
			{
				$file = File::get($path);

				foreach (explode(PHP_EOL, $file) as $string) 
				{
					if ($token = Constructor::takeBetween($string, $tokenPrefix, $tokenPostfix))
					{
						$this->tokens[$this->counter]['path'] = $path;
						$this->tokens[$this->counter]['partials'][] = $token;
					}
				}
				
				$this->counter++;
			}
		}

		return $this->tokens;
	}

}