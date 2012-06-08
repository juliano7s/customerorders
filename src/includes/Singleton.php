<?php

abstract class Singleton
{
	protected static $_instances = NULL;

	public static function getInstance()
	{
		if (null === static::$_instances) {
			static::$_instances = array();
		}

		$class_name = get_called_class();
		if (!array_key_exists($class_name, static::$_instances)) {
			static::$_instances[$class_name] = new $class_name;
		}

		return static::$_instances[$class_name];
	}  

	abstract protected function __construct();
}

?>
