<?php
	define("RELurl", '../');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "text_";
	if(isset($_GET['sd']))
	{
		require_once(RELurl."/inc/inc.class.document.php");
		$document = new document($_GET['uid'], false);
		if($document->access == false) { exit( '<div class="error">Vous n\'avez pas les droits d\'accès à cette page</div>'); }
		else { $db = "social_doc_"; }
	}
	
	require_once(RELurl.'inc/inc.class.cache.php');
	$cache = new start_cache("json.man", $db."trad_man", $_GET['uid']);//Pagename, Table, Item
	
	require_once(RELurl.'inc/inc.conn.php');
	
	if(!is_string($connectBDD))
	{
		
		//Normalement on reçoit $_POST qu'on transforme en data		
		$uid = $_GET['uid'];
		$results = array();
		$row_array = array();
		
		$m_q=$connectBDD->prepare("SELECT * FROM ".$db."man WHERE text= ?  ORDER BY name ASC");
		$m_q->execute(array($uid));
		if($m_q->rowCount() > 0 ) // Si true, trop tard ! On récupère donc l'id
		{
			while($man = $m_q->fetch(PDO::FETCH_OBJ))
			{
				$row_array['Value'] = $man->id;  
				$row_array['Text'] = $man->name. ' ('.$man->location.')';  
				array_push($results, $row_array);  
			}
		}
		

		//On propose le nouveau
		if(sessionBolean() == true)
		{
			$row_array['Value'] = 0;  
			$row_array['Text'] = 'Nouveau';  
			array_push($results, $row_array);
		}
		
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>