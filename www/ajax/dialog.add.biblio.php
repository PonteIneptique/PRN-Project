<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	if(!is_string($connectBDD) && sessionBolean())
	{
		$v_q = $connectBDD->prepare("SELECT * FROM bibliography WHERE title= ? AND url= ? LIMIT 1");
		$v_q->execute(array($_POST['title'], $_POST['url']));
		if($v_q)
		{
			if($v_q->rowCount() == 0)
			{
				$insert = $connectBDD->prepare("INSERT INTO bibliography (id, title, url, auteur) VALUES ('', ? , ? , ? )");
				$insert->execute(array($_POST['title'], $_POST['url'], $_POST['auteur']));
				if($insert->rowCount() == 1) { success(0); $storeLogs->execute(array('bibliography', $connectBDD->lastInsertId(), $_SESSION['uid'])); } else { error(0); }
			}
			else
			{
				error(5);
			}
		}
		else
		{
			error(0);
		}
	}
?>