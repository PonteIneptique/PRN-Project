<?php
	class OPSession{
		/**
		 * Fonction qui récupère l'adresse IP du visiteur
		 *
		 * @return String Adresse IP 
		 */
		public static function IP(){
			if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
			  if(strchr($_SERVER['HTTP_X_FORWARDED_FOR'],','))
			  {
				 $tab=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
				 $realip=$tab[0];
			  }
			  else
			  {
				 $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			  }
			}
			else{
				$realip=$_SERVER['REMOTE_ADDR'];
			}
			
			return $realip;
		}
		
		/**
		 * Générer une nouvelle variable de session
		 * est utilisée lors de la création ou lors d'une tentative de vol de session
		 */
		public static function NewSession(){
			//On copie la session dans une nouvelle session puis on vide cette nouvelle session
			session_regenerate_id();
			$_SESSION=array();
			
			//Définition de l'adresse IP en session
			$_SESSION['AGENT']    = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['LANGUAGE'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			//$_SESSION['CHARSET']  = $_SERVER['HTTP_ACCEPT_CHARSET'];
			$_SESSION['ENCODING'] = $_SERVER['HTTP_ACCEPT_ENCODING'];
			$_SESSION['IP']       = self::IP();
		}
		
		/**
		 * Correspond à la fonction session_start() mais en version sécurisée
		 */
		public static function Start(){
			if(!isset($_SESSION)) { session_start(); }
			
			if(!isset($_SESSION['IP'])){
				//Si la session n'a pas été initialisée
				self::NewSession();
			}else{
				//Vérificationde l'adresse IP, de l'encodage accepté, des langues acceptés et du navigateur
				if(
					$_SESSION['AGENT'] !== $_SERVER['HTTP_USER_AGENT']
					|| $_SESSION['LANGUAGE'] !== $_SERVER['HTTP_ACCEPT_LANGUAGE']
					//|| $_SESSION['CHARSET'] !== $_SERVER['HTTP_ACCEPT_CHARSET']
					|| $_SESSION['ENCODING'] !== $_SERVER['HTTP_ACCEPT_ENCODING']
					|| $_SESSION['IP'] !== self::IP()
				){
					//Si une valeur n'est pas correcte, on écrase tout et on réécrit
					self::NewSession();
				}
			}
		}
	}
?>