<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(sessionBolean())
	{
	if(!is_string($connectBDD))
	{		
		$v_q = $connectBDD->prepare("SELECT * FROM author WHERE name= ? AND lage= ? LIMIT 1");
		$v_q->execute(array($_POST['name'], $_POST['lage']));
		if($v_q)
		{
			if($v_q->rowCount() == 0)
			{
				$insert = $connectBDD->prepare("INSERT INTO author (id, name, lage) VALUES ('', ? , ? )");
				$insert->execute(array($_POST['name'], $_POST['lage']));
				if($insert->rowCount() == 1) { 
					success(0); 
					$storeLogs->execute(array('author', $connectBDD->lastInsertId(), $_SESSION['uid'])); 
					$cache->delete("json", "author", $_POST['lage']);
					$cache->delete("json", "lang", 0);
					$cache->delete("block", "lang", 0);
				} else { error(0); }
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
	}
	else
	{
		error(3);
	}
?>