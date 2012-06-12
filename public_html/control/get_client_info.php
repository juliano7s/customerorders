<?php
error_reporting(E_ALL);
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");

$clientid = isset($_POST['clientid']) ? $_POST['clientid'] : NULL;

$client = new Client(substr($clientid, 6));
$clientjson = array();
$clientjson["client"] = array(
	"id" => $client->getId(),
	"name" => $client->getName(),
	"email" => $client->getEmail(),
	"phone" => $client->getPhone()
	);

echo json_encode($clientjson);
?>
