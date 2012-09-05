<?php
	#Fonctions de print de texte
	function text_annote($options) // Retourne le texte de la div avec les epi-comment, epi-note
	/*
		$options => array(
			0	id			=>	Identifiant numérique
			1	table		=>	Table sql des part
			2	row			=>	Colonne identifiant des part
			3	class		=>	Classe des notes
			4	Tabltxt		=>	Table du text
			5	Row			=>	Row du text
					)
	*/
	{
		global $connectBDD;
		//On récupère le texte
		$sql = $connectBDD->prepare("SELECT ".$options[5]." as textsrc FROM ".$options[4]." WHERE id = ? LIMIT 1");
		$sql->execute(array($options[0]));
		if($sql->rowCount() == 0)
		{
			die(error(0));
		}
		$text = $sql->fetch(PDO::FETCH_OBJ);
		$textnote = $text->textsrc;
		
		//Maintenant on va chercher les parts annotées
		$result=$connectBDD->prepare("SELECT * FROM ".$options[1]." WHERE ".$options[2]."= ? ORDER BY start ASC");
		$result->execute(array($options[0]));
		if($result->rowCount() > 0) // Si true, ça a marché !)
		{
			$added = 0;//On set le curseur d'addition 
			$idpart = 1;
			$idnote = 1;
			
			//Equivalent mysql_fetch_array() 
			while($part = $result->fetch(PDO::FETCH_OBJ))
			{
				//On crée les balises autour des notes
				$before = '<span val="'.$part->id.'" class="'.$options[3].'">';
				$after = "</span>";
				
				//On ajuste en fonction des \n
				$n = 0;
				if($part->start > 0) { $n = substr_count($text->textsrc, "\n", 0, $part->start); }
				if(substr($text->textsrc, $part->start, 1) == "\n")	{ ++$n; } //Si on a toujours un n devant le machin
				
				//On ajuste les positions
				$posBefore = $part->start + $added + $n; // Added étant incrémenté à chaque part
				if(substr($textnote, $posBefore, 1) == "\n"){ ++$posBefore; ++$n; } //Si on a toujours un n devant le machin
				$posAfter = $part->end + strlen($before) + $added + $n;
				
				//On ajoute les notes 
				$textnote = substr_replace($textnote, $before, $posBefore, 0);//On ajoute avant
				$textnote = substr_replace($textnote, $after, $posAfter, 0);//On ajoute après
				
				//On met à jour la position globale et le numéro de note
				$added = $added + strlen($before) + strlen($after);
				++$idpart;
			}
		}
		return nl2br($textnote);
	}
?>