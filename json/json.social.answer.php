<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	$results = array('done' => 0);
	if(!is_string($connectBDD) && (socialBolean()) && ($_GET['target'] != $_SESSION['uid']))
	{
		//On prépare déjà
		$results = array('done' => 0);

		$query = $connectBDD->prepare("SELECT target FROM social_demande sd WHERE sd.target = ? AND sd.user = ? LIMIT 1");
		$query->execute(array($_SESSION['uid'], $_GET['target']));
		if(($query->rowCount() == 1) && ($_GET['type'] == 1))//Si on valide
		{
			$reg = $connectBDD->prepare("INSERT INTO social_link (id, user, friend) VALUES ('', ? , ? )");
			$reg->execute(array($_GET['target'], $_SESSION['uid']));
			$reg->execute(array($_SESSION['uid'], $_GET['target']));
			$del = $connectBDD->prepare("DELETE FROM social_demande WHERE target= ? AND user= ? LIMIT 1");
			$del->execute(array($_SESSION['uid'], $_GET['target']));
			
			//On supprime le cache "Amis"
			//delcache("json.social.people", "friends", $_SESSION['uid']);
			$results['done'] = 1;
		}
		else
		{
			$del = $connectBDD->prepare("DELETE FROM social_demande WHERE target= ? AND user= ? LIMIT 1");
			$del->execute(array($_SESSION['uid'], $_GET['target']));
			$results['done'] = 2;
		}
		
	}
	else
	{
			$results['done'] = 0;
	}
	echo json_encode($results);//format the array into json data
?>