<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	
	if(!is_string($connectBDD))
	{
		$results = array();
		$row_array = array();
		
		$m_q=$connectBDD->prepare("SELECT id, title, auteur FROM bibliography ORDER BY title ASC");
		$m_q->execute();
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			$m_q->setFetchMode(PDO::FETCH_OBJ);
			while($man = $m_q->fetch())
			{
				$row_array['Value'] = $man->id;  
				$row_array['Text'] = $man->title. "(".$man->auteur.")";  
				array_push($results, $row_array);  
			}
		}
		

		//On propose le nouveau
		$row_array['Value'] = 0;  
		$row_array['Text'] = 'Nouveau';  
		array_push($results, $row_array);
		
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>