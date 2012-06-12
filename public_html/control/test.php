<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/ClientManager.php");

$name = "Ma";
$clients = ClientManager::clientsByName($name);

foreach($clients as $client)
{
	echo $client->getName() . "<br />";
}

?>
