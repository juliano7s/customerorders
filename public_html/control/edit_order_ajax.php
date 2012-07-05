<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"setorderdelivered",
		"setorderready",
		"orderid");

foreach ($dados as $dado) {
	${$dado} = isset($_POST[$dado]) ? $_POST[$dado] : NULL;
}

$orderid = ($orderid != NULL) ? substr($orderid,5) : NULL;

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

	if ($setorderready != NULL)
	{
		if ($order->getReady() == 0)
			$order->setReady(1);
		else
			$order->setReady(0);
	}

	$order->save();

	$orderinfo["id"] = $order->getId();
	$orderinfo["delivered"] = $order->getDelivered();
	$orderinfo["ready"] = $order->getReady();
	echo json_encode($orderinfo);
}
?>
