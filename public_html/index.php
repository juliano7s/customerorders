<?php

require_once(realpath(dirname(__FILE__) . "/../src/config.php"));
require_once(INCLUDES_PATH . "/SessionControl.php");

$Session = SessionControl::getInstance();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Customer Order System</title>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" />

<link rel="shortcut icon" href="img/favicon.png">

<script type="text/javascript" src="js/lib/jquery.js"></script>
<script type="text/javascript" src="js/lib/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="js/lib/jquery.maskedinput-1.3.min.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">

function cleanClientForm()
{
	$("#client-id").val("");
	$("#client-name").val("");
	$("#client-email").val("");
	$("#client-email").attr("disabled", false);
	$("#client-phone").val("");
	$("#client-phone").attr("disabled", false);
}

function cleanOrderForm()
{
	$("#order-delivery-date").val("");
	$("#order-request-date").val("");
	$("#order-value").val("");
	$("#order-cost").val("");
	$("#order-owner").val("");
	$("#order-description").val("");
}

//$(document).ready(function() {
jQuery(function($) {
	//mask date input
	$(".date").mask("99/99/9999");
	//$(".currency").mask("");
	cleanClientForm();
	//cleanOrderForm();

	/* http://net.tutsplus.com/tutorials/javascript-ajax/how-to-use-the-jquery-ui-autocomplete-widget/ */
	$("#client-name").autocomplete({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "control/get_clients.php",
				data: { clientname: request.term },
				dataType: "json",
				success:
					function(data)
					{
						console.debug(data);
						objArray = [];
						for (_id in data.clients)
						{
							_object = {};
							_client = data.clients[_id];
							_object.label = _client.name;
							_object.id = _client.id;
							objArray.push(_object);
						}
						console.debug(objArray);

						response(objArray);
					},
				});
			},

		select: function(event, ui) {
				getClientInfo(ui.item.id);
			},
		});

	$("#client-name").keyup(function()
		{
			if ($(this).val() == "")
				cleanClientForm();
		});

	$("#client-name-hdrsearch").autocomplete({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "control/get_clients.php",
				data: { clientname: request.term },
				dataType: "json",
				success:
					function(data)
					{
						console.debug(data);
						objArray = [];
						for (_id in data.clients)
						{
							_object = {};
							_client = data.clients[_id];
							_object.label = _client.name;
							_object.id = _client.id;
							objArray.push(_object);
						}
						console.debug(objArray);

						response(objArray);
					},
				});
			},

		select: function(event, ui) {
				$("#clientid-hdrsearch").val(ui.item.id);
			},
		});
	$("#clean-order").click(function()
		{
			cleanClientForm();
			cleanOrderForm();
		});

	$("#add-order-form").submit(function()
		{
			/* confirm if client is to be added */
		});

	//$("#add-order").hide();
	$("#hide-order").click(function() {
		if ($("#add-order").is(":visible"))
		{
			$("#add-order").slideUp("fast");
		} else
		{
			$("#add-order").slideDown("fast");
		}
	});

	$(".deliveredajax").click(function()
		{
			_orderid = $(this).parent().parent().attr("id");
			if ($("#" + _orderid).hasClass("orderdelivered"))
			{
				if (!confirm("Esse pedido já foi entregue. Definir como não-entregue?"))
					return false;
				$(this).attr("title", "Marcar pedido como entregue");
			} else
			{
				if (!confirm("Marcar pedido como entregue?"))
					return false;
				$(this).attr("title", "Marcar pedido como não-entregue");
			}

			$.ajax({
				type: "POST",
				url: "control/edit_order_ajax.php",
				data: { orderid: _orderid, setorderdelivered: "1" },
				dataType: "json",
				success:
					function(orderdata)
					{
						if (orderdata.ready == "1")
						{
							$("#order" + orderdata.id).addClass("orderready");
						} else
						{
							$("#order" + orderdata.id).removeClass("orderready");
						}

						if (orderdata.delivered == "1")
						{
							$("#order" + orderdata.id).addClass("orderdelivered");
						} else
						{
							$("#order" + orderdata.id).removeClass("orderdelivered");
						}
					}
			});
	});

	$(".readyajax").click(function()
		{
			_orderid = $(this).parent().parent().attr("id");
			if ($("#" + _orderid).hasClass("orderready"))
			{
				if (!confirm("Esse pedido está pronto. Definir como não-pronto?"))
					return false;
				$(this).attr("title", "Marcar pedido como pronto");
			} else
			{
				if (!confirm("Marcar pedido como pronto?"))
					return false;
				$(this).attr("title", "Marcar pedido como não-pronto");
			}

			$.ajax({
				type: "POST",
				url: "control/edit_order_ajax.php",
				data: { orderid: _orderid, setorderready: "1" },
				dataType: "json",
				success:
					function(orderdata)
					{
						if (orderdata.ready == "1")
						{
							$("#order" + orderdata.id).addClass("orderready");
						} else
						{
							$("#order" + orderdata.id).removeClass("orderready");
						}

						if (orderdata.delivered == "1")
						{
							$("#order" + orderdata.id).addClass("orderdelivered");
						} else
						{
							$("#order" + orderdata.id).removeClass("orderdelivered");
						}
					}
			});
	});

	$(".deleteorder").click(function()
		{
			if (!confirm("Deseja deletar o pedido?"))
				return false;
			window.location.href = $(this).parent().attr("href");

	});

	$(".deleteclient").click(function()
		{
			if (!confirm("Deseja deletar o cliente?"))
				return false;
			if (!confirm("Todas os pedidos do cliente serão também excluídos. Deletar mesmo assim?"))
				return false;
			window.location.href = $(this).parent().attr("href");

	});
});


