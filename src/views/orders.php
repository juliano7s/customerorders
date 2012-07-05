<?php

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/OrderManager.php");

$clientid = isset($_GET["clientid"]) ? $_GET["clientid"] : NULL;

/* Client info */
$client = ($clientid != NULL) ? new Client($clientid) : NULL;
if ($client != NULL)
{
?>

	<div id="client-info">
		<fieldset>
		<legend>Editar Cliente</legend>
		<form action="control/edit_client.php" method="post">
		<input type="hidden" id="client-info-id" name="clientinfoid" value="<?php echo $client->getId(); ?>" />
		<div class="client-item">
			Cliente: <input type="text" id="client-info-name" name="clientinfoname"  value="<?php echo $client->getName(); ?>" size="30" />
		</div>
		<div class="client-item">
			Email: <input type="text" id="client-info-email" name="clientinfoemail"  value="<?php echo $client->getEmail(); ?>" size="30" />
		</div>
		<div class="client-item">
			Telefones: <input type="text" id="client-info-phone" name="clientinfophone"  value="<?php echo $client->getPhone(); ?>" size="30" />
			<input type="submit" value="Salvar" />
		</div>

		<a href="control/delete_client.php?clientid=<?php echo $client->getId(); ?>"><font class="deleteclient">Deletar cliente</font></a>

		<div style="clear:both"></div>
		</form>
		</fieldset>
	</div>

<?php
} /* End Client info */

$orderid = isset($_GET["orderid"]) ? $_GET["orderid"] : NULL;

/* Order info */
$order = ($orderid != NULL) ? new Order($orderid) : NULL;
if ($order != NULL)
{
	$dlvdate = new DateTime($order->getDeliveryDate());
	$reqdate = new DateTime($order->getRequestDate());

?>

	<div id="order-info">
		<fieldset>
		<legend>Editar Pedido</legend>
		<form action="control/edit_order.php" method="post">
		<input type="hidden" id="order-info-id" name="orderid" value="<?php echo $order->getId(); ?>" />
		<table class="edit-order-table">
		<tr>
			<td>Cliente: </td><td><a href="index.php?pid=orders&clientid=<?php echo $order->getClient()->getId() ?>"><?php echo $order->getClient()->getName(); ?></a></td>
			<td>Recebimento: </td><td><input class="date" type="text" id="order-info-reqdate" name="orderreqdate"  value="<?php if ($order->getRequestDate() != NULL) echo $reqdate->format("d/m/Y"); ?>" size="30" /></td>
		</tr>
		<tr>
			<td>Valor: </td><td><input type="text" id="order-info-value" name="ordervalue"  value="<?php echo $order->getValue(); ?>" size="30" /></td>
			<td>Entregar em: </td><td><input class="date" type="text" id="order-info-dlvdate" name="orderdlvdate"  value="<?php if ($order->getDeliveryDate() != NULL) echo $dlvdate->format("d/m/Y"); ?>" size="30" /></td>
		</tr>
		<tr><td>Sinal: </td><td><input type="text" id="order-info-cost" name="ordercost"  value="<?php echo $order->getCost(); ?>" size="30" /></td></tr>
		<tr><td>Responsável: </td><td><input type="text" id="order-info-owner" name="orderowner"  value="<?php echo $order->getOwner(); ?>" size="30" /></td></tr>
		<tr><td>Descrição: </td><td colspan="3"><textarea rows="10" cols="55" id="order-info-description" name="orderdescription"><?php echo $order->getDescription(); ?></textarea></td></tr>
		</table>
		<input type="submit" value="Salvar" />
		</form>
		</fieldset>
	</div>

<?php
} /* End Order info */

$dlvdate = isset($_GET["dlvdate"]) ? $_GET["dlvdate"] : NULL;
$reqdate = isset($_GET["reqdate"]) ? $_GET["reqdate"] : NULL;
$dlvdatetime = DateTime::createFromFormat("d/m/Y", $dlvdate);
$reqdatetime = DateTime::createFromFormat("d/m/Y", $reqdate);
$dlvstr = ($dlvdatetime != NULL) ? $dlvdatetime->format("Y-m-d") : NULL;
$reqstr = ($reqdatetime != NULL) ? $reqdatetime->format("Y-m-d") : NULL;
$late = isset($_GET["late"]) ? true : false;
$delivered = isset($_GET["delivered"]) ? true : false;
$ready = isset($_GET["ready"]) ? true : false;

$orders = OrderManager::orders($clientid, $dlvstr, $reqstr, $late, $delivered, $ready);

if (count($orders) > 0)
{
?>

	<div id="order-list">
	<table id="order-list-table">
	<tr>
		<th>Cliente</th>
		<th>Recebimento</th>
		<th>Entregar em</th>
		<th>Valor</th>
		<th>Sinal</th>
		<th>Descrição</th>
		<th>Responsável</th>
		<th>Editar</th>
	</tr>

	<div id="orders-summary">
	<h4>Resumo:</h4>
<?php
	$valuesum = 0;
	foreach ($orders as $order)
	{
		$valuesum += $order->getValue();
	}
	echo "<div>" . count($orders) . " ordens</div>";
	echo "<div><b>Valor total:</b> R$ " . number_format($valuesum,2,'.','') . "</div>";
?>
	</div>
	<br />

<?php

	/* Orders in a table */
	foreach ($orders as $order)
	{
		$reqDate = new DateTime($order->getRequestDate());
		$dlvDate = new DateTime($order->getDeliveryDate());

		$orderClass = "";
		if ($dlvDate != NULL && $dlvDate < (new DateTime()))
			$orderClass = "orderlate";

		//if ($order->getReady() == 1 && $order->getDelivered() == 0)
		if ($order->getReady() == 1)
			$orderClass .= " orderready";

		if ($order->getDelivered() == 1)
			$orderClass .= " orderdelivered";
?>
		<tr id="order<?php echo $order->getId(); ?>" class="order-list-item <?php echo $orderClass;  ?>">
		<td width="15%">
			<a href="index.php?pid=orders&clientid=<?php echo $order->getClient()->getId() ?>">
			<?php echo $order->getClient()->getName(); ?>
			</a>
		</td>
		<td class="center"><?php if ($reqDate != NULL) echo $reqDate->format("d/m/Y"); ?></td>
		<td class="center"><?php if ($dlvDate != NULL) echo $dlvDate->format("d/m/Y"); ?></td>
		<td><?php if ($order->getValue() != NULL && trim($order->getValue() != "")) echo "R$ " . str_replace('.',',',$order->getValue()); ?></td>
		<td><?php if ($order->getCost() != NULL && trim($order->getCost() != "")) echo "R$ " . str_replace('.',',',$order->getCost()); ?></td>
		<td width="25%"><?php echo $order->getDescription(); ?></td>
		<td class="center"><?php echo $order->getOwner(); ?></td>
		<td width="10%" class="orderoptions">
			<img class="deliveredajax" title="Marcar pedido como entregue" src="img/Accept16.png" style="float: left; margin: 0 0 0 10px;" />
			<img class="readyajax" src="img/Cut16.png" title="Marcar pedido como pronto" style="float: left"/>
			<a href="index.php?pid=orders&orderid=<?php echo $order->getId(); ?>"><img title="Editar pedido" src="img/Edit16.png"  style="float: left"/></a>
			<a href="control/delete_order.php?orderid=<?php echo $order->getId(); ?>"><img class="deleteorder" src="img/Delete16.png" title="Deletar pedido" style="float: left"/></a>
			<div style = "clear: both"></div>
		</td>
		</tr>

<?php
	}
?>
	</table>
	</div>
<?php
} //end if (count($orders)
?>

