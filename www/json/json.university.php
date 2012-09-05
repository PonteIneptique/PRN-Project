<?php
	define("RELurl", '../');
	
	//Le cache
	// require_once(RELurl.'inc/inc.class.cache.php');	
	// if(isset($_GET['tb'])) { $tb = $_GET['tb']; } else { $tb = ""; }
	// $cache = new start_cache("json.university", "university", "");//Pagename, Table, Item
	//Fin du cache
	
	
	require_once(RELurl.'inc/inc.conn.php');
	
	if(!is_string($connectBDD))
	{
		
		//Normalement on reçoit $_POST qu'on transforme en data		
		$results = array();
		$row_array = array();
		
		//On propose le nouveau
		$row_array['Value'] = 0;  
		$row_array['Text'] = 'Nouveau';  
		array_push($results, $row_array);
		
		//On propose aucune
		$row_array['Value'] = -1;  
		$row_array['Text'] = 'Aucune';  
		array_push($results, $row_array);
		
		$m_q=$connectBDD->prepare("SELECT * FROM university ORDER BY name ASC");
		$m_q->execute();
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			while($man = $m_q->fetch(PDO::FETCH_OBJ))
			{
				$row_array['Value'] = $man->id;  
				$row_array['Text'] = $man->name;  
				array_push($results, $row_array);  
			}
		}
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>