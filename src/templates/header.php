<?php
require_once(INCLUDES_PATH . "/Client.php");

$clientid = isset($_GET["clientid"]) ? $_GET["clientid"] : NULL;
$client = ($clientid != NULL) ? new Client($clientid) : NULL;
$clientname = ($client != NULL) ? $client->getName() : NULL;
$dlvdate = isset($_GET["dlvdate"]) ? $_GET["dlvdate"] : NULL;
$reqdate = isset($_GET["reqdate"]) ? $_GET["reqdate"] : NULL;
$dlvdatetime = DateTime::createFromFormat("d/m/Y", $dlvdate);
$reqdatetime = DateTime::createFromFormat("d/m/Y", $reqdate);
$dlvstr = ($dlvdatetime != NULL) ? $dlvdatetime->format("Y-m-d") : NULL;
$reqstr = ($reqdatetime != NULL) ? $reqdatetime->format("Y-m-d") : NULL;
$late = isset($_GET["late"]) ? true : false;
$delivered = isset($_GET["delivered"]) ? true : false;
$ready = isset($_GET["ready"]) ? true : false;



?>

<div id="header-top">
	<div class="header-item"><em style="font-size: 13pt"><strong>Josi Costuras</strong></em></div>
	<div class="header-item"><a id="hide-order" href="#">+ pedido</a></div>
	<div class="header-item"><a href="?pid=orders">Todos os Pedidos</a></div>
</div>

<div id="header-bottom">
	<form id="client-search-form" name="client-search-form" action="index.php">
	<input type="hidden" name="pid" value="orders" />
	<input type="hidden" id="clientid-hdrsearch" name="clientid" value="<?php echo $clientid; ?>" />
	<div class="header-item">
		Cliente: <input id="client-name-hdrsearch" type="text" value="<?php echo $clientname; ?>"/>
	</div>
	<div class="header-item">
		Recebimento: <input id="reqdate-hdrsearch" name="reqdate" value="<?php echo $reqdate; ?>" class="date" type="text" size="8" />
	</div>
	<div class="header-item">
		Entrega em: <input id="dlvdate-hdrsearch" name="dlvdate" value="<?php echo $dlvdate; ?>" class="date" type="text" size="8" />
	</div>
	<div class="header-item">
		<table>
		<tr>
		<td><input type="checkbox" name="late" <?php if ($late) echo "checked='on'"; ?> /></td><td> Atrasados</td>
		<td><input type="checkbox" name="ready" <?php if ($ready) echo "checked='on'"; ?> /></td><td> Prontos</td>
		<td><input type="checkbox" name="delivered" <?php if ($delivered) echo "checked='on'"; ?> /></td><td> Entregues</td>
		</tr>
		</table>
	</div>
	<div class="header-item">
		<input type="submit" value="Listar" />
	</div>
	<div style="clear:both;"></div>
	</form>
</div>
