<?php
	if(isset($_GET['uid']) && ($_GET['uid'] > 0))
	{
		$text = new text;
		$text->everything_from_text($_GET['uid']); 
	}
	elseif(isset($_GET['chp']) && isset($_GET['ss']) && isset($_GET['bid']))
	{
		$text = new text;
		$text->everything_from_chap_sschap($_GET['chp'], $_GET['ss'], $_GET['bid']); 
	}
	
	$arianne = array(
		'active' => $text->chapter . ', '.$text->sschapter,
		'path' => array(
			"Lire" => "read.html",
			langReturn($text->lage) => "read/".$text->lage.".html",
			$text->authorname => "read/".$text->lage."/".$text->authorid."/".$text->authorname.".html",
			$text->bookname => "read/".$text->lage."/".$text->authorname."/".$text->book."/".$text->bookname.".html"			
		));
?>