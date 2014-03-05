<?php namespace cook\libraries;

use cook\libraries\Constructor as C;
use cook\libraries\Helpers as H;
use FilesystemIterator;
use Laravel\Config;
use Laravel\File;
use Laravel\Bundle;

class Generator extends C {

	public $template;
	public $files = array();
	public $arguments;

	protected $counter = 0;

	public function __construct($template, $arguments)
	{
		$this->template = $template;
		$this->arguments = $arguments;

	}

	public function run()
	{
		$this->recursiveFileCallbackIterator($this->template, $this, 'getFiles');

		// H::dd($this->fillToken('D:\www\kazpost.ibecsystems.kz\bundles\cook\temp\Articles\config\default.tpl', 'FormNamespaces', '\cook\templates\bundle\config\FormNamespaces')); 
		// H::pp($this->toTemp());
		H::pp($this);
		
		// H::dd($this->Constructor);
	}

	protected function toTemp()
	{
		$destination = path('bundle').'cook'.DS.'temp'.DS.C::bundleName($this->arguments->name);

		return File::cpdir($this->template, $destination);
	}

	protected function removeTemp()
	{
		$directory = path('bundle').'cook'.DS.'temp'.DS.C::bundleName($this->arguments->name);

		File::rmdir($directory);

		if (!File::exists($directory))
		{
			return true;
		}

		return false;
	}

	protected function fillToken($path, $token, $partialClass)
	{
		$tokenPrefix = Config::get('cook::generator.token_prefix');
		$tokenPostfix = Config::get('cook::generator.token_postfix');

		if ($template = File::get($path))
		{
			$partial = new $partialClass($this->arguments);

			H::pp($partial);

			$token = $tokenPrefix.$token.$tokenPostfix;

			$template = str_replace($token, $partial->fill(), $template);

			return $template;
		}

		return false;
	}

	protected function getFiles($path)
	{
		$templatesExtention = Config::get('cook::generator.templates_extension');
		$tokenPrefix = Config::get('cook::generator.token_prefix');
		$tokenPostfix = Config::get('cook::generator.token_postfix');

		if (File::extension($path) == $templatesExtention)
		{
			foreach (explode(PHP_EOL, File::get($path)) as $key => $string) 
			{
				// May be more then one token per line, or nothing.
				if ($tokens = C::takeBetween($string, $tokenPrefix, $tokenPostfix, true))
				{
					foreach ($tokens as $token) 
					{
						// Check is exists the partial file.
						if ($partial = static::getPartial($path, $token))
						{
							$this->files[$this->counter]['path'] = $path;
							$this->files[$this->counter]['name'] = static::getFileName($path);
							$this->files[$this->counter]['rename'] = static::getRenameClass($path);
							$this->files[$this->counter]['items'][] = array(
								'partial' => $partial,
								'partialClass' => static::getClass($partial),
								'token' => $token, 
								'position' => static::countTabsBefore($string),
								'result' => null,
							);
						}
					}
				}
			}
			
			$this->counter++;
		}
	}

	// all callbacks must have file path as firs parameter
	protected function recursiveFileCallbackIterator($path, $object, $callbackString, array $parameters = null)
	{
		$items = new FilesystemIterator($path);

		foreach ($items as $itemPath => $item)
		{
			if ($item->isDir())
			{
				$this->recursiveFileCallbackIterator($itemPath, $object, $callbackString, $parameters);
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

				call_user_func_array(array($object, $callbackString), $parameters);
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

	public static function countTabsBefore($string, $before = null, $tabSymbol = "\t")
	{
		$before = ($before) ?: Config::get('cook::generator.token_prefix');

		if (($stringBefore = C::takeBefore($string, $before)) !== false)
		{
			return mb_substr_count($stringBefore, $tabSymbol);
		}

		return false;
	}

	public static function getFileName($path, $ext = false)
	{
		if ($fileName = C::takeAfter($path, DS))
		{
			return ($ext) ? $fileName : C::takeBefore($fileName, '.');
		}

		return false;
	}

	public static function getPartial($templatePath, $token)
	{
		if ($templateName = C::takeAfter($templatePath, DS))
		{
			$partialPath = str_replace($templateName, $token, $templatePath).EXT;

			if (File::exists($partialPath))
			{
				return $partialPath;
			}

			return false;
		}
		
		return false;
	}

	public static function getClass($partialPath)
	{
		$afterBundlePath = DS.'cook'.DS.C::takeAfter($partialPath, Bundle::path('cook'));

		$withoutExtPath = C::takeBefore($afterBundlePath, EXT);

		return str_replace(DS, '\\', $withoutExtPath);
	}

	public function getRenameClass($templatePath, $renamePostfix = null)
	{
		$renamePostfix = ($renamePostfix) ?: Config::get('cook::generator.rename_postfix');

		$withoutNamePath = C::takeBefore($templatePath, static::getFileName($templatePath, true));

		$name = static::getFileName($templatePath);

		if (File::exists($withoutNamePath.$name.$renamePostfix.EXT))
		{
			return static::getClass($withoutNamePath.$name.$renamePostfix.EXT);
		}

		return false;
	}

}