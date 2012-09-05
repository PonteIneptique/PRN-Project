<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	if(!is_string($connectBDD))
	{		
		$v_q = $connectBDD->prepare("SELECT * FROM university WHERE name= ? LIMIT 1");
		$v_q->execute(array($_POST['name']));
		if($v_q)
		{
			if($v_q->rowCount() == 0)
			{
				$insert = $connectBDD->prepare("INSERT INTO university (id, name) VALUES ('', ? )");
				$insert->execute(array($_POST['name']));
				if($insert->rowCount() == 1) { success(0); delcache("json.university", "university", "");} else { error(0); }
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
	else
	{
		error(0);
	}
?>