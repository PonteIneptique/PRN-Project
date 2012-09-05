<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	//On prépare déjà
	$results = array('done' => 0);
		
	if(!is_string($connectBDD) && (socialBolean()) && ($_GET['target'] != $_SESSION['uid']))
	{

		$query = $connectBDD->prepare("SELECT su.id FROM social_users su WHERE ( su.user= ? AND su.status= ? ) AND (user NOT IN (SELECT target FROM social_demande sd WHERE sd.user= ? )) AND (user NOT IN (SELECT user FROM social_demande sd WHERE sd.target= ? )) AND (user NOT IN (SELECT user FROM social_link sl WHERE sl.friend= ? ))  LIMIT 1");
		$query->execute(array($_GET['target'], 1, $_SESSION['uid'], $_SESSION['uid'], $_SESSION['uid']));
		if($query->rowCount() == 1)
		{
			$reg = $connectBDD->prepare("INSERT INTO social_demande (id, user, target, msg) VALUES ('', ? , ? , '')");
			$reg->execute(array($_SESSION['uid'], $_GET['target']));
			$results['done'] = 1;
		}
		
	}
	else
	{
			$results['done'] = 0;
	}
	echo json_encode($results);//format the array into json data
?>