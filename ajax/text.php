<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	//text.php?i='.$_POST['id'].'&type='.$page.'&el=text//
	//	-> &eli=5 pour l'id de trad
	
	$db = "text_";
	
	$page = "read";
	if(isset($_GET['type']) && ($_GET['type'] == "document"))
	{
		$document = new document($_GET['i'], false);
		if($document->access == false) { exit( error(3)); }
		else { $db = "social_doc_"; $page = "document"; }
	}
	elseif(!sessionBolean())
	{
		exit(error(3));
	}
	
	//On définit les options de text_annote et du cache
	if($_GET['el'] == "text")
	{
		//Pour le cache
		$array = array("text");
		//Pour l'annoté
		$class = "epi-note";
		$table2 = "src";
		$row1 = "text";
		$row2 = "text";//Pour le sd -> src
		if($_GET['type'] == 'document') { $row2 = "src"; }
		$mainid= $_GET['i'];
		$docid = $_GET['i'];
	}
	else
	{
		//Pour le cache
		$array = array("traduction", $_GET['eli']);
		
		if($_GET['type'] == "document") { $db.= "trad_"; } else {  $db = "trad_"; }
		
		//Pour l'annoté
		$table2 = "text";
		$class = "epi-comment";
		$row1 = "trad";
		$row2 = "trad";
		$mainid= $_GET['eli'];
		$docid = $_GET['i'];
	}
	
	//On passe à l'impression
	if($cache->exists("block", $page, $docid, $array))
	{
		$tradtext = $cache->text("block", $page, $docid, $array);
	}
	else
	{
		//On récupère le texte annoté
		$tradtext = text_annote(array($mainid,  $db."part", $row1, $class, $db.$table2, $row2));
		$cache->write($tradtext, "block", $page, $docid, $array);
	}
	echo $tradtext;
?>