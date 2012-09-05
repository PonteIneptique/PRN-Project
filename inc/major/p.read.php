<?php
	if($text->textsrc != '')
	{
		
		//On récupère le texte annoté
		if($cache->exists("block", "read", $text->text, array("text")))
		{
			$textnote = $cache->text("block", "read", $text->text, array("text"));
		}
		else
		{
			$textnote = text_annote(array($text->text, "text_part", "text", "epi-note", "text_src", "text"));
			$cache->write($textnote, "block", "read", $text->text, array("text"));
		}
		
		$block = '<h1>'.$text->bookname . ', '.$text->chapter.', '.$text->sschapter.'</h1><h2>'.$text->authorname.'</h2><input type="hidden" id="textid" value="'.$text->text.'" />
		<p>
		'.sessionString2Var('
        	<button class="btn" id="noteaction"><i class="icon-plus"></i> Ajouter note(s)</button>
        	<button class="btn" name="tradbutton" id="tradaction"><i class="icon-pencil"></i> Traduire</button>
		').' 
        	<button class="btn"><i class="icon-print"></i> Imprimer</button>
		</p>
		<div id="poem">'.$textnote.'</div>
		<textarea id="poemarea" readonly class="area-disabled">'.$text->textsrc.'</textarea>
		';
		echo $block;
	}
	else
	{
		error(0);
		echo 'z';
	}
?>