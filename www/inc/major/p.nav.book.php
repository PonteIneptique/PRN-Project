<?php
if(isset($book->name))
{
	if(blockcacheexists("block-book", "text_book", $book->id))//On vérifie qu'il n'y a qu'une réponse
	{
		blockviewcache("block-book", "text_book", $book->id);
	}
	else
	{
		$chap = array();
		$m_q = $connectBDD->prepare("SELECT tb.text, tb.chapter as tbchapter, tb.sschapter as tbsschapter FROM text_book tb WHERE tb.book= ? ORDER BY chapter, sschapter ASC");
		$m_q->execute(array($book->id));
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			$minor = "<ul class='unstyled text-kw-list cache' style='margin:5px;'>";
			$echo = "<h1>".$book->name."</h1>";
			$echo .= "<h2>".$book->authname."</h2>";
			//Et là on fait le navigateur de chapitre

			$echo .= "<ul class='unstyled' style='margin-left:10px;'>";
			#PREPARE Keyword sql
			$kql = $connectBDD->prepare("SELECT kw FROM keyword_use WHERE text= ?");
			while($text = $m_q->fetch(PDO::FETCH_OBJ))
			{
				$kw_list = " text-kw-filtered ";
				$kql->execute(array($text->text));
				while($kw = $kql->fetch(PDO::FETCH_OBJ))
				{
					$kw_list .= " text-kw-filtered-".$kw->kw." ";
				}
				#$prevchapt = $text->tbchapter - 1;
				//Si c'est le premier du chapitre
				if(!isset($chap[$text->tbchapter]))
				{
					if(($lastchapter != $text->tbchapter) && isset($lastchapter)) // Si on a déjà défini un chapitre précédent, c'est qu'on change de chapitre
					{
						$echo .= "</ul>";
					}
					$echo .= "<li class='nav-parent link' hierarchie='chapter-".$text->tbchapter."'><i class='icon-align-justify'></i> Chapitre ".$text->tbchapter."</li>";
					$echo .= "<ul class='nav-children parent-chapter-".$text->tbchapter."'>";
					$chap[$text->tbchapter] = array();
				}
				$echo .= "<li class='nav-children parent-chapter-".$text->tbchapter."' ><a href='./read/".urlencode($book->alage)."/".urlencode($book->authname)."/".urlencode($book->name)."/".$book->id."/Chapter-".$text->tbchapter."/Section-".$text->tbsschapter.".html'>Section ".$text->tbsschapter."</a></li>";
				//"./read/".urlencode($text->alage)."/".urlencode($text->aname)."/".urlencode($text->bname)."/".$text->bid."/Chapter-".$man->tbchapter."/Section-".$man->tbsschapter.".html"
				//$echo .= '<li><a>'.$book->name.' ('.$book->texts.' Textes / '.$book->chapters.' Chapitres)</a></li>';
				$lastchapter = $text->tbchapter;
				$minor .= '<li class="'.$kw_list.'"><a href="./read/'.urlencode($book->alage).'/'.urlencode($book->authname).'/'.urlencode($book->name).'/'.$book->id.'/Chapter-'.$text->tbchapter.'/Section-'.$text->tbsschapter.'.html"><i class="icon-align-justify"></i> Chapitre '.$text->tbchapter.', Section '.$text->tbsschapter.'</a></li>';
			}
			$echo .= "</ul></ul>";
			$minor .="</ul>";
		}
		echo $echo;
		//writeblockcache("block-author", "book", $author->id, $block);
	}
}
?>