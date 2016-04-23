<?php
namespace Sysmon\Plugin;

class Resque extends \Sysmon\Plugin
{
	public function getQueueSizes()
	{
		$client = new \Predis\Client(array(
		    'scheme' => 'tcp',
		    'host'   => $this->getConfig('host', 'localhost'),
		    'port'   => $this->getConfig('port', 6379),
			));

		$queues = $this->getConfig('queues');
		if (is_string($queues))
		{
			$queues = explode(',', $queues);
		}
		else if (!$queues)
		{
			// Try to determine the queues
			$queues = $client->smembers('resque:queues');
		}

		$retr = array();
		foreach ($queues as $queue)
		{
			$retr[$queue] = $client->llen('resque:queue:' . $queue);
		}

		$retr['total'] = array_sum($retr);

		return $retr;
	}

	public function stats()
	{
		return array(
			'queue_sizes' => new \Sysmon\Stat('getQueueSizes', \Sysmon\Stat::TYPE_INT),
			);
	}
}