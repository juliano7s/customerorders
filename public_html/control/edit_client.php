<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"clientinfoid",
		"clientinfoname",
		"clientinfoemail",
		"clientinfophone");

foreach ($dados as $dado) {
	${$dado} = isset($_POST[$dado]) ? $_POST[$dado] : NULL;
}

if ($clientinfoid != NULL && is_numeric($clientinfoid))
{
	$client = new Client($clientinfoid);
	$client->load();
	$client->setName($clientinfoname);
	$client->setEmail($clientinfoemail);
	$client->setPhone($clientinfophone);
	$client->save();
}

/* TODO set session message for succes and error */
header("Location: ../index.php"); /* Redirect browser */
?>
