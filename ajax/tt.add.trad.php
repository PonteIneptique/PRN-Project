<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "";
	if(isset($_GET['sd']))
	{
		$document = new document($_GET['uid'], false);
		if($document->access == false) { exit(error(3)); }
		else 
		{ 
			$db = "social_doc_"; 
			$t = $connectBDD->prepare("SELECT id FROM social_doc_src WHERE id= ? LIMIT 1");
			$t->execute(array($_GET['uid']));
		}
	}
	elseif(!sessionBolean())
	{
		exit(error(3));
	}
	else
	{	
		$t = $connectBDD->prepare("SELECT id FROM text_src WHERE id= ? LIMIT 1");
		$t->execute(array($_GET['uid']));
	}
	//On vérifie que le texte existe
	
	$tr = $connectBDD->prepare("SELECT id FROM ".$db."trad_text WHERE text = ? AND trad = ? LIMIT 1");
	$tr->execute(array($_GET['uid'], $_POST['trad']));
	if(($t->rowCount() == 1) && ($tr->rowCount() == 0))
	{
		if($_POST['autorid'] == 1)
		{
			$autorid = $_SESSION['uid'];
			$autorname = NULL;
			$year = date("Y");
			$f = true;
		}
		elseif(($_POST['year'] != '') && ($_POST['autorname'] != ''))
		{
			$autorid = 0;
			$autorname = $_POST['autorname'];
			$year = $_POST['year'];
			$f = true;
		}
		if(($f == true) && ($_POST['trad'] != ''))
		{
			$ins = $connectBDD->prepare("INSERT INTO ".$db."trad_text (id, text, year, authorid, authorname, trad, archive, archive_date, lage) VALUES ('', ? , ? , ? , ? , ? , '0' , NULL , ? )");
			$ins->execute(array($_GET['uid'], $year, $autorid, $autorname, $_POST['trad'], $_POST['lage']));
			if($ins->rowCount() == 1)
			{
				$id = $connectBDD->lastInsertId(); // Voilà !
				$storeLogs->execute(array($db.'trad_text', $id, $_SESSION['uid']));
				success(0);
				delblockcache("block-trad", $db."trad_text", $_GET['uid'] . '-' .$_POST['lage']);
				delcache("json.trad.lang", $db."trad_text", $_GET['uid']);
			}
			else
			{
				error(0);
			}
		}
		else
		{
			error(1);
		}
	}
	else
	{
		error(5);
	}
?>