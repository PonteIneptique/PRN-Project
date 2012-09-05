<?php
	define("RELurl", '../');

	require_once(RELurl.'inc/inc.conn.php');
	require_once(RELurl.'inc/inc.func.lang.php');
	

	
	if(!is_string($connectBDD))
	{		
		if(sessionBolean())
		{
			//On set par défaut l'activate à 0
			$results = array('activate' => 0);
			
			//Requete d'enregistrement
			if(socialBolean()) // Déjà activé
			{
				$results['activate'] == 1;
			}
			else
			{
				$query = $connectBDD->prepare("INSERT INTO social_users (id, user, status) VALUES ('', ? , ? )");
				$query->execute(array($_SESSION['uid'], 1));
				$results['activate'] = $query->rowCount(); //Si ça marche pas on a un row count à 0 donc bon :D
			}
			
			//On renvoie le résultat
			if(isset($results)) { echo json_encode($results); }
		}
	}
	else
	{
		exit($connectBDD);
	}
?>