<?php
	//Pour reprendre le système de p.read, on a besoin de 
	#	text->
	#			text 	: id du doc
	#			textsrc : texte en lui même
	#			authorname : nom de l'auteur
	#			title : titre du document
	$db = "social_doc_";
	if($text->textsrc != '')
	{
			//On récupère le texte annoté
			if($cache->exists("block", "document", $text->text, array("text")))
			{
				$textnote = $cache->text("block", "document", $text->text, array("text"));
			}
			else
			{
				$textnote = text_annote(array($text->text, "social_doc_part", "text", "epi-note", "social_doc_src", "src"));
				$cache->write($textnote, "block", "document", $text->text, array("text"));
			}
	?>
	<?php
			$block = '<h1>'.$text->title . '</h1><h2>'.$text->authorname.'</h2><input type="hidden" id="textid" value="'.$text->text.'" />
			<p>
				<button class="btn" id="noteaction"><i class="icon-plus"></i> Ajouter note(s)</button>
				<button class="btn" name="tradbutton" id="tradaction"><i class="icon-pencil"></i> Traduire</button>
				<button class="btn"><i class="icon-print"></i> Imprimer</button>
			</p>
			<div id="poem">'.$textnote.'</div>
			<textarea id="poemarea" readonly class="area-disabled">'.$text->textsrc.'</textarea>
			';
			echo $block;
	}
	else
	{
		echo '<div class="error">Aucun texte ne correspond</div>';
	}
?>