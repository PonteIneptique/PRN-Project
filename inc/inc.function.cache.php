<?php
	//Message type généré : 11/12/2011 17:05:57 -> STOP:  cache ended for file /home/lp-efficient/projetsThibault/PRN/cache-page//cache-dialog.trad.note-trad_part-3(2046)
	function str2bin($str) {
		$out = false;
		for($a=0; $a < strlen($str); $a++) {
			$dec = ord(substr($str,$a,1));
			$bin = '';
			for($i=7; $i>=0; $i--) {
				if ( $dec >= pow(2, $i) ) {
					$bin .= "1";
					$dec -= pow(2, $i);
				} else {
					$bin .= "0";
				}
			}
			$out .= $bin;
		}
		return $out;
    }
	
	function cachelogging($msg, $type)
	{
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		$log_file="log.txt"; // append will be done with $cache_base
		$log = true;//Activation desactivation du log
		if($log)
		{
			// open file
			$fd = fopen($cache_base.$log_file, "a");
			// write string 18/01/2011 11:45:16 -> MYSQL: SELECT * FROM table   Cache cleared for file ".$cache_file."
			fwrite($fd, date("d/m/Y H:i:s")." -> ".$type.":  ".$msg." \n");
			// close file
			fclose($fd);
		}
	}
	
	//Vérification du cache du log
	function blockcacheexists($page, $table, $item)
	{
		if(!is_numeric($item)) { $item = str2bin($item); } 
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		
		//On fait un cache différent pour connecté ou non : -0- et -1-
		$page .= session2var("-1", "-0");
		//Refaire la définition de nom de fichier
		$requested_url = '-'.$page.'-'.$table.'-'.$item;// Nom du fichier Sans cache devant
		$cache_file=$cache_base."/cache".$requested_url;
		//Donc là on a récupéré nos données...
		#return file_exists($cache_file);
		return false;
	}
	
	function writeblockcache($page, $table, $item, $block)
	{
		if(!is_numeric($item)) { $item = str2bin($item); }
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		
		//On fait un cache différent pour connecté ou non : -0- et -1-
		$page .= session2var("-1", "-0");
		//Refaire la définition de nom de fichier
		$requested_url = '-'.$page.'-'.$table.'-'.$item;// Nom du fichier Sans cache devant
		$cache_file=$cache_base."/cache".$requested_url;
		//Donc là on a récupéré nos données...
		
		
		//On comme le logging de cache
		cachelogging(" cache started for file  ".$cache_file, "START");
		//Maintenant on écrit
		$fd = fopen($cache_file, "w"); // open cache file and clear it
		if ($fd) {
			fwrite($fd,$block); // Write cache file
			fclose($fd);
			cachelogging(" cache ended for file ".$cache_file."(".strlen($block).")", "STOP");
		}
	}
	
	function blockviewcache($page, $table, $item)
	{
		if(!is_numeric($item)) { $item = str2bin($item); }
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		
		//On fait un cache différent pour connecté ou non : -0- et -1-
		$page .= session2var("-1", "-0");
		//Refaire la définition de nom de fichier
		$requested_url = '-'.$page.'-'.$table.'-'.$item;// Nom du fichier Sans cache devant
		$cache_file=$cache_base."/cache".$requested_url;
		//Donc là on a récupéré nos données...
		
		//On affiche
		include($cache_file);
		
		//On log
		cachelogging($cache_file, "READ");
	}
	function delblockcache($page, $table, $item)
	{
		if(!is_numeric($item)) { $item = str2bin($item); }
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		
		for($i=0; $i <= 1; $i++)//Comme ça on traite le cache en bloc
		{
			//On fait un cache différent pour connecté ou non : -0- et -1-
			//$page .= "-".$i; -> Redirigée directement sinon page incrémentée deux fois
			//Refaire la définition de nom de fichier
			$requested_url = '-'.$page.'-'.$i.'-'.$table.'-'.$item;// Nom du fichier Sans cache devant
			$cache_file=$cache_base."/cache".$requested_url;
			//Donc là on a récupérer nos données...
			
			if(file_exists($cache_file))
			{
				if(!unlink($cache_file))
				{  // Si l'effacement du cache ne marche pas
					die('Erreur liée au cache'); 
				}
				else
				{
					cachelogging("Cache cleared for file ".$cache_file, "CLEAR");
				}
			}
		}
	}
	
	function delcache($page, $table, $item)
	{
		if(!is_numeric($item)) { $item = str2bin($item); }
		$cache_base= '/home/lp-efficient/projetsThibault/PRN/cache-page/'; // base path with / ending
		
		//Refaire la définition de nom de fichier
		$requested_url = '-'.$page.'-'.$table.'-'.$item;// Nom du fichier Sans cache devant
		$cache_file=$cache_base."/cache".$requested_url;
		//Donc là on a récupérer nos données...
		
		if(file_exists($cache_file))
		{
			if(!unlink($cache_file))
			{  // Si l'effacement du cache ne marche pas
				die('Erreur liée au cache'); 
			}
			else
			{
				cachelogging("Cache cleared for file ".$cache_file, "CLEAR");
			}
		}
	}
?>