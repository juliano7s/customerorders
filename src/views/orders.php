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
$orders = OrderManager::orders($clientid, $dlvstr, $reqstr);

if (count($orders) > 0)
{
?>

	<div id="order-list">
	<table id="order-list-table">
	<tr>
		<th>OK</th>
		<th>E</th>
		<th width="25%">Cliente</th>
		<th>Recebimento</th>
		<th>Entregar em</th>
		<th>Valor</th>
		<th>Sinal</th>
		<th width="25%">Descrição</th>
		<th>Responsável</th>
	</tr>

<?php

	/* Orders in a table */
	foreach ($orders as $order)
	{
		$reqDate = new DateTime($order->getRequestDate());
		$dlvDate = new DateTime($order->getDeliveryDate());
?>
		<tr id="order<?php echo $order->getId(); ?>" class="order-list-item <?php if ($dlvDate < (new DateTime())) echo "orderlate" ?> <?php if ($order->getDelivered() == 1) echo "delivered" ?>">
		<td title="Clique para marcar pedido como entregue" class="center deliveredajax">
		<font style="text-decoration: underline; font-weight: bold; color: blue;">¬</font><?php $order->getDelivered(); ?>
		</td>
		<td class="center" title="Clique aqui para editar o pedido"><a href="?index.php?pid=orders&orderid=<?php echo $order->getId(); ?>">~</a></td>
		<td width="25%">
		<a href="index.php?pid=orders&clientid=<?php echo $order->getClient()->getId() ?>">
		<?php echo $order->getClient()->getName(); ?>
		</a>
		</td>
		<td class="center"><?php if ($order->getRequestDate() != NULL) echo $reqDate->format("d/m/Y"); ?></td>
		<td class="center"><?php if ($order->getDeliveryDate() != NULL) echo $dlvDate->format("d/m/Y"); ?></td>
		<td><?php echo "R$ " . str_replace('.',',',$order->getValue()); ?></td>
		<td><?php echo "R$ " . str_replace('.',',',$order->getCost()); ?></td>
		<td width="25%"><?php echo $order->getDescription(); ?></td>
		<td class="center"><?php echo $order->getOwner(); ?></td>
		</tr>

<?php
	}
?>
	</table>
	</div>
<?php
} //end if (count($orders)
?>

