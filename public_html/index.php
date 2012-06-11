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

<script type="text/javascript">

$(document).ready(function() {
	$("#hide-order").click(function() {
		if ($("#add-order").is(":visible"))
		{
			$("#add-order").slideUp("fast");
		} else
		{
			$("#add-order").slideDown("fast");
		}
	});

    // When the user finishes typing 
    // a character in the text box...
    $('#client-name').keyup(function() {
        
        // Call the function to handle the AJAX.
        // Pass the value of the text box to the function.
        getClients($(this).val());   
    }); 

});

// Function to handle ajax.
function getClients(str) {
    
	$('#client-list').html("Carregando...");

    // post(file, data, callback, type); (only "file" is required)
    $.post(
        
			"control/get_clients.php", //Ajax file
    
			{ clientname: str },  // create an object will all values
    
			//function that is called when server returns a value.
			function(data){
			$('#client-list').html(data.returnValue);
			}, 

			//How you want the data formated when it is returned from the server.
			"json"
  );
}
</script>


</head>

<body>

<?php include(TEMPLATES_PATH . "/header.php");?>


<div id="container">
	<div id="menu">
		<div class="menu-item"><a id="hide-order" href="#">+ pedido</a></div>
	</div>
	<div style="clear:both;"></div>

	<div id="add-order">
		<form name="add-order" method="post" action="control/add_order.php" >

		<div id="add-order-client">
		<fieldset>
		<legend>Cliente</legend>
		<table>
		<input type="hidden" value="0" name="client-id" />
		<tr><td>Nome: </td><td><input type="text" id="client-name" name="client-name" size="40"/></td></tr>
		<tr><td>Email: </td><td><input type="text" name="client-email" size="40"/></td></tr>
		<tr><td>Telefone: </td><td><input type="text" name="client-phone" size="40"/></td></tr>
		<tr><td colspan="2"><div id="client-list"></div></td></tr>
		</table>
		</fieldset>
		</div>

		<div id="add-order-order">
		<fieldset>
		<legend>Pedido</legend>
		<table>
		<tr>
			<td>Recebimento: </td><td><input type="text" name="order-request-date" /></td>
			<td>Entrega: </td><td><input type="text" name="order-delivery-date" /></td>
		</tr>
		<tr>
			<td>Valor: </td><td><input type="text" name="order-value" /></td>
			<td>Custo: </td><td><input type="text" name="order-cost" /></td>
		</tr>
		<tr><td>Responsável: </td><td colspan="3"><input type="text" name="order-owner" size="40"/></td></tr>
		<tr><td>Descrição: </td><td colspan="3"><textarea name="order-description" rows="10" cols="40"></textarea></td></tr>
		</table>
		</fieldset>
		</div>

		<input type="submit" value="Enviar" />

		</form>
	</div>
	<div style="clear:both"></div>

    <?php

	// Get pageid
	$pid = isset($_GET["pid"]) ? $_GET["pid"] : 0;

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

