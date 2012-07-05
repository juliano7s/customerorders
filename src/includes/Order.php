<?php

require_once(realpath(dirname(__FILE__) . "/DBControl.php"));
require_once(realpath(dirname(__FILE__) . "/DataObject.php"));

class Order extends DataObject
{
	protected $client = NULL;
	protected $requestDate = NULL;
	protected $deliveryDate = NULL;
	protected $value = NULL;
	protected $cost = NULL;
	protected $description = NULL;
	protected $owner = NULL;
	protected $delivered = NULL;
	protected $ready = NULL;

	protected $_fieldAttrMapping = array(
			"orderid" => "id",
			"request_date" => "requestDate",
			"delivery_date" => "deliveryDate",
			);

	protected $_objectMapping = array(
			"clientid" => "Client"
			);

	public function __construct($order_id = 0, $is_stub = true)
	{
		$this->id = $order_id;
		$this->is_stub = $is_stub;
	}

	public function validate()
	{
		return true;
	}

	const DB_TABLE = "orders";
	const LOAD_BY_ID_QUERY = "SELECT * FROM orders WHERE orders.orderid = %s";
	const DELETE_BY_ID_QUERY = "DELETE FROM orders WHERE orders.orderid = %s";
}

?>
