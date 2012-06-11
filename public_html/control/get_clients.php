<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/ClientManager.php");

$clientName = isset($_POST['clientname']) ? $_POST['clientname'] : NULL;

$clients = ClientManager::clientsByName($clientName);
$names = "";
foreach ($clients as $client)
{
	$names .= $client->getName() . "<br />";
}

echo json_encode(array("returnValue" => $names));

?>
