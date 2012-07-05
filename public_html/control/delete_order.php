<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"orderid");

foreach ($dados as $dado) {
	${$dado} = isset($_GET[$dado]) ? $_GET[$dado] : NULL;
}

if ($orderid != NULL && is_numeric($orderid))
{
	$order = new Order($orderid);
	$order->load();

	$order->delete();
}

/* TODO set session message for succes and error */
header("Location: ../index.php"); /* Redirect browser */
?>