/* click event callback for client-suggest div class */
function getClientInfo(_clientId)
{
//	_clientId = $(this).attr("id");
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
				$("#client-email").attr("disabled", "true");
				$("#client-phone").val(_client.phone);
				$("#client-phone").attr("disabled", "true");
			},
		dataType: "json"
	});
}
</script>


</head>

<body>

<?php include(TEMPLATES_PATH . "/header.php");?>

<div id="container">

	<div id="messages">
		<div id="error-message">
			<?php echo $Session->getErrorMessage(); ?>
		</div>

		<div id="info-message">
			<?php echo $Session->getMessage(); ?>
		</div>
	</div>

	<div id="add-order">
		<form id="add-order-form" name="add-order" method="post" action="control/add_order.php" >

		<div id="add-order-client">
		<fieldset class="add-order-fieldset">
		<legend>Cliente</legend>
		<table class="add-order-table">
		<input type="hidden" value="0" id="client-id" name="clientid" />
		<tr><td>Nome: </td><td><input type="text" id="client-name" name="clientname" size="40" autocomplete="off"/></td></tr>
		<tr><td>Email: </td><td><input type="text" id="client-email" name="clientemail" size="40"/></td></tr>
		<tr><td>Telefone: </td><td><input type="text" id="client-phone" name="clientphone" size="40"/></td></tr>
		<!-- <tr><td colspan="2"><div id="client-list"></div></td></tr> --!>
		</table>
		<div id="client-list"></div>
		</fieldset>
		</div>

		<div id="add-order-order">
		<fieldset>
		<legend>Pedido</legend>
		<table cellspacing="5px" class="add-order-table">
		<tr>
			<?php $today = new DateTime(); ?>
			<td>Recebimento: </td><td><input class="date" type="text" name="orderrequestdate" id="order-request-date" value="<?php echo $today->format("d/m/Y"); ?>" /></td>
			<td>Entregar em: </td><td><input class="date" type="text" name="orderdeliverydate" id="order-delivery-date" /></td>
		</tr>
		<tr>
			<td>Valor: </td><td><input type="text" name="ordervalue" id="order-value" /></td>
			<td>Sinal: </td><td><input type="text" name="ordercost" id="order-cost" /></td>
		</tr>
		<tr><td>Responsável: </td><td colspan="3"><input type="text" name="orderowner" id="order-owner" size="40"/></td></tr>
		<tr><td>Descrição: </td><td colspan="3"><textarea name="orderdescription" id="order-description" rows="10" cols="55"></textarea></td></tr>
		</table>
		</fieldset>
		</div>

		<input id="submit-order" type="submit" value="Enviar" />
		<input id="clean-order" type="button" value="Limpar" />

		</form>
		<div style="clear:both"></div>
	</div>

    <?php

	// Get pageid
	$pid = isset($_GET["pid"]) ? $_GET["pid"] : "orders";

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

<?php
	$Session->clearMessages();
?>

</body>
</html>

