<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/SessionControl.php");

/* info we may receive from user input */
$dados = array(
		"clientid");

foreach ($dados as $dado) {
	${$dado} = isset($_GET[$dado]) ? $_GET[$dado] : NULL;
}

if ($clientid != NULL && is_numeric($clientid))
{
	$client = new Client($clientid);
	$client->load();

	$client->delete();
}

$Session = SessionControl::getInstance();
$Session->setMessage("Cliente deletado com sucesso.");

/* TODO set session message for succes and error */
header("Location: ../index.php"); /* Redirect browser */
?>
