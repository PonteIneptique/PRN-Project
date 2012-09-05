<?php
	define("RELurl", '../');
if(isset($_GET['term']) && isset($_GET['row']))
{
	require_once(RELurl.'inc/inc.conn.php');
	if(!is_string($connectBDD))
	{
		//Normalement on reçoit $_POST qu'on transforme en data		
		$q = $_GET['term'];
		$row = $_GET['row'];
		$results = array();
		$row_array = array();
		
		$m_q=$connectBDD->prepare("SELECT id, ".$row." FROM bibliography WHERE ".$row." LIKE ? ORDER BY ".$row." ASC");
		$m_q->execute(array('%'.$q.'%'));
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			$m_q->setFetchMode(PDO::FETCH_OBJ);
			while($man = $m_q->fetch())
			{
				$row_array['id'] = $man->id;  
				$row_array['value'] = $man->$row;  
				array_push($results, $row_array);  
			}
		}
		else
		{
			//On propose le nouveau
			$row_array['id'] = 0;  
			$row_array['value'] = 'Aucune donnée pour ce texte';  
			array_push($results, $row_array);
		}
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
}
?>