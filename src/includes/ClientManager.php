<?php

require_once(realpath(dirname(__FILE__) . "/DBControl.php"));
require_once(realpath(dirname(__FILE__) . "/Client.php"));

class ClientManager
{
	public static function clientsByName($name)
	{
		$DBCtrl = DBControl::getInstance();
		$DBCtrl->connect();

		$result = mysql_query(sprintf(ClientManager::CLIENTS_BY_NAME, "'" . "%" . $name . "%" . "'"));

		$clients = array();
		$i = 0;
		if ($result)
		{
			while ($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				$clients[$i] = new Client($row[0]);
				$i++;
			}
			mysql_free_result($result);
		}

		$DBCtrl->disconnect();
		return $clients;
	}

	const CLIENTS_BY_NAME = "SELECT clientid FROM clients WHERE name LIKE %s";
}
?>
