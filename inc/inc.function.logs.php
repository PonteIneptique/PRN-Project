<?php
	if(!is_string($connectBDD))
	{
		$storeLogs = $connectBDD->prepare("INSERT INTO logs (`id`,`table`,`item`,`user`)  VALUES (NULL, ? , ? , ? )");
	}
	#$storeLogs->execute(array($table, $item_id, $_SESSION['uid']));
?>