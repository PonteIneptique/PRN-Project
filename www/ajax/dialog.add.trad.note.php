<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	$db = "";
	$page = "read";
	if(isset($_GET['sd']))
	{
		$document = new document($_POST['tradid'], false);
		if($document->access == false) { die(error(3)); }
		else { $db = "social_doc_"; $page = "document"; }
	}
	elseif(!sessionBolean())
	{
		die(error(3));
	}
	
	
	//Part
	//On va chercher les infos relatives à la traduction
	$rtrad=$connectBDD->prepare("SELECT id, text, lage FROM ".$db."trad_text WHERE id= ? LIMIT 1");
	$rtrad->execute(array($_POST['tradid']));
	if($rtrad->rowCount() == 0) { die(error(9)); } else {
		$trad = $rtrad->fetch(PDO::FETCH_OBJ);
	}
	
	//On vérifie qu'une part n'existe pas encore
	$result=$connectBDD->prepare("SELECT * FROM ".$db."trad_part WHERE trad= ? AND start= ? ORDER BY start ASC LIMIT 1");
	$result->execute(array($_POST['tradid'], $_POST['start']));
	if($result) // Si true, trop tard ! On récupère donc l'id
	{
		if($result->rowCount() == 1)
		{
			$part = $result->fetch(PDO::FETCH_OBJ);
			$id = $part->id; // On store l'id
		}
		else // Sinon on enregistre la nouvelle et on chope l'id
		{
			$part = $connectBDD->prepare("INSERT INTO ".$db."trad_part (id, start, end, trad) VALUES ('', ? , ? , ? )");//Count marche pas O_o
			$part->execute(array($_POST['start'], $_POST['end'], $_POST['tradid']));
			$id = $connectBDD->lastInsertId(); // Voilà !
			$storeLogs->execute(array($db.'trad_part', $id, $_SESSION['uid']));
			//On efface le cache de block puisque on a ajouté une part...
			$cache->delete("block", $page, $trad->text, array("traduction", $_POST['tradid']));
			echo '<input type="hidden" class="auto-reload" data-target="#traduction-'.$_POST['tradid'].' .traduction-text" data-type="div" data-src="text.php?i='.$trad->text.'&type='.$page.'&el=traduction&eli='.$_POST['tradid'].'" />';//Par opposition à traduction
		}
	}
	
	//On vérifie si on a la part
	if($id > 0)
	{
		//On passe à la vérification d'une note comparable
		$existnote = $connectBDD->prepare("SELECT * FROM ".$db."trad_comment WHERE part = ? AND comment = ? LIMIT 1");
		$existnote->execute(array($id, $_POST['note']));
		//On applique donc un rowcount
		if($existnote->rowCount() > 0)//Commentaire déjà existant (double post, redire la réponse d'un autre comme un boulet) ou une erreur
		{
			die(error(5));
		}
		else
		{
			//Si on a une reply, on set reply to $_POST['reply'] sinon on set à 0
			if(isset($_POST['reply'])) { $reply = $_POST['reply']; } else { $reply = 0; }
			
			$comment = $connectBDD->prepare("INSERT INTO ".$db."trad_comment (id, part, comment, trad, reply) VALUES ('', ? , ? , ? , ? )");
			$comment->execute(array($id, $_POST['note'], $_POST['tradid'], $reply));
			if($comment->rowCount() == 1)
			{
				$storeLogs->execute(array($db.'trad_comment', $connectBDD->lastInsertId(), $_SESSION['uid']));
				success(0);
				delcache("dialog.trad.note", $db."trad_part", $id);
			}
		}
	}
?>