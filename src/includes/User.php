<?php
/**
 *	Users table database functions	
 *
 */
require_once(realpath(dirname(__FILE__) . "/DBControl.php")); 
require_once(realpath(dirname(__FILE__) . "/DataObject.php")); 
require_once(realpath(dirname(__FILE__) . "/UserSettings.php")); 
require_once(realpath(dirname(__FILE__) . "/City.php")); 
require_once(realpath(dirname(__FILE__) . "/SecurePassword.php")); 

class User extends DataObject {

	protected $city = NULL;
	protected $username = NULL;
	protected $password = NULL;
	protected $fullname = NULL;
	protected $pictureid = NULL;
	protected $gender = NULL;
	protected $birthday = NULL;
	protected $credibility = 1;
	protected $points = NULL;
	protected $permLevel = 0;
	protected $lastAccess = NULL;
	protected $lastAct = NULL;
	protected $userSettings = NULL;
	protected $emailConfirmed = NULL;

	protected $_fieldAttrMapping = array(
			"userid" => "id",
			"userpass" => "password",
			"lastaccess" => "lastAccess",
			"lastact" => "lastAct",
			"permlevel" => "permLevel",
			);

	protected $_objectMapping = array(
			"cityid" => "City",
			"settingsid" => "UserSettings");

	function __construct($userid = 0, $is_stub = true) {
		$this->_stub = $is_stub;
		$this->id = $userid;
	}

	function validate() {
		return true;
	}

	function verify() {
		$DBCtrl = DBControl::getInstance();

		if ($this->username == NULL || $this->password == NULL)
			throw new Exception("User object not initialized. Can't validate user");

		$DBCtrl->connect();

		$result = mysql_query(sprintf(User::VALIDATE_QUERY,
					$DBCtrl->parse($this->username), 
					$DBCtrl->parse($this->password))); 

		if (!$result)
			throw new Exception("Query failed: " . mysql_error());

		$line = mysql_fetch_array($result, MYSQL_ASSOC);

		if ($line['username'] === $this->username && $line['userpass'] === $this->password) {
			$this->id = $line['userid'];
			$ret = true;
		} else {
			$ret = false;
		}

		mysql_free_result($result);
		$DBCtrl->disconnect();

		return $ret;
	}

	function setPassword($pass) {
		$this->password = SecurePassword::SaltAndHashPassword($pass);
		return true;
	}

	public function setPasswordHash($hash) {
		$this->password = $hash;
		return true;
	}

	public function hasAdminLvl() {
		if ($this->getPermLevel() == SessionControl::ADMIN_LVL)
			return true;
		else
			return false;
	}

	public function hasCollLvl() {
		if ($this->permLevel == SessionControl::COLL_LVL)
			return true;
		else
			return false;
	}

	public function hasPremiumLvl() {
		if ($this->permLevel == SessionControl::PREMIUM_LVL)
			return true;
		else
			return false;
	}

	public function hasUserLvl() {
		if ($this->permLevel == SessionControl::USER_LVL)
			return true;
		else
			return false;
	}

	public function atLeastCollLvl() {
		if ($this->permLevel <= SessionControl::COLL_LVL)
			return true;
		else
			return false;
	}

	public function atLeastPremiumLvl() {
		if ($this->permLevel <= SessionControl::PREMIUM_LVL)
			return true;
		else
			return false;
	}

	public function atLeastUserLvl() {
		if ($this->permLevel <= SessionControl::USER_LVL)
			return true;
		else
			return false;
	}

	public static function findIdByEmail($email)
	{
		$DBCtrl = DBControl::getInstance();

		if (! filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new Exception("Not a valid e-mail.");

		$DBCtrl->connect();

		$result = mysql_query(sprintf(User::VALIDATE_QUERY,
					$DBCtrl->parse($email)));

		if (!$result)
			throw new Exception("Query failed: " . mysql_error());

		$line = mysql_fetch_array($result, MYSQL_ASSOC);

		mysql_free_result($result);
		$DBCtrl->disconnect();

		return $line['userid'];
	}

	const DB_TABLE = "users";
	const LOAD_BY_ID_QUERY = "SELECT * FROM users WHERE users.userid = %s";
	const VALIDATE_QUERY = "SELECT userid, userpass, username FROM users WHERE username=%s";
}
?>
