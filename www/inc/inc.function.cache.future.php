<?php
	//Message type généré : 11/12/2011 17:05:57 -> STOP:  cache ended for file C:/Program Files (x86)/EasyPHP-5.3.8.1/www/cache-page//cache-dialog.trad.note-trad_part-3(2046)
	class cache {
		var $base = '/home/lp-efficient/projetsThibault/PRN/cache/';
		var $log_file = "log.txt";
		var $log = true;
		
		/*
		Les caches sont divisés en 4 parties
			- Le type : json, fullpage, block[=Div], text[=Contenu Div]
			- La page référence : privatedoc, read, etc.
			- Un sous-élément : Peut-être un identifiant secondaire (langue) ou un élément pour identifier le block/text [traduction-area]
			- L'identifiant : id, langue
			- La créations de blocks évitent les problèmes de connected/non-connected
		*/
		function filename($type, $page, $id, $element = false)
		{
			if($element == false)
			{
				return $type."-".$page."-".$id.".cache";
			}
			else
			{
				$el = implode('-', $element);
				return $type."-".$page."-".$id."-".$el.".cache";
			}
		}
		function logging($type, $file, $debug = '')
		{
			if($this->log)
			{
				// open file
				$fd = fopen($this->base.$this->log_file, "a");
				$file = str_replace('.cache', '', str_replace('-', " \t ", $file));
				fwrite($fd, date("d/m/Y H:i:s")." \t ".$type." \t  ".$file." \n".$debug);
				// close file
				fclose($fd);
				return true;
			}
		}
		function exists($type, $page, $id, $element = false)
		{
			$filename = $this->filename($type, $page, $id, $element);
			$file = $this->base.$filename;
			#return file_exists($file);
			return false;
		}
		
		function text($type, $page, $id, $element = false)
		{
			$filename = $this->filename($type, $page, $id, $element);
			$file = $this->base.$filename;
			
        	ob_start();
        	include $file;
       	 	return ob_get_clean();
		
			//On comme le logging de cache
			$this->logging("READ", $filename);
		}
		function write($text, $type, $page, $id, $element = false)
		{
			$filename = $this->filename($type, $page, $id, $element);
			$file = $this->base.$filename;
		
			//On comme le logging de cache
			$this->logging("START", $filename);
			
			//Maintenant on écrit
			$fd = fopen($file, "w");
			
			if ($fd) {
				fwrite($fd,$text); // Write cache file
				fclose($fd);
				$this->logging("STOP", $filename);
			}
		}
		function read($type, $page, $id, $element = false)
		{
			//On source
			$filename = $this->filename($type, $page, $id, $element);
			$file = $this->base.$filename;
			//On inclut
			include($file);
			//On log
			$this->logging("READ", $filename);
		}
		function del($filename)
		{
			$file = $this->base.$filename;
			if(file_exists($file))
			{
				if(!unlink($file))
				{  // Si l'effacement du cache ne marche pas
					$this->logging("ERROR", $filename);
					die('Erreur liée au cache'); 
				}
				else
				{
					$this->logging("CLEAR", $filename);
				}
			}
		}
		function delete($type, $page, $id, $element = false)
		{
			//On source
			$filename = $this->filename($type, $page, $id, $element);
			$file = $this->base.$filename;
			
			//Alors le del doit être intelligent
			//D'abord, si on a un block, on doit vérifier si y a une full page 
			//De même dans l'autre sens
			//Cela permet de faire des blocks pour des éléments qui ne changent pas
			if($type == "block")
			{
				$d = dir($this->base);
				while($entry = $d->read()) {
					$split = explode('-', $entry);
					if(($split[0] == "fullpage") && ($split[1] == $page) && ($split[2] == $id) && ($split[3] == "traduction"))
					{
						$this->del($entry);
					}
				}
				$d->close(); 
			}
			elseif($type == "json")
			{
				$masterjson = $this->filename($type, $page, 0);
				$this->del($masterjson);
			}
			$this->del($filename);
		}
	}
class autocache extends cache {
	var $name, $url, $on;
	
   function start() {
		ob_start(); // start buffering for the page nothing is sent to the browser
		$this->logging("START", $this->name);
		$this->on = true;
	}
	
	function end() {
		$contenuCache = ob_get_flush();
		$fd = fopen($this->url, "w");
		if($fd)
		{
			fwrite($fd, $contenuCache);
			fclose($fd);
			$this->logging("STOP", $this->name, $contenuCache);
			//echo $contenuCache;
		}
		ob_end_flush();
	}
	
	function __construct($type, $page, $id, $element = false) {
		$this->name = $this->filename($type, $page, $id, $element);
		$this->url = $this->base.$this->name;
		if($this->exists($type, $page, $id, $element))
		{
        	include ($this->url); // on le copie ici
        	$this->logging("READ", $this->name);
        	die();
			$this->on = false;
		}
		else
		{
			$this->start();
		}
   }
   function __destruct() {
	   if($this->on == true)
	   {
			$this->end();
			exit();
		}
   }
}
?>