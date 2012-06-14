<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"clientid",
		"clientname",
		"clientemail",
		"clientphone",
		"orderrequestdate",
		"orderdeliverydate",
		"ordervalue",
		"ordercost",
		"orderowner",
		"orderdescription");

foreach ($dados as $dado) {
	${$dado} = isset($_POST[$dado]) ? $_POST[$dado] : NULL;
}

/* TODO verify data format: cost, value, dates */

$client = NULL;
if ($clientid == 0 || trim($clientid) == "")
{
	/* Add client if id is not set */
	$client = new Client();
	$client->setName($clientname);
	$client->setEmail($clientemail);
	$client->setPhone($clientphone);

	/* TODO try/catch */
	$client->save();
} else
{
	$client = new Client($clientid);
}
$order = new Order();
$order->setRequestDate(DateTime::createFromFormat("d/m/Y",$orderrequestdate)->format("Y-m-d"));
$order->setDeliveryDate(DateTime::createFromFormat("d/m/Y",$orderdeliverydate)->format("Y-m-d"));
$order->setValue(number_format(str_replace(',', '.',$ordervalue),2,'.',''));
$order->setCost(number_format(str_replace(',', '.',$ordercost),2,'.',''));
$order->setOwner($orderowner);
$order->setDescription($orderdescription);
$order->setClient($client);

/* TODO try/catch */
$order->save();

/* TODO set session message for succes and error */
header("Location: ../index.php"); /* Redirect browser */
?>
