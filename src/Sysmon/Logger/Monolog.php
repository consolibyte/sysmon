<?php
namespace Sysmon\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \Vube\Monolog\Formatter\SplunkLineFormatter;

class Monolog extends \Sysmon\Logger
{
	public function __construct($opts)
	{
		parent::__construct($opts);

		print_r($opts);

		$this->_logger = new Logger('my_logger');
		
		$handler = new StreamHandler($opts['handlers'][0]['dest']);
		$handler->setFormatter(new SplunkLineFormatter());

		$this->_logger->pushHandler($handler);
	}

	public function log($name, $type, $data)
	{
		$this->_logger->addInfo($name . ' / ' . $type, $data);
	}
}