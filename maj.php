<?php
	define("RELurl", './');
	require_once(RELurl.'inc/inc.conn.php');
	
	$bql = $connectBDD->prepare("SELECT * FROM book WHERE id = ? LIMIT 1");
	$aql = $connectBDD->prepare("SELECT * FROM author WHERE id = ?");
	
	$sql = $connectBDD->prepare("SELECT * FROM text_book");
	$maj = $connectBDD->prepare("UPDATE text_book SET author = ? , lage = ? WHERE id = ? LIMIT 1");
	$sql->execute();
	while($tb = $sql->fetch(PDO::FETCH_OBJ))
	{
		$bql->execute(array($tb->book));
		$book = $bql->fetch(PDO::FETCH_OBJ);
		
		$aql->execute(array($book->author));
		$author = $aql->fetch(PDO::FETCH_OBJ);
		
		echo $author->lage.' '.$author->name.'<br />';
		if($aql->rowCount() == 1)
		{
			$maj->execute(array($author->id, $author->lage, $tb->id));
		}
	}
	
?>