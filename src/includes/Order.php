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

	protected $_fieldAttrMapping = array(
			"orderid" => "id",
			);

	protected $_objectMapping = array(
			"clientid" => "Client"
			);
	
	public function __construct($order_id = 0, $is_stub = true)
	{

	}

	protected function validate()
	{
		return true;
	}

	const DB_TABLE = "orders";
	const LOAD_BY_ID_QUERY = "SELECT * FROM orders WHERE orders.orderid = %s";
}

?>
