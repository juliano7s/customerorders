<?php
/**
 *	Database control functions
 *
 */
require_once(realpath(dirname(__FILE__) . "/../config.php")); 
require_once(realpath(dirname(__FILE__) . "/Singleton.php"));

final class SessionControl extends Singleton
{
	protected function __construct() {
		session_start();
	}

	public function setMessage($msg)
	{
		if (isset($_SESSION[SessionControl::INFO_MSG]))
			$_SESSION[SessionControl::INFO_MSG] .= "<br />" . $msg;
		else
			$_SESSION[SessionControl::INFO_MSG] = $msg;
	}

	public function getMessage()
	{
		if (isset($_SESSION[SessionControl::INFO_MSG]))
			return $_SESSION[SessionControl::INFO_MSG];
		else
			return "";
	}

	public function setErrorMessage($msg)
	{
		if (isset($_SESSION[SessionControl::ERROR_MSG]))
			$_SESSION[SessionControl::ERROR_MSG] .= "<br />" . $msg;
		else
			$_SESSION[SessionControl::ERROR_MSG] = $msg;
	}

	public function getErrorMessage()
	{
		if (isset($_SESSION[SessionControl::ERROR_MSG]))
			return $_SESSION[SessionControl::ERROR_MSG];
		else
			return "";
	}

	public function clearMessages()
	{
		if (isset($_SESSION[SessionControl::ERROR_MSG]))
			unset($_SESSION[SessionControl::ERROR_MSG]);
		if (isset($_SESSION[SessionControl::INFO_MSG]))
			unset($_SESSION[SessionControl::INFO_MSG]);
	}


	const INFO_MSG = "infomsg";
	const ERROR_MSG = "errormsg";
}
