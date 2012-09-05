<?php
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
	
	//Le cache
	require_once(RELurl.'inc/inc.class.cache.php');	
	if(isset($_GET['lage']) && isset($_GET['text'])) 
	{ 
	$page = "block-trad";
	$exec = array($_GET['text']);
	if(isset($_SESSION['uid'])) { $page.="-1"; } else { $page.="0"; }
	if($_GET['lage'] != "") { $options = "AND lage = ?"; array_push($exec, $_GET['lage']); } else { $options = ""; }
	$a = $_GET['text'].'-'.$_GET['lage'];
	//$cache = new start_cache($page, $db."trad_text", $a);//Pagename, Table, Item  // ("block-trad", "trad_text", $_GET['text'] . '-' .$_SESSION['lage']);
	//Fin du cache
	
	require_once(RELurl.'inc/inc.conn.php');
	#Page d'affichage minor de Read : traduction en gros
	//On vérifie qu'on a un texte source
	$trad = $connectBDD->prepare("SELECT * FROM ".$db."trad_text WHERE text=? AND archive=0 ".$options);//Soit on met un ajax pour sélectionner d'autres langues aussi...
	$trad->execute($exec);
	//On crée la var de print/cache $block
	$block = "";
	if($trad->rowCount() > 0) // Si on a des traductions dispos
	{
		//On prépare les traductions en elles même
		while($tdat = $trad->fetch(PDO::FETCH_OBJ))
		{
			$tradtext = $tdat->trad;
			//Maintenant on va chercher les parts annotées
			$tradpart_sql=$connectBDD->prepare("SELECT * FROM ".$db."trad_part WHERE trad= ?  ORDER BY start ASC");
			$tradpart_sql->execute(array($tdat->id));
			if($tradpart_sql->rowCount() > 0) // Si true, ça a marché !)
			{
				$added = 0;//On set le curseur d'addition 
				
				//Equivalent mysql_fetch_array() 
				while($tradpart = $tradpart_sql->fetch(PDO::FETCH_OBJ))
				{
					//On crée les balises autour des notes
					$before = '<span val="'.$tradpart->id.'" class="epi-comment">';
					$after = "</span>";
					
					//On ajuste en fonction des \n
					$n = 0;
					$n = substr_count($tdat->trad, "\n", 0, $tradpart->start);
					if(substr($tdat->trad, $tradpart->start, 1) == "\n")	{ ++$n; }
					
					//On ajuste les positions
					$posBefore = $tradpart->start + $added + $n; // Added étant incrémenté à chaque part
					$posAfter = $tradpart->end + strlen($before) + $added + $n;
					//On ajoute les lien de commentaire 
					$tradtext = substr_replace($tradtext, $before, $posBefore, 0);//On ajoute avant
					$tradtext = substr_replace($tradtext, $after, $posAfter, 0);//On ajouter après
					
					//On met à jour la position globale et le numéro de note
					$added = $added + strlen($before) + strlen($after);
				}
			}
			
			if($tdat->authorid == 0 ) { $autor = $tdat->authorname; } else { $autor = "membre"; }
			//$block .= '<h2>Traduction de '.$autor.' ('.$tdat->year.')</h2>';//On affiche le header de la note
			/*
			 <div class="traduction-div">
                  <h4>Membre (2012)</h4>
                  <button class="btn btn-small pull-right">Commenter</button><br clear="right" />
                  <p class="traduction-text">La traduction ici c'est trop cool</p>
                </div>
			*/
			$block .= '
				<div class="traduction-div" id="traduction-'.$tdat->id.'">
					<div class="pull-right">
						<input type="button" class="btn btn-small traduction-action" value="Commenter" />
						<span class="tradnote" id="tradnote-'.$tdat->id.'" item="'.$tdat->id.'"></span>
					</div>
					<h4>'.$autor.' ('.$tdat->year.')</h4>
					<br clear="all" />
						<p class="traduction-text">'.nl2br($tradtext).'</p>
						<textarea val="'.$tdat->id.'" class="traduction-area area-disabled" readonly="readonly">'.$tdat->trad.'</textarea>
				</div>
			';
			/*
			$rate = ' <span class="tradnote" id="tradnote-'.$tdat->id.'" item="'.$tdat->id.'"></span>';
			$block .= '<div id="showtrad-'.$tdat->id.'" class="showtrad-div"><h2>Traduction de '.$autor.' ('.$tdat->year.') '.$rate.'</h2>';//On affiche le header de la note
			//$block .= '';
			$block .= '<button class="tradaction" val="'.$tdat->id.'">Commentaire</button>';//On affiche les boutons (dont le switch)
			$block .= '<div class="tradtext trad-text-'.$tdat->id.'">'.nl2br($tradtext).'</div>';//On affiche la zone avec les clicks possibles
			$block .= '<textarea val="'.$tdat->id.'" readonly="readonly" class="tradtoclick trad-tarea-'.$tdat->id.'">'.$tdat->trad.'</textarea></div>';//On affiche la zone avec la selection possible
			*/
		}
	}
	else
	{
		$block = "Aucune traduction pour cette(ces) langue(s).";
	}
	echo $block;
}
?>