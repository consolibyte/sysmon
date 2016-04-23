<?php
namespace Sysmon\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Monolog extends \Sysmon\Logger
{
	public function __construct($opts)
	{
		parent::__construct($opts);

		print_r($opts);

		$this->_logger = new Logger('my_logger');
		
		$this->_logger->pushHandler(new StreamHandler($opts['handlers'][0]['dest']));
	}

	public function log($name, $type, $data)
	{
		$this->_logger->addInfo($name . ' / ' . $type . ' ' . json_encode($data));
	}
}