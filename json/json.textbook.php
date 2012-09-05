<?php
	define("RELurl", '../');
	
	if(isset($_GET['tb'])) { $tb = $_GET['tb']; } else { $tb = 0; }
	
	//Le cache
	require_once(RELurl.'/inc/inc.function.cache.future.php');
	$cache = new autocache("json", "text", $tb);
	//Fin du cache
	
	require_once(RELurl.'inc/inc.conn.php');
	require_once(RELurl.'inc/inc.func.lang.php');
	require_once(RELurl.'inc/inc.class.text.php');
	
	if(isset($_GET['tb'])) //Si on a un auteur
	{
		$options = "WHERE book='".$_GET['tb']."'";
	}
	else
	{
		$options = "";
	}
	if(!is_string($connectBDD))
	{
		//Normalement on reçoit $_POST qu'on transforme en data		
		$results = array();
		$row_array = array();
		$text = new text;
		
		if(isset($_GET['tb'])) //Si on a un auteur
		{
			$b_a = $connectBDD->prepare("SELECT tb.text as tbid, tb.chapter as tbchapter, tb.sschapter as tbsschapter, book.name as bname, book.id as bid, a.name as aname, a.lage as alage FROM text_book tb, book, author a WHERE tb.book= ? AND book.id=tb.book AND a.id=book.author ORDER BY chapter, sschapter ASC LIMIT 1");
			$b_a->execute(array($_GET['tb']));
			
			$m_q = $connectBDD->prepare("SELECT tb.chapter as tbchapter, tb.sschapter as tbsschapter FROM text_book tb WHERE tb.book= ? ORDER BY chapter, sschapter ASC");
			//On donne l'unique info liant tout ça
			$m_q->execute(array($_GET['tb']));
		}
		else
		{
			$m_q=$connectBDD->prepare("SELECT * FROM text_book ORDER BY chapter, sschapter ASC");
			$m_q->execute();
		}
		
		if($m_q->rowCount() > 0) // Si true, trop tard ! On récupère donc l'id
		{
			$text = $b_a->fetch(PDO::FETCH_OBJ);
			while($man = $m_q->fetch(PDO::FETCH_OBJ))
			{
				if(!isset($chap) || ($chap != $man->tbchapter))// Au premier définition on définit un chapitre
				{
					$chap = $man->tbchapter;
					$row_array['Value'] = $man->tbchapter;  
					$row_array['chap'] = '1';
					$row_array['Text'] = $text->bname . ' '.$man->tbchapter;  
					array_push($results, $row_array); 
				}
				$row_array['Value'] = $man->tbchapter."-".$man->tbsschapter;  
				$row_array['chap'] = '0';
				$row_array['Text'] = $man->tbchapter. ', '.$man->tbsschapter;  
				//Version par UID $row_array['Link'] = "./read/".urlencode($text->alage)."/".urlencode($text->aname)."/".urlencode($text->bname)."/".$man->tbid.".html";
				$row_array['Link'] = "./read/".urlencode($text->alage)."/".urlencode($text->aname)."/".urlencode($text->bname)."/".$text->bid."/Chapter-".$man->tbchapter."/Section-".$man->tbsschapter.".html";
				array_push($results, $row_array);  
			}
		}
		
		//On propose le nouveau

		//On propose le nouveau
		//if(sessionBolean() == true)
		//{
		$row_array['Value'] = 0;  
		$row_array['Text'] = 'Nouveau';  
		array_push($results, $row_array);
		//}
		
		if(isset($results)) { echo json_encode($results); }
	}
	else
	{
		exit($connectBDD);
	}
?>