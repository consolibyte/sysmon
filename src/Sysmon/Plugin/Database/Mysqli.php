<?php
namespace Sysmon\Plugin\Database;

class Mysqli extends \Sysmon\Plugin\Database
{
	protected function _connect()
	{
		$this->_conn = mysqli_connect($this->_host, $this->_user, $this->_pass);
	}

	public function getQueriesPerSecond()
	{
		$time = 0;
		$stats = $this->_readStats($time);

		return array( 'queries_per_second' => (($stats['Queries'][1] - $stats['Queries'][0]) / $time) );
	}

	protected function _readStats(&$time)
	{
		$this->_connect();

		$start = microtime(true);

		$data = array();

		$res = mysqli_query($this->_conn, "SHOW STATUS");
		while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC))
		{
			$data[$arr['Variable_name']] = array( $arr['Value'], null );
		}

		sleep(1);

		$res = mysqli_query($this->_conn, "SHOW STATUS");
		while ($arr = mysqli_fetch_array($res))
		{
			$data[$arr['Variable_name']][1] = $arr['Value'];
		}

		$time = microtime(true) - $start;

		return $data;
	}

	public function stats()
	{
		return array(
			'queries_per_second' => new \Sysmon\Stat('getQueriesPerSecond', \Sysmon\Stat::TYPE_INT),
			);
	}
}