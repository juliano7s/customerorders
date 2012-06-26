<?php

require_once(realpath(dirname(__FILE__) . "/DBControl.php"));
require_once(realpath(dirname(__FILE__) . "/Order.php"));

class OrderManager
{
	public static function orders($clientid = NULL,  $dlvdate = NULL, $reqdate = NULL)
	{
		$DBCtrl = DBControl::getInstance();
		$DBCtrl->connect();

		$queryOrders = sprintf(OrderManager::ALL_ORDERS);
		$conditions = array();

		if ($clientid != NULL)
			$conditions[] = sprintf(OrderManager::SUBQUERY_CLIENTID, $DBCtrl->parse($clientid));
		if ($reqdate != NULL)
			$conditions[] = sprintf(OrderManager::SUBQUERY_REQUESTDATE, $DBCtrl->parse($reqdate));
		if ($dlvdate != NULL)
			$conditions[] = sprintf(OrderManager::SUBQUERY_DELIVERYDATE, $DBCtrl->parse($dlvdate));

		if (!count($conditions))
			$conditions[] = " 1";

		$queryOrders .= implode($conditions, "AND");
		$queryOrders .= OrderManager::ALL_ORDERS_ORDERBY;
		$result = mysql_query($queryOrders);

		$orders = array();
		$i = 0;
		if ($result)
		{
			while ($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				$orders[$i] = new Order($row[0]);
				$i++;
			}
			mysql_free_result($result);
		}

		$DBCtrl->disconnect();
		return $orders;
	}

	const ALL_ORDERS = "SELECT orderid FROM orders WHERE ";
	const ALL_ORDERS_ORDERBY = " ORDER BY orders.delivery_date ASC";
	const SUBQUERY_CLIENTID = " orders.clientid = %s ";
	const SUBQUERY_REQUESTDATE = " orders.request_date = %s ";
	const SUBQUERY_DELIVERYDATE = " orders.delivery_date = %s ";
}
?>
