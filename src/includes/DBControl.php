<?php
/**
 *	Database control functions
 *
 */
require_once(realpath(dirname(__FILE__) . "/../config.php")); 
require_once(realpath(dirname(__FILE__) . "/Singleton.php"));

/* onde fica o usuario e senha agora, nivel acima do public_html */
if (file_exists(realpath(dirname(__FILE__) . "/../dbname.php")))
	require_once(realpath(dirname(__FILE__) . "/../dbname.php")); 

final class DBControl extends Singleton
{
	private $link;
	private $connection_count = 0;
	private $dbname = "customerorders";
	//private $username = "nights10_user";
	//private $password = "u57l;WWIXbQ.";

	private $username = "root";
	private $password = "jegues";
	private $hostname = "localhost";

	protected function __construct() {
		global $_DB_host, $_DB_name, $_DB_user, $_DB_password;

		if ($_DB_host)
			$this->hostname = $_DB_host;
		if ($_DB_name)
			$this->dbname = $_DB_name;
		if ($_DB_user)
			$this->username = $_DB_user;
		if ($_DB_password)
			$this->password = $_DB_password;

		$this->link = NUll;
	}

		public function __destruct() {
				$this->disconnect();
		}

		public function connect() {
				if ($this->link == NULL)
						$this->link = mysql_connect($this->hostname, $this->username, $this->password);

				if (!$this->link)
						throw new Exception("Unable to connect to the database: " . mysql_error());

				if (!mysql_select_db($this->dbname))
						throw new Exception("Unable to select database" . mysql_error());

				/*** Charset definitions must also be set on SQL tables ***/
				mysql_query("SET NAMES 'utf8'");
				mysql_query('SET character_set_connection=utf8');
				mysql_query('SET character_set_client=utf8');
				mysql_query('SET character_set_results=utf8');

				$this->connection_count++;

		}

		public function disconnect() {
				if ($this->link != NULL) {
						$this->connection_count--;
						if ($this->connection_count == 0) {
								mysql_close($this->link);
								$this->link = NULL;
						}
				}
		}

		public function parse($value) {
				$this->connect();

				// Stripslashes
				if (get_magic_quotes_gpc()) {
						$value=stripslashes($value);
				}
				// Quote if not integer
				if (!is_numeric($value)) {
					if ($value === NULL || $value == "")
						$value = "NULL";
					else
						$value = "'" . mysql_real_escape_string($value) . "'";
				}

				$this->disconnect();

				return $value;
		}
}

?>
