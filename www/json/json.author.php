<?php
	define("RELurl", '../');
	
	if(isset($_GET['l'])) { $l = $_GET['l']; } else { $l = 0; }
	
	//Le cache
	require_once(RELurl.'/inc/inc.function.cache.future.php');
	$cache = new autocache("json", "author", $l);
	
	//$cache = new start_cache("json.author", "author", $l);//Pagename, Table, Item
	//Fin du cache
	require_once(RELurl.'inc/inc.conn.php');
	require_once(RELurl.'inc/inc.func.lang.php');
	

	
	if(!is_string($connectBDD))
	{		
		
		//Normalement on reçoit $_POST qu'on transforme en data		
		$results = array();
		$row_array = array();
		
		if(isset($_GET['l'])) //Si on a un auteur
		{
			$m_q=$connectBDD->prepare("SELECT * FROM author WHERE lage= ? ORDER BY name ASC");
			$m_q->execute(array($_GET['l']));
		}
		else
		{
			$m_q=$connectBDD->prepare("SELECT * FROM author ORDER BY name ASC");
			$m_q->execute();
		}
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			$m_q->setFetchMode(PDO::FETCH_OBJ);
			if($m_q)
			{
				while($man = $m_q->fetch())
				{
					$row_array['Value'] = $man->id;  
					$row_array['Text'] = $man->name . ' ('.langReturn($man->lage).')';  
					array_push($results, $row_array);  
				}
			}
		}
		
		//On propose le nouveau
		//if(sessionBolean() == true)
		//{
		$row_array['Value'] = 0;  
		$row_array['Text'] = 'Nouveau';  
		array_push($results, $row_array);
		//}
		
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>