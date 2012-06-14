<?php

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(INCLUDES_PATH . "/Client.php");
require_once(INCLUDES_PATH . "/ClientManager.php");

?>

<div id="clients">
<?php

$clients = ClientManager::clients();
foreach ($clients as $client)
{
?>
	<div id=<?php echo "\"client" . $client->getId() . "\" class=\"client-list-item\""; ?> >
		<div class="client-item"><?php echo $client->getName(); ?></div>
		<div class="client-item"><a href="mailto:<?php echo $client->getEmail(); ?>"><?php echo $client->getEmail(); ?></a></div>
		<div class="client-item"><?php echo $client->getPhone(); ?></div>
		<div style="clear:both"></div>
	</div>
<?php
}

?>
</div>
