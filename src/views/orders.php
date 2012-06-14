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
		</div>
		<div class="client-item">
			<input type="submit" value="Editar Cliente" />
		</div>

		<div style="clear:both"></div>
		</form>
	</div>

<?php
}
/* End Client info */

$dlvdate = isset($_GET["dlvdate"]) ? $_GET["dlvdate"] : NULL;
$reqdate = isset($_GET["reqdate"]) ? $_GET["reqdate"] : NULL;
$dlvdatetime = DateTime::createFromFormat("d/m/Y", $dlvdate);
$reqdatetime = DateTime::createFromFormat("d/m/Y", $reqdate);
$dlvstr = ($dlvdatetime != NULL) ? $dlvdatetime->format("Y-m-d") : NULL;
$reqstr = ($reqdatetime != NULL) ? $reqdatetime->format("Y-m-d") : NULL;
$orders = OrderManager::orders($clientid, $dlvstr, $reqstr);

if (0) {

?>
	<div id="order-list-header" class="order-list-item" >
		<div class="order-item" style="width: 20%">Cliente</div>
		<div class="order-item" style="width: 9%">Data do Pedido</div>
		<div class="order-item" style="width: 9%">Data da Entrega</div>
		<div class="order-item" style="width: 7%">Valor</div>
		<div class="order-item" style="width: 7%">Custo</div>
		<div class="order-item" style="width: 20%">Descrição</div>
		<div class="order-item" style="width: 10%">Responsável</div>
		<div style="clear:both"></div>
	</div>
<?php

/* Orders in a div*/
foreach ($orders as $order)
{
	$reqDate = new DateTime($order->getRequestDate());
	$dlvDate = new DateTime($order->getDeliveryDate());
?>
	<div id=<?php echo "\"order" . $order->getId() . "\" class=\"order-list-item\""; ?> >
		<div class="order-item" style="width: 20%">
			<a href="index.php?pid=orders&clientid=<?php echo $order->getClient()->getId() ?>">
			<?php echo $order->getClient()->getName(); ?>
			</a>
		</div>
		<div class="order-item" style="width: 9%"><?php echo $reqDate->format("d/m/Y"); ?></div>
		<div class="order-item" style="width: 9%"><?php echo $dlvDate->format("d/m/Y"); ?></div>
		<div class="order-item" style="width: 7%"><?php echo "R$ " . $order->getValue(); ?></div>
		<div class="order-item" style="width: 7%"><?php echo "R$ " . $order->getCost(); ?></div>
		<div class="order-item" style="width: 20%"><?php echo $order->getDescription(); ?></div>
		<div class="order-item" style="width: 10%"><?php echo $order->getOwner(); ?></div>
		<div style="clear:both"></div>
	</div>
<?php
}

} //if 0

if (count($orders) > 0)
{
?>

	<div id="order-list">
	<table id="order-list-table">
	<tr>
		<th>!</th>
		<th width="25%">Cliente</th>
		<th>Recebimento</th>
		<th>Entrega</th>
		<th>Valor</th>
		<th>Custo</th>
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
	<tr>
		<td class="center delivered"><?php echo $order->getDelivered(); ?></td>
		<td width="25%">
			<a href="index.php?pid=orders&clientid=<?php echo $order->getClient()->getId() ?>">
			<?php echo $order->getClient()->getName(); ?>
			</a>
		</td>
		<td class="center"><?php echo $reqDate->format("d/m/Y"); ?></td>
		<td class="center"><?php echo $dlvDate->format("d/m/Y"); ?></td>
		<td><?php echo "R$ " . $order->getValue(); ?></td>
		<td><?php echo "R$ " . $order->getCost(); ?></td>
		<td width="25%"><?php echo $order->getDescription(); ?></td>
		<td class="center"><?php echo $order->getOwner(); ?></td>
	</tr>

<?php
}

}
?>

	</table>
	</div>
