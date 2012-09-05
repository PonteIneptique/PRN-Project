<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	//Gestion de l'alternative Social_DOC//Trad avec les sécurités affiliées
	//On commence par simplement vérifier si on est en social_doc
	// ".$db."
	$db = "text_";
	$page = "read";
	if(isset($_GET['sd']))
	{
		$document = new document($_POST['id'], false);
		if($document->access == false) { exit( error(3)); }
		else { $db = "social_doc_"; $page = "document"; }
	}
	elseif(!sessionBolean())
	{
		exit(error(3));
	}

//Part

	//On vérifie qu'une part n'existe pas encore
	$result=$connectBDD->prepare("SELECT * FROM ".$db."part WHERE text= ? AND start= ? ORDER BY start ASC LIMIT 1");
	$result->execute(array($_POST['id'], $_POST['start']));
	if($result) // Si true, trop tard ! On récupère donc l'id
	{
		$note = $result->fetch(PDO::FETCH_OBJ);
		if($note)
		{
			$id = $note->id; // On store l'id
			$count = 1;
		}
		else // Sinon on enregistre la nouvelle et on chope l'id
		{
			$part = $connectBDD->prepare("INSERT INTO ".$db."part (id, start, end, text) VALUES ('', ? , ? , ? )");//Count marche pas O_o
			$part->execute(array($_POST['start'], $_POST['end'], $_POST['id']));
			if($part) { $count = 1; }
			$id = $connectBDD->lastInsertId(); // Voilà !
			$storeLogs->execute(array($db.'part', $id, $_SESSION['uid']));
			
			$cache->delete("block", $page, $_POST['id'], array("text"));
			echo '<input type="hidden" class="auto-reload" data-target="#poem" data-type="div" data-src="text.php?i='.$_POST['id'].'&type='.$page.'&el=text" />';//Par opposition à traduction
		}
	}

	//Si le manuscrit est posté :
	if(isset($_POST['maninput']) && ($count ==1))
	{
		//On fait une recherche
		$m_q=$connectBDD->prepare("SELECT * FROM ".$db."man WHERE id= ? AND text= ? LIMIT 1");
		$m_q->execute(array($_POST['maninput'], $_POST['id']));
		if($m_q)
		{ 
			$man = $m_q->fetch(PDO::FETCH_OBJ);
			if($man)//Si on a des résultats
			{
				$ivar = $connectBDD->prepare("INSERT INTO ".$db."var (id, part, var, text, man) VALUES ('', ? , ? , ? , ? )");
				$ivar->execute(array($id, $_POST['note'], $_POST['id'], $man->id));
				if($ivar) { success(0); }
				$storeLogs->execute(array($db.'var', $connectBDD->lastInsertId(), $_SESSION['uid']));
				delcache("tt-note", $db."part", $id);
			}
			else//Si on a pas de manuscrit enregistré sous ce nom
			{
				error(4);
			}
		}
		else { error(0); }
	}
	else//On passe en note
	{
		if($count == 1)
		{
			//On vérifie qu'elle existe pas déjà quand même...
			$m_n=$connectBDD->prepare("SELECT * FROM ".$db."note WHERE note= ? AND text= ? AND part= ? LIMIT 1");
			$m_n->execute(array($_POST['note'], $_POST['id'], $id));
			//Note
			if($m_n) { 
				if ($m_n->rowCount() == 0) {
					$count = $connectBDD->prepare("INSERT INTO ".$db."note (id, part, note, text) VALUES ('', ? , ? , ? )");
					$count->execute(array($id, $_POST['note'], $_POST['id']));
					if($count) 
					{ 
						success(0);
					}
					$noteid = $connectBDD->lastInsertId();
					$storeLogs->execute(array($db.'note', $noteid, $_SESSION['uid']));
					delcache("tt-note", $db."part", $id);
					
					
					//Maintenant on traite la biblio !
					if(isset($_POST['bibliographie']) && is_array($_POST['bibliographie']))//On vérifie qu'il y en a
					{
						//On prépare les variables
						$v_b = $connectBDD->prepare("SELECT id FROM bibliography WHERE id = ?");//La biblio existe ?
						$quote_text = $connectBDD->prepare("INSERT INTO ".$db."bibliography_quote_text (id, note, biblio) VALUES ('', ? , ? )");//Insertion de la relation note/biblio
						
						
						//Ces requêtes changent en cas de document
						if($db == "")
						{
							$v_hub = $connectBDD->prepare("SELECT id FROM bibliography_hub WHERE biblio= ? AND author = ? AND book = ? AND text_src = ? LIMIT 1");//Vérification de l'existence de la biblio dans le hub (Relation text/auteur/livre/source//biblio)
							$quote_hub = $connectBDD->prepare("INSERT INTO bibliography_hub (id, biblio, author, book, text_src) VALUES ('', ? , ? , ? , ? )");//On insert dans le hub
							//On va chercher les infos relatives au texte
							$text = new text;
							$text->everything_from_text($_POST['id']); 
						}
						else
						{
							$v_hub = $connectBDD->prepare("SELECT id FROM social_doc_bibliography_hub WHERE biblio= ? AND document = ? LIMIT 1");//Vérification de l'existence de la biblio dans le hub (Relation text/auteur/livre/source//biblio)
							$quote_hub = $connectBDD->prepare("INSERT INTO social_doc_bibliography_hub (id, biblio, document) VALUES ('', ? , ?)");//On insert dans le hub
						}
						foreach($_POST['bibliographie'] as $key => $value)//On traite le tableau de biblio
						{
							if($value != 0)//Si ce n'est pas "nouveau"
							{
								$v_b->execute(array($value));//On execute la verif
								if($v_b->fetch(PDO::FETCH_OBJ))//Si elle existe
								{
									if($quote_text->execute(array($noteid, $value)))//Si on a inséré
									{
										//On passe à la vérification de hub
										if($db == "")// En cas de text
										{
											$hub_array = array($value, $text->authorid, $text->book, $text->text);
										}
										else//En cas de doc
										{
											$hub_array = array($value, $_POST['id']);
										}
										$v_hub->execute($hub_array);//On lance la requete de verif hub
										if(!$v_hub->fetch(PDO::FETCH_OBJ))
										{
											$quote_hub->execute($hub_array);//Ca fonctionne :D
										}										
									}
								}
							}
						}
						//Fin biblio
					}
				}
				else
				{
					error(5); // Existe déjà
				}
			}
			else
			{
				error(0);
			}
		}
		else
		{
			error(0);
		}
//Note/Var	
	}
?>
