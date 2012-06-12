<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");


/* info we may receive from user input */
$dados = array(
		"client-id",
		"client-name",
		"client-email",
		"client-phone",
		"order-request-date",
		"order-delivery-date",
		"order-value",
		"order-cost",
		"order-owner",
		"order-description");

foreach ($dados as $dado) {
	/* para confirmar quem tem dado em casa */
	${$dado} = isset($_POST[$dado]) ? $_POST[$dado] : NULL;
	if (isset($_POST[$dado]))
		echo $dado . " - " . $_POST[$dado] . "<br />";
	else
		echo $dado . " not set on POST<br />";
}




?>
