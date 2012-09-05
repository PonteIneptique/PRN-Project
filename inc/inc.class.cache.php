<?php
if(defined("RELurl")) {	require_once(RELurl.'./inc/inc.traitement.post.get.php'); require_once(RELurl.'./inc/inc.class.OPSession.php'); OPSession::Start(); }
class start_cache {
		var $cache_base= '/home/lp-efficient/projetsThibault/PRN/cache/'; // base path with / ending
		var $log_file="log.txt"; // append will be done with $cache_base
		var $log_activated=true;
		
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
	function setenvcache($page, $table, $item) // On définit les variable de table, item
	{
		if(!is_numeric($item)) { $item = $this->str2bin($item); } 
		$this->page = $page;
		$this->table = $table;
		$this->item = $item;
	}

   function __construct($page, $table, $item, $clear = false) {
	  //On set les var d'enviro
	  $this->setenvcache($page, $table, $item);
	  //Refaire la définition de nom de fichier
	  $requested_url = '-'.$this->page.'-'.$this->table.'-'.$this->item;// Nom du fichier Sans cache devant
	  
	  //Le dossier de cache
      $this->cache_dir=$this->cache_base;
      $this->cache_file=$this->cache_base."/cache".$requested_url; // build full cache file path
	  
      if ($clear == true) { $this->clearcache(); }
	  
      #$this->cache_activated= !file_exists($this->cache_file) || isset($_GET['reload']) ; // Condition d'expiration... Nous c'est seulement si fileexists + $_get['reload']
	  $this->cache_activated = false;
      if ($this->cache_activated) {
         ob_start(); // start buffering for the page nothing is sent to the browser
         $this->logging(" cache started for file ".$this->cache_file, "start");
      } else { // file exist
         include ($this->cache_file); // on le copie ici
         $this->logging($this->cache_file , "read");
         die();
      }
   }
   
   // log event used like this -> argument : Message , [Group]
   // write string 18/01/2011 11:45:16 -> MYSQL: SELECT * FROM table
   function logging($msg, $group="DEFAULT")
   {
      if($this->log_activated) {
         // upper case
         $group=strtoupper ($group);
         // prepare message
         $msg= str_replace("\n"," ",$msg);
         $msg= str_replace("\r"," ",$msg);
         // open file
         $fd = fopen($this->cache_base.$this->log_file, "a");
         // write string 18/01/2011 11:45:16 -> MYSQL: SELECT * FROM table
         fwrite($fd, date("d/m/Y H:i:s")." -> ". $group. ": ".$msg . "\n");
         // close file
         fclose($fd);
      }
   }

	//Fonction obsolète pour un cache mis à jour par formulaire
	function clearcache()
	{		
		if(file_exists($this->cache_file))
		{
			if(!unlink($this->cache_file))
			{  // Si l'effacement du cache ne marche pas
				die('Erreur liée au cache'); 
			}
			else
			{
				$this->logging("Cache cleared for file ".$this->cache_file, "CLEAR");
			}
		}
	}
   // samll function to protect from script attack or SQL injections
   function escape_string($uvar){
   $uvar=preg_replace("/((\%3C)|<)[^\n]+((\%3E)|>)/","",$uvar); // Prevent script CSS attack
   return mysql_escape_string($uvar); // prevent mysql attack
   }
   
   function __destruct() {
      if ($this->cache_activated) {
          $contenuCache = ob_get_contents(); //get buffer
          ob_end_flush();// end buffer
         if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir); // create dir if needed
            $this->logging($this->cache_dir, "create_dir");
         }
         $fd = fopen($this->cache_file, "w"); // open cache file and clear it
         if ($fd) {
            fwrite($fd,$contenuCache); // Write cache file
            fclose($fd);
            $this->logging(" cache ended for file ".$this->cache_file."(".strlen($contenuCache).")", "stop");
         }
      }
   }

}
?>