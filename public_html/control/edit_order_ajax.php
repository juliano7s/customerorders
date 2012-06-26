<?php
require_once(realpath(dirname(__FILE__) . "/../../src/config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/Order.php");

/* info we may receive from user input */
$dados = array(
		"setorderdelivered",
		"orderid",
		"orderrequesdate",
		"orderdeliverydate",
		"ordervalue",
		"ordercost",
		"orderowner",
		"orderdescription");

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

	if ($orderrequesdate != NULL)
		$order->setRequestDate(DateTime::createFromFormat("d/m/Y", $orderrequestdate)->format("Y-m-d"));
	if ($orderdeliverydate != NULL)
		$order->setDeliveryDate(DateTime::createFromFormat("d/m/Y", $orderdeliverydate)->format("Y-m-d"));
	if ($ordervalue != NULL)
		$order->setValue(number_format(str_replace(',', '.',$ordervalue),2,'.',''));
	if ($ordercost != NULL)
		$order->setCost(number_format(str_replace(',', '.',$ordercost),2,'.',''));
	if ($orderowner != NULL)
		$order->setOwner($orderowner);
	if ($orderdescription != NULL)
		$order->setDescription($orderdescription);

	$order->save();

	$orderinfo["id"] = $order->getId();
	$orderinfo["delivered"] = $order->getDelivered();
	$orderinfo["requestdate"] = $order->getRequestDate();
	$orderinfo["deliverydate"] = $order->getDeliveryDate();
	$orderinfo["value"] = $order->getValue();
	$orderinfo["cost"] = $order->getCost();
	$orderinfo["owner"] = $order->getOwner();
	$orderinfo["description"] = $order->getDescription();
	echo json_encode($orderinfo);
}
?>
