<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "text_";
	if(isset($_GET['sd']))
	{
		$document = new document($_GET['uid'], false);
		if($document->access == false) { exit(error(3)); }
		else { $db = "social_doc_"; }
	}
	elseif(!sessionBolean())
	{
		exit( error(3));
	}
	
//Part
	//On vérifie qu'une part n'existe pas encore
	$result=$connectBDD->prepare("SELECT * FROM ".$db."man WHERE text= ? AND name= ? LIMIT 1");
	$result->execute(array($_GET['uid'], $_POST['name']));
	if($result) // Si true, trop tard ! On récupère donc l'id
	{
		$man = $result->fetch(PDO::FETCH_OBJ);
		if($man)
		{
			error(5);
			$name = $man->name;
		}
		else // Sinon on enregistre la nouvelle et on chope l'id
		{
			//On recupère le numéro du manuscrit
			$result2=$connectBDD->prepare("SELECT * FROM ".$db."man WHERE text= ? ");
			$result2->execute(array($_GET['uid']));
			if($result2) { $man2 = $result2->fetchAll(PDO::FETCH_OBJ); if($man2) { $ab_number = count($man2); } else { $ab_number = 1; } } 
			//On insert le manuscrit
			$count = $connectBDD->prepare("INSERT INTO ".$db."man (id, name, location, abrev, text) VALUES ('', ? , ? , ? , ? )");
			$count->execute(array($_POST['name'], $_POST['location'], substr($_POST['name'], 0, 2).$ab_number , $_GET['uid']));
			
			$id = $connectBDD->lastInsertId(); // Voilà !
			$storeLogs->execute(array($db.'text_man', $id, $_SESSION['uid']));
			delcache("json.man", $db."trad_man", $_GET['uid']);
			success(0);
			$name = $_POST['name'];
		}
	}	
	
?>