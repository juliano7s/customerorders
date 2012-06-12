<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/ClientManager.php");
header('Content-Type: text/html; charset=UTF-8');

$clientName = isset($_POST['clientname']) ? $_POST['clientname'] : NULL;
//$clientName = isset($_GET['clientname']) ? $_GET['clientname'] : NULL;

$clients = ClientManager::clientsByName($clientName);
$names = "";
$idNames = array();
foreach ($clients as $client)
{
	$idName = array(
			"id" => $client->getId(),
			"name" => $client->getName()
			);
	$idNames["clients"][] = $idName;
	$names .= $client->getName() . "<br />";
}

echo json_encode($idNames);
//echo "<br />";
//echo "<br />";
//echo json_encode(array("returnValue" => $names));
//echo $names;
?>
