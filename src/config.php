<?php
/* 
    I will usually place the following in a bootstrap file or some type of environment 
    setup file (code that is run at the start of every page request), but they work 
    just as well in your config file if it's in php (some alternatives to php are xml or ini files). 
*/  
  
/* 
    Creating constants for heavily used paths makes things a lot easier. 
    ex. require_once(LIBRARY_PATH . "/Paginator.php") 
*/  
defined("BASE_URL")  
    or define("BASE_URL", "http://localhost/customerorders/");  

defined("LIBRARY_PATH")  
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/lib'));  
  
defined("TEMPLATES_PATH")  
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));  
  
defined("INCLUDES_PATH")  
    or define("INCLUDES_PATH", realpath(dirname(__FILE__) . '/includes'));  

defined("VIEWS_PATH")  
    or define("VIEWS_PATH", realpath(dirname(__FILE__) . '/views'));

defined("WIDGETS_PATH")  
    or define("WIDGETS_PATH", realpath(dirname(__FILE__) . '/widgets'));
/* 
    Error reporting. 
*/  
ini_set("error_reporting", "true");  
error_reporting(E_ALL|E_STRCT);  

/*
 * Our date
 */
date_default_timezone_set('America/Sao_Paulo');


?>
