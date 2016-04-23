<?php
namespace Sysmon;

class Stat
{
	const TYPE_INT = 'int';

	public function __construct($method, $retr)
	{
		$this->_method = $method;
		$this->_retr = $retr;
	}

	public function getMethod()
	{
		return $this->_method;
	}
}