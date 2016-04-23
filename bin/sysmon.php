<?php

header('Content-Type: text/plain');

require_once __DIR__ . '/../vendor/autoload.php';

// Auto-loader
spl_autoload_register(function ($class) {

	// project-specific namespace prefix
	$prefix = 'Sysmon\\';

	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/../src/Sysmon/';

	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) 
	{
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	// if the file exists, require it
	if (file_exists($file)) {
		require_once $file;
	}
});


$contents = file_get_contents(__DIR__ . '/../config.json');
$parse = json_decode($contents, true);

//print_r($parse);

$loggers = array();

if (isset($parse['loggers']))
{
	foreach ($parse['loggers'] as $logger)
	{
		$class = 'Sysmon\\Logger\\' . str_replace('/', '\\', $logger['type']);

		$Logger = new $class($logger);
		$loggers[] = $Logger;
	}
}

foreach ($parse['monitors'] as $monitor)
{
	$class = 'Sysmon\\Plugin\\' . str_replace('/', '\\', $monitor['type']);

	$Plugin = new $class($monitor);

	print_r($Plugin);

	$stats = $Plugin->stats();
	foreach ($stats as $key => $Stat)
	{
		$method = $Stat->getMethod();

		$data = $Plugin->{$method}();

		$Logger->log($Plugin->getType(), $key, $data);
	}
}
