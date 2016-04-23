<?php
namespace Sysmon;

abstract class Plugin
{
	public function __construct($opts)
	{
		foreach ($opts as $key => $value)
		{
			$this->{'_' . $key} = $value;
		}
	}

	public function getType()
	{
		return get_class($this);
	}

	public function getName()
	{
		return get_class($this);
	}

	public function getConfig($key, $default = null)
	{
		$key = '_' . $key;

		$vars = get_object_vars($this);
		if (array_key_exists($key, $vars))
		{
			return $vars[$key];
		}

		return $default;
	}

	abstract function stats();
}
