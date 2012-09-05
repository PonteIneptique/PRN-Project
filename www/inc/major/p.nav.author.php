<?php
if(isset($author->name))
{
	$m_q=$connectBDD->prepare("SELECT b.id, b.name as name, COUNT(DISTINCT chapter) as chapters, COUNT(*) as texts FROM book b, text_book tb WHERE b.author= ? AND tb.book=b.id");
	$m_q->execute(array($author->id));
	if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
	{
		$echo = "<h1>".$author->name."</h1>";
		$echo .= "<ul class='unstyled'>";
		while($book = $m_q->fetch(PDO::FETCH_OBJ))
		{
			$echo .= '<li class="unstyled"><a href="read/'.$author->lage.'/'.$author->name.'/'.$book->id.'/'.urlencode($book->name).'.html"><i class="icon-book"></i> '.$book->name.' ('.$book->texts.' Textes / '.$book->chapters.' Chapitres)</a></li>';
		}
		$echo .= "</ul>";
	}
	echo $echo;
}
?>