<?php

require_once(realpath(dirname(__FILE__) . "/../src/config.php"));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Customer Order System</title>

<link rel="stylesheet" type="text/css" href="css/main.css" />

<link rel="shortcut icon" href="img/favicon.png">

<script type="text/javascript" src="js/lib/jquery.js"></script>
<script type="text/javascript" src="js/lib/facebox.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>


<?php include(TEMPLATES_PATH . "/header.php");?>


<div id="container">
	<div id="menu">
		<div class="menu-item"><a href="">+ pedido</a></div>
	</div>
	<div style="clear:both;"></div>

	<div id="add-order">

	</div>

    <?php

	// Get pageid
	$pid = $_GET["pid"];

	switch ($pid) {
		case "clients":
			include(VIEWS_PATH . "/clients.php");
			break;

		case "orders":
		default:
			include(VIEWS_PATH . "/orders.php");
			break;
	}

	?>
</div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>


</body>
</html>

