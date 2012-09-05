<?php
	//Pour reprendre le système de p.read, on a besoin de 
	#	text->
	#			text 	: id du doc
	#			textsrc : texte en lui même
	#			authorname : nom de l'auteur
	#			title : titre du document
	if(isset($_GET['i']) && ($_GET['i'] > 0) && sessionBolean())
	{
		$text = new document($_GET['i']);
	}
?>