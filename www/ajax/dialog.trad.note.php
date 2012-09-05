<?php
	define("RELurl", '../');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "";
	if(isset($_GET['sd']))
	{
		require_once(RELurl."/inc/inc.class.document.php");
		$document = new document(false, false);
		$document->getaccessfrompart($_GET['part'], false);
		if($document->access == false) { exit(error(3)); }
		else { $db = "social_doc_"; }
	}
	
	//Le cache
	require_once(RELurl.'inc/inc.class.cache.php');	
	if(isset($_GET['part'])) { $a = $_GET['part']; } else { $a = ""; }
	//$cache = new start_cache("dialog.trad.note", $db."trad_part", $a);//Pagename, Table, Item
	//Fin du cache
	
	require_once(RELurl.'inc/inc.conn.php');
	$answer = array(); //Tableau pour les réponses pour éviter les multiples requêtes
	function showtradcomment($part, $reply, $start = 0) {
		global $comment, $db, $answer;//On fait appel au prepare et aux variables utiles
		$comment->execute(array($part, $reply, $db.'trad_comment'));//On execute avec nos données
		if($comment->rowCount() > 0) //Si on a des réponses
		{
			$class = "";
			if($reply > 0) { $class = "margin10"; }
			$echo = "";
			//Le fetch object ne marchait pas, allez savoir pourquoi...
			$z = $comment->fetchAll(PDO::FETCH_UNIQUE);
			foreach($z as $lineid => $comline)
			{
				$answer[$lineid] = $comline['username'];
				$replyto = ""; if($reply != 0) { $replyto = " à ".$answer[$reply]; }
				$echo .= '
					<div class="note '.$class.'">
						<p>'.nl2br($comline['comment']).'</p>
						<div class="footer">
							'.$comline['username'].$replyto.'
							<span class="answer pull-right" val="'.$lineid.'" to="'.$comline['username'].'" start="'.$start.'" trad="'.$comline['trad'].'" ><i class="icon-share-alt"></i> Répondre</span>
						</div>
					</div>
				';
				$echo .= showtradcomment($part, $lineid, $start);
			}
			return $echo;
		}
	}
	
	//Si get_part a été donné 
	if(isset($_GET['part']))
	{
		$partreq=$connectBDD->prepare("SELECT * FROM ".$db."trad_part WHERE id= ? ORDER BY start ASC LIMIT 1");
		$partreq->execute(array($_GET['part']));
		if($partreq->rowCount() == 1)//On a bien la partie qui a appelé ceci
		{
			//On store les infos de la part
			$part = $partreq->fetch(PDO::FETCH_OBJ);
			
			//On va chercher les infos du text pour afficher la portion
			$tradr = $connectBDD->prepare("SELECT * FROM ".$db."trad_text WHERE id=? LIMIT 1");
			$tradr->execute(array($part->trad));
			if($tradr->rowCount() > 0) // Si on a la traduction
			{
				$trad = $tradr->fetch(PDO::FETCH_OBJ);
				
				//On set les balises à implanter
				$before = "<b>";
				$after = "</b>";
				
				//On met le cache
				$tradtext = $trad->trad;
				
				//On ajuste en fonction des \n
				$n = 0;
				$n = substr_count($trad->trad, "\n", 0, $part->start);
				if(substr($trad->trad, $part->start, 1) == "\n")	{ ++$n; } //Si le caractère de départ est un \n
				
				//Si on commence après 20
				if($part->start > 20) { $start = $part->start - 20; $substart = 20; } else { $start = 0; $substart = $part->start; }
				//Si on finit après 20
				$end = strlen($tradtext);
				$diff = $end - $part->end;//Longueur entre end et la fin du tradtext
				if($diff > 20) { $end = $part->end + 20; $diff = 20; } elseif($diff < 0) { $diff = 0; }
				
				//Maintenant on coupe
				$tradtext = substr($tradtext, $start, $end - $start);
				
				//Maintenant on positionne la fin
				$subend = strlen($before) + strlen($tradtext) - $diff ;
				
				//On ajoute le gras 
				$tradtext = substr_replace($tradtext, $before, $substart, 0);//On ajoute avant
				$tradtext = substr_replace($tradtext, $after, $subend, 0);//On ajouter après
				
				echo '<h3>Extrait annoté :</h3><div class="comments-extract alert alert-info">&#8220;...'.$tradtext.'...&#8221;</div>';
			}
				
			//On va chercher les commentaires
			$comment = $connectBDD->prepare("SELECT ".$db."trad_comment.id, ".$db."trad_comment.comment,  ".$db."trad_comment.reply, ".$db."trad_comment.trad, users.name as username FROM ".$db."trad_comment , logs, users WHERE ".$db."trad_comment.part= ? AND ".$db."trad_comment.reply= ? AND logs.table= ?  AND logs.item=".$db."trad_comment.id AND users.id=logs.user");//On store le prepare
			echo '<h3>Commentaires</h3>'.showtradcomment($part->id, 0, $part->start);
		}
		else
		{
			error(9);
		}
	}
?>