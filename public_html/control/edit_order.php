<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"setorderdelivered",
		"orderid",
		"orderreqdate",
		"orderdlvdate",
		"ordervalue",
		"ordercost",
		"orderowner",
		"orderdescription");

foreach ($dados as $dado) {
	${$dado} = isset($_POST[$dado]) ? $_POST[$dado] : NULL;
}

if ($orderid != NULL && is_numeric($orderid))
{
	$order = new Order($orderid);
	$order->load();

	if ($setorderdelivered != NULL)
	{
		if ($order->getDelivered() == 0)
			$order->setDelivered(1);
		else
			$order->setDelivered(0);
	}

	if ($orderreqdate != NULL)
		$order->setRequestDate(DateTime::createFromFormat("d/m/Y", $orderreqdate)->format("Y-m-d"));
	if ($orderdlvdate != NULL)
		$order->setDeliveryDate(DateTime::createFromFormat("d/m/Y", $orderdlvdate)->format("Y-m-d"));
	if ($ordervalue != NULL)
		$order->setValue(number_format(str_replace(',', '.',$ordervalue),2,'.',''));
	if ($ordercost != NULL)
		$order->setCost(number_format(str_replace(',', '.',$ordercost),2,'.',''));
	if ($orderowner != NULL)
		$order->setOwner($orderowner);
	if ($orderdescription != NULL)
		$order->setDescription($orderdescription);

	$order->save();
}

/* TODO set session message for succes and error */
header("Location: ../index.php"); /* Redirect browser */
?>
