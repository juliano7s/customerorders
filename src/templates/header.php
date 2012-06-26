<?php



?>

<div id="header-top">
	<div class="header-item"><em style="font-size: 13pt"><strong>Josi Costuras</strong></em></div>

	<div class="header-item"><a href="?pid=orders">Todos os Pedidos</a></div>
	<form id="client-search-form" name="client-search-form" action="index.php">
	<input type="hidden" name="pid" value="orders" />
	<input type="hidden" id="clientid-hdrsearch" name="clientid" />
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Cliente: <input id="client-name-hdrsearch" type="text" />
	</div>
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Data de Entrega: <input id="dlvdate-hdrsearch" name="dlvdate" class="date" type="text" size="8" />
	</div>
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Data do Pedido: <input id="reqdate-hdrsearch" name="reqdate" class="date" type="text" size="8" />
	</div>
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		<input type="submit" value="Listar Pedidos" />
	</div>
	</form>
	<div style="clear:both;"></div>
</div>
