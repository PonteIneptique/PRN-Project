<?php 
	if($profileaccess == true)
	{
	echo '<h1>'.$us->name.'</h1>';
?>
	
	<table class="table"><tbody>
<?php
	function print_logs($data)
	{
		global $connectBDD;
		//Les mots équivalents 
		$words = array("un commentaire", "une note","le document", "la traduction", "le document privé", "la traduction de document privé", "l'auteur", "le livre");
		//Les tableaux des données
		//array(PREMIERMOT, DEUXIEMEMOT, REQUETE_SQL_DOCUMENT, ARRAY_SQL)
		$table = array(
			"text_note" => array(1, 2, "text"),
			"text_part" => NULL,
			"trad_comment" => array(0, 3, "trad"),
			"social_doc_trad_comment" => array(0, 5, "trad"),
			"social_doc_note" => array(0, 4, "text"),
			"trad_part" => NULL,
			"social_doc_part" => NULL,
			"author" => array(6, "name"),
			"book" => array(7, "name")
		);
		$result = $table[$data->table];
		switch (count($result)) {
				case 3:
					$sql = $connectBDD->prepare("SELECT ".$result[2]." as itemid FROM ".$data->table." WHERE id=? LIMIT 1");
					$sql->execute(array($data->item));
					$item = $sql->fetch(PDO::FETCH_OBJ);
					return 'Vous avez ajouté '.$words[$result[0]].' pour '.$words[$result[1]].' '.$item->itemid.'<br />';
					break;
				case 2:
					$sql = $connectBDD->prepare("SELECT ".$result[1]." as itemname FROM ".$data->table." WHERE id=? LIMIT 1");
					$sql->execute(array($data->item));
					$item = $sql->fetch(PDO::FETCH_OBJ);
					return 'Vous avez ajouté '.$words[$result[0]].' '.$item->itemname.'<br />';
					break;
				default:
					return NULL;
					break;
		}
	}
	$sql = $connectBDD->prepare("SELECT logs.table, logs.item, logs.user FROM logs WHERE user=? ORDER BY id DESC LIMIT 100");
	$sql->execute(array($usid));
	while($data = $sql->fetch(PDO::FETCH_OBJ))
	{
		$return = print_logs($data);
		if($return != NULL)
		{
			echo '<tr><td>'.print_logs($data).'</td></tr>';
		}
	}
?>
</tbody>
</table>
<?php
}
else
{
error(0);
}
?>