<?php
	if(isset($_GET['text']))
	{
		$exec = array($_GET['text']);
		define("RELurl", '../');
		
		
		//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
		//On commence par simplement vérifier si on est en social_doc
		// ".$db."
		$db = "";
		if(isset($_GET['sd']))
		{
			require_once(RELurl."/inc/inc.class.document.php");
			$document = new document($_GET['text'], false);
			if($document->access == false) { exit( '<div class="error">Vous n\'avez pas les droits d\'accès à cette page</div>'); }
			else { $db = "social_doc_"; }
		}
		
		require_once(RELurl.'inc/inc.class.cache.php');
		$cache = new start_cache("json.trad.lang", $db."trad_text", $_GET['text']);//Pagename, Table, Item
		
		require_once(RELurl.'inc/inc.conn.php');
		require_once(RELurl.'inc/inc.func.lang.php');
		
		$src = langList();
		
		if(!is_string($connectBDD))
		{		
			//Normalement on reçoit $_POST qu'on transforme en data		
			$results = array();
			$row_array = array();
			
			$m_q=$connectBDD->prepare("SELECT DISTINCT lage FROM ".$db."trad_text WHERE text=? ORDER BY lage");
			$m_q->execute($exec);
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
			
			$row_array['Value'] = "";  
			$row_array['Text'] = "Toutes les langues";  
			array_push($results, $row_array);
		 
			if(isset($results)) { echo json_encode($results); }
		}
		else
		{
			exit($connectBDD);
		}
	}
?>