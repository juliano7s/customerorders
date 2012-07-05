<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");
require_once(INCLUDES_PATH . "/SessionControl.php");

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

$Session = SessionControl::getInstance();

$client = NULL;
if ($clientid == 0 || trim($clientid) == "")
{
	/* Add client if id is not set */
	$client = new Client();
	$client->setName($clientname);
	$client->setEmail($clientemail);
	$client->setPhone($clientphone);

	try {
		$client->save();
	} catch (Exception $e)
	{
		$Session->setErrorMessage("Erro ao adicionar cliente");
		header("Location: ../index.php"); /* Redirect browser */
		exit;
	}
	$Session->setMessage("Cliente adicionado com sucesso.");
} else
{
	$client = new Client($clientid);
}
$order = new Order();
if ($orderrequestdate != NULL)
	$order->setRequestDate(DateTime::createFromFormat("d/m/Y",$orderrequestdate)->format("Y-m-d"));
if ($orderdeliverydate != NULL)
	$order->setDeliveryDate(DateTime::createFromFormat("d/m/Y",$orderdeliverydate)->format("Y-m-d"));
if ($ordervalue != NULL)
	$order->setValue(number_format(str_replace(',', '.',$ordervalue),2,'.',''));
if ($ordercost != NULL)
	$order->setCost(number_format(str_replace(',', '.',$ordercost),2,'.',''));
if ($orderowner != NULL)
	$order->setOwner($orderowner);
if ($orderdescription != NULL)
	$order->setDescription($orderdescription);
$order->setDelivered(0);
$order->setReady(0);
$order->setClient($client);

try{
	$order->save();
} catch (Exception $e)
{
	$Session->setErrorMessage("Erro ao adicionar pedido");
	header("Location: ../index.php"); /* Redirect browser */
	exit;
}

$Session->setMessage("Pedido adicionado com sucesso.");

header("Location: ../index.php"); /* Redirect browser */
?>
