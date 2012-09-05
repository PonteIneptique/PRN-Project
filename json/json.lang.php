<?php
	define("RELurl", '../');
	
	//Le cache
	require_once(RELurl.'/inc/inc.function.cache.future.php');
	$cache = new autocache("json", "lang", 0);
	
	require_once(RELurl.'inc/inc.conn.php');
	require_once(RELurl.'inc/inc.func.lang.php');
		

	
	$src = langList();
	
	if(!is_string($connectBDD))
	{		
		//Normalement on reçoit $_POST qu'on transforme en data		
		$results = array();
		$row_array = array();
		
		$m_q=$connectBDD->prepare("SELECT DISTINCT lage FROM author ORDER BY lage");
		$m_q->execute();
		if($m_q) // Si true, trop tard ! On récupère donc l'id
		{
			if($m_q)
			{
				$temp = array();
				while($man = $m_q->fetch(PDO::FETCH_OBJ))
				{
					$temp[$src[$man->lage]] = $man->lage;
				}
				ksort($temp);//On trie
				foreach($temp as $key => $value)
				{
					$row_array['Value'] = $value;  
					$row_array['Text'] = $key;  
					array_push($results, $row_array); 
				}
			}
		}
		
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>