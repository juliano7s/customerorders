<?php



?>

<div id="header-top">
	<div class="header-item">Josi Costuras</div>

	<div class="header-item"><a href="?pid=orders">Todos os Pedidos</a></div>
	<form id="client-search-form" name="client-search-form" action="index.php">
	<input type="hidden" name="pid" value="orders" />
	<input type="hidden" id="clientid-hdrsearch" name="clientid" />
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Listar por Cliente: <input id="client-name-hdrsearch" type="text" />
	</div>
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Listar por Data de Entrega: <input id="dlvdate-hdrsearch" name="dlvdate" class="date" type="text" size="8" />
	</div>
	<div class="header-item" style="margin: 4px 20px 0 0;" >
		Listar por Data do Pedido: <input id="reqdate-hdrsearch" name="reqdate" class="date" type="text" size="8" /> <input type="submit" value="Pesquisar" />
	</div>
	</form>
	<div style="clear:both;"></div>
</div>
