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
<script type="text/javascript" src="js/lib/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="js/lib/facebox.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">

var clientIdList;

//$(document).ready(function() {
jQuery(function($) {
	//mask date input
	$(".date").mask("99/99/9999");
	//$(".currency").mask("");

	$('#client-list').hide();

//	$("#add-order").hide();
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
		if ($(this).val() != "")
			getClients($(this).val());
		else {
			$('#client-list').html("");
			$('#client-list').hide();
			$("#client-id").val("");
			$("#client-name").val("");
			$("#client-email").val("");
			$("#client-phone").val("");
		}

    });

});

/* key-up event on the client name input */
function getClients(str)
{
//	$('#client-list').html("Carregando...");
    $.ajax({
		type: "POST",
		url: "control/get_clients.php", //ajax file requested
		data: { clientname: str },  // create an object will all values
		success: //callback on success
			function(data)
			{
				$('#client-list').html("");
				for (_id in data.clients)
				{
					$('#client-list').show();
					_client = data.clients[_id];
					/* http://stackoverflow.com/questions/268490/jquery-document-createelement-equivalent/268520#268520
					creating a div element */
					clientDiv = $("<div>");
					clientDiv.attr("id","client" + _client.id);
					clientDiv.text(_client.name);
					clientDiv.addClass("client-suggest");
					$('#client-list').append(clientDiv);
				}

				$(".client-suggest").click(getClientInfo);
				/* $(".client-suggest").click(getClientInfo); to send more parameters to a function, use bind()
					http://stackoverflow.com/questions/1541127/how-to-send-multiple-arguments-to-jquery-click-function */
			},
		dataType: "json" // or "html"
  	});
}

/* click event callback for client-suggest div class */
function getClientInfo()
{
	_clientId = $(this).attr("id");

    $.ajax({
		type: "POST",
		url: "control/get_client_info.php",
		data: { clientid: _clientId },
		success:
			function(data)
			{
				//set client info on inputs
				_client = data.client;
				$("#client-id").val(_client.id);
				$("#client-name").val(_client.name);
				$("#client-email").val(_client.email);
				$("#client-phone").val(_client.phone);
			},
		dataType: "json"
	});
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
		<fieldset class="add-order-fieldset">
		<legend>Cliente</legend>
		<table class="add-order-table">
		<input type="hidden" value="0" id="client-id" name="client-id" />
		<tr><td>Nome: </td><td><input type="text" id="client-name" name="client-name" size="40" autocomplete="off"/></td></tr>
		<tr><td>Email: </td><td><input type="text" id="client-email" name="client-email" size="40"/></td></tr>
		<tr><td>Telefone: </td><td><input type="text" id="client-phone" name="client-phone" size="40"/></td></tr>
		<tr><td colspan="2"><div id="client-list"></div></td></tr>
		</table>
		</fieldset>
		</div>

		<div id="add-order-order">
		<fieldset>
		<legend>Pedido</legend>
		<table cellspacing="5px" class="add-order-table">
		<tr>
			<td>Recebimento: </td><td><input class="date" type="text" name="order-request-date" /></td>
			<td>Entrega: </td><td><input class="date" type="text" name="order-delivery-date" /></td>
		</tr>
		<tr>
			<td>Valor: </td><td><input type="text" name="order-value" /></td>
			<td>Custo: </td><td><input type="text" name="order-cost" /></td>
		</tr>
		<tr><td>Responsável: </td><td colspan="3"><input type="text" name="order-owner" size="40"/></td></tr>
		<tr><td>Descrição: </td><td colspan="3"><textarea name="order-description" rows="10" cols="55"></textarea></td></tr>
		</table>
		</fieldset>
		</div>

		<input id="submit-order" type="submit" value="Enviar" />
		<div style="clear:both"></div>

		</form>
	</div>

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

