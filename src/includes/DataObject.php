<?php


abstract class DataObject {

	protected $id = 0;
	protected $_stub = true;

	protected $_fieldAttrMapping;
	protected $_objectMapping;

	/*******************************************************************************/

	function __toString()
	{
		return "" . get_called_class() . "(" . $this->id . ")";
	}

	/*******************************************************************************/

	function __call($method, $args) {
		$prefix = substr($method, 0, 3);
		$field = strtolower(substr($method, 3, 1)) . substr($method, 4);

		if (method_exists($this, $method))
			return call_user_func_array(array($this, $method), $args);

		if ($prefix != "get" && $prefix != "set")
			throw new Exception(sprintf("Method %s of class %s does not exist", $field, get_class($this)));

		$attributeFound = false;
		foreach (get_class_vars(get_class($this)) as $classAttr => $attrValue) {
			if (($field == $classAttr) || ($field == substr($classAttr,1))) {
				$attributeFound = true;
				break;
			}
		}

		if (!$attributeFound)
			throw new Exception(sprintf("Unknown attribute %s for class %s", $field, get_class($this)));

		if (substr($field, 0, 1) == "_")
			$field = substr($field, 1);

		switch ($prefix) {
			case "get":
				$this->stub();
			return $this->{$field};
			break;

			case "set":
				$this->{$field} = $args[0];
			break;

			default: throw new Exception("Unknown function prefix: " . $prefix . " - should be 'get' or 'set'");
		}

		/* needed for making sure it didn't fail */
		return true;
	}

	/* Checks if this is a _stub object (id only) or a full loaded object */
	protected function stub() {
		if ($this->_stub)
			$this->load();
	}


	protected function getId() {
		return $this->id;
	}

	/* Load the object's data from the database */
	protected function load() {
		$DBCtrl = DBControl::getInstance();

		$DBCtrl->connect();

		if ($this->id != 0)
			$result = mysql_query(sprintf($this::LOAD_BY_ID_QUERY, $DBCtrl->parse($this->id)));
		else
			throw new Exception("Can't load " . get_class($this) . " without id");

		if (!$result)
			throw new Exception("Error on query: " . mysql_error());

		if (mysql_num_rows($result) > 1)
			throw new Exception("Weirdly query resulted in more than 1 row for the id " . $this->id);

		$assoc_array = mysql_fetch_assoc($result);
		if (is_array($assoc_array))
			foreach($assoc_array as $column => $cell) {
					$attribute = $column;
					$value = $cell;

					if (@isset($this->_fieldAttrMapping[$column]))
						$attribute = $this->_fieldAttrMapping[$column];

					if (@isset($this->_objectMapping[$attribute])) {
						$value = new $this->_objectMapping[$attribute]($value);
						$attribute = strtolower(substr($this->_objectMapping[$attribute], 0, 1)).
							substr($this->_objectMapping[$attribute],1);
					}

					$this->{$attribute} = $value;
					//	See [Ticket 29] --> call_user_func_array(array($this, "set".$attribute), array($value));
			}

		mysql_free_result($result);

		$DBCtrl->disconnect();

		/* Not a stub object anymore */
		$this->_stub = false;

		return true;
	}

	/* Insert or update an object to/from the database */
	protected function save() {
		$this->validate();

		$DBCtrl = DBControl::getInstance();
		$DBCtrl->connect();

		$fieldvalues = array();

		if ($this->id > 0) {
			if ($this->_stub)
				throw Exception("You are trying to UPDATE a stub. load() the object before you save() it, you tool!");
			$query = sprintf("UPDATE %s SET ", $this::DB_TABLE);
		} else
			$query = sprintf("INSERT INTO %s SET ", $this::DB_TABLE);

		foreach (get_class_vars(get_class($this)) as $attribute => $value) {

			/* On fliping, the last key ocurrence is taken for each value.
			   This shouldn't be a problem on attribute -> field mapping because
			   the values should be unique */
			$attrFieldMapping = array_flip($this->_fieldAttrMapping);
			$objFieldMapping = array_flip($this->_objectMapping);
			$cell = $this->{$attribute};
			$column = $attribute;

			/* Attributes that begin with _ are not to be persisted */
			if (substr($attribute, 0, 1) == "_")
				continue;

			if (@isset($attrFieldMapping[$attribute]))
				$column = $attrFieldMapping[$attribute];

			$attrUpper = strtoupper(substr($column,0,1)) . substr($column,1);

			if (@isset($objFieldMapping[$attrUpper]))
				$column = $objFieldMapping[$attrUpper];

			/* If the attribute is an object, save only the id to the database */
			if ($cell instanceof DataObject)
				$cell = $cell->getId();

			$fieldvalues[] = sprintf("`%s` = %s", $column, $DBCtrl->parse($cell));
		}


		$query .= implode($fieldvalues, ",");

		if ($this->id > 0)
			$query .= sprintf(" WHERE `%s` = %s", $attrFieldMapping["id"], $DBCtrl->parse($this->id));

		$result = mysql_query($query);

		if (!$result)
			throw new Exception("Error inserting/updating object: " . mysql_error());

		if ($this->id <= 0) {
			$this->id = mysql_insert_id();
			$this->_stub = false;
		}

		$DBCtrl->disconnect();

		return true;
	}

	protected function delete()
	{
		$DBCtrl = DBControl::getInstance();
		$DBCtrl->connect();

		if ($this->id != 0)
			mysql_query(sprintf($this::DELETE_BY_ID_QUERY, $DBCtrl->parse($this->id)));

		$DBCtrl->disconnect();
	}

	/* Every object shall be validated before being saved to the database */
	abstract function validate();

	/* Get database table name */
	final function getTableName()
	{
		return $this::DB_TABLE;
	}

	/* Get _fieldAttrMapping value from given key */
	final function getIdFieldName()
	{
		$flip = array_flip($this->_fieldAttrMapping);

		if (@isset($flip["id"]))
			return $flip["id"];

		return null;
	}

	const DB_TABLE = "_undefined_";
	const LOAD_BY_ID_QUERY = "_undefined_";
	const DELETE_BY_ID_QUERY = "_undefined_";
}

?>
