<?php
	define("RELurl", '../');
	
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "text_";
	if(isset($_GET['sd']))
	{
		require_once(RELurl."/inc/inc.class.document.php");
		$document = new document($_GET['text'], false);
		if($document->access == false) { error(3); }
		else { $db = "social_doc_"; }
	}
	
	//Le cache
	require_once(RELurl.'inc/inc.class.cache.php');	
	if(isset($_GET['part']) && isset($_GET['text'])) { $a = $_GET['part']; } else { $a = ""; }
	//$cache = new start_cache("tt-note", $db."part", $a);//Pagename, Table, Item
	//Fin du cache
	
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_GET['part']) && isset($_GET['text']))
	{
		if($db == "text_") { $champs = "text"; } else { $champs = "src as text"; } //Pour les changements de nom de champs des sources
		$p_req = $connectBDD->prepare("SELECT * FROM ".$db."part WHERE text = ? AND id = ? LIMIT 1");
		$p_req->execute(array($_GET['text'], $_GET['part']));
		if($p_req->rowCount() == 1)
		{
			$part = $p_req->fetch(PDO::FETCH_OBJ);
			
			//On balance l'affichage de l'extrait
			$textr = $connectBDD->prepare("SELECT ".$champs." FROM ".$db."src WHERE id=? LIMIT 1");
			$textr->execute(array($part->text));
			if($textr->rowCount() > 0) // Si on a la traduction
			{
				$text = $textr->fetch(PDO::FETCH_OBJ);
				
				//On set les balises à implanter
				$before = "<b>";
				$after = "</b>";
				
				//On met le cache
				$cachetext = $text->text;
				
				//On ajuste en fonction des \n
				$n = 0;
				$n += substr_count($text->text, "\n", 0, $part->end);
				
				//Si on commence après 20
				if($part->start > 20) { $start = $part->start - 20; $substart = 20; } else { $start = 0; $substart = $part->start; }
				//Si on finit après 20
				$end = strlen($cachetext);
				$diff = $end - $part->end;//Longueur entre end et la fin du tradtext
				if($diff > 20) { $end = $part->end + 20; $diff = 20; } elseif($diff < 0) { $diff = 0; }
				
				//Maintenant on coupe
				$cachetext = substr($cachetext, $start, $end - $start);
				
				//Maintenant on positionne la fin
				$subend = strlen($before) + strlen($cachetext) - $diff ;
				
				//On ajoute le gras 
				$cachetext = substr_replace($cachetext, $before, $substart+$n, 0);//On ajoute avant
				$cachetext = substr_replace($cachetext, $after, $subend+$n, 0);//On ajouter après
				
				echo '<div class="comments-extract alert alert-info">&#8220;...'.$cachetext.'...&#8221;</div>';
					
			}
			//On crée le cache pour créer des notes à partir de cette fenetre
			echo '<input type="hidden" class="end" value="'.$part->end.'"><input type="hidden" class="start" value="'.$part->start.'">';
			//On va chercher les notes
			$q_note=$connectBDD->prepare("SELECT * FROM ".$db."note WHERE text= ? AND part= ? "); 
			$q_note->execute(array($_GET['text'], $_GET['part'])); // La requête qui convient
			if($q_note->rowCount() > 0)
			{				
				$biblio = $connectBDD->prepare("SELECT bibliography.title, bibliography.url, bibliography.auteur FROM bibliography, ".$db."bibliography_quote_text WHERE bibliography.id=".$db."bibliography_quote_text.biblio AND ".$db."bibliography_quote_text.note= ? ");
				while($note = $q_note->fetch(PDO::FETCH_OBJ))
				{
					echo '<div class="note">';					
					//On execute la requete de biblio
					$biblio->execute(array($note->id));
					$rate = ' <span class="noterate" id="noterate-'.$note->id.'" item="'.$note->id.'"></span> ';
					if((strlen($note->note) > 255) || ($biblio->rowCount() > 0))//255 étant très long // On rajoutera IF biblio
					{
						//On prépare le raccourci
						$shownote = substr($note->note,0,50);
						$space = strrpos($shownote, " ");
						if($space) { $shownote = substr($shownote, 0, $space); }
						//On prépare la biblio
						$bibl = "";
						
						if($biblio->rowCount() > 0)
						{
							$bibl = '<i class="icon-book"></i> Bibliographie<ul class="note-bibliographie">';
							while($bb = $biblio->fetch(PDO::FETCH_OBJ))
							{
								$bibl .= '<li>&raquo; '.$bb->title.', '.$bb->auteur.' <a href="'.$bb->url.'">( Lien )</a></li>';
							}
							$bibl .= '</ul>';
						}
						echo '
							<p class="link note-maximize"><i class="icon-plus-sign"></i> '.$shownote.'... </p>
							<div class="note-all">
								<p>'.nl2br($note->note).'</p>
								'.$bibl.'
								<div class="note-minimize link"><i class="icon-minus-sign"></i> Réduire</div>
							</div><!-- note-all -->
						';
					}
					else
					{
						echo '<div>'.nl2br($note->note).'</div>';//tid correspond à l'id dans cette présentation
					}
					echo '<div class="footer">&nbsp;<div class="pull-right">'.$rate.'</div></div>
					</div>';
					++$note;
				}
			}
			//On va chercher les vars de manuscrits
			$q_var=$connectBDD->prepare("SELECT * FROM ".$db."var WHERE text= ? AND part= ? "); // La requête qui convient
			$q_var->execute(array($_GET['text'], $_GET['part']));
			if($q_var->rowCount() > 0)
			{
				echo '</div><hr />
						<span class="pull-right">
							<span class="link minimize-next" target="var-list"><i class="icon-minus-sign"></i> Réduire</span>
						</span>'; // On ferme les notes
				echo '<h3>Variations</h3>';
				echo '<div id="var-list"><ul class="unstyled">';
				$q_var->setFetchMode(PDO::FETCH_OBJ);
				//On prépare la proche requete
				$q_man = $connectBDD->prepare("SELECT * FROM ".$db."man WHERE text= ? AND id= ? ");
				while($var = $q_var->fetch())
				{
					$q_man->execute(array($_GET['text'], $var->man)); // La requête qui convient
					if($q_man)
					{
						$q_man->setFetchMode(PDO::FETCH_OBJ);
						$man = $q_man->fetch();
						echo '<li uid="'.$var->id.'">'.$var->var.' (<a href="#manuscrit_'.$man->id.'">'.$man->abrev.'</a>)</li>';
					}
				}
				echo '</ul></div>';
			}
		}
	}
?>