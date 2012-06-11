<?php

require_once(realpath(dirname(__FILE__) . "/DBControl.php"));
require_once(realpath(dirname(__FILE__) . "/DataObject.php"));

class Client extends DataObject
{
	protected $name = NULL;
	protected $phone = NULL;
	protected $email = NULL;

	protected $_fieldAttrMapping = array(
			"clientid" => "id",
			);

	protected $_objectMapping = array(
			/* "settingsid" => "UserSettings" */);
	
	public function __construct($client_id = 0, $is_stub = true)
	{
		$this->id = $client_id;
		$this->is_stub = $is_stub;
	}

	public function validate()
	{
		return true;
	}

	const DB_TABLE = "clients";
	const LOAD_BY_ID_QUERY = "SELECT * FROM clients WHERE clients.clientid = %s";
}

?>
