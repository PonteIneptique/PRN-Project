<?php
	class OPSession{
		/**
		 * Fonction qui r�cup�re l'adresse IP du visiteur
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
		 * G�n�rer une nouvelle variable de session
		 * est utilis�e lors de la cr�ation ou lors d'une tentative de vol de session
		 */
		public static function NewSession(){
			//On copie la session dans une nouvelle session puis on vide cette nouvelle session
			session_regenerate_id();
			$_SESSION=array();
			
			//D�finition de l'adresse IP en session
			$_SESSION['AGENT']    = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['LANGUAGE'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			//$_SESSION['CHARSET']  = $_SERVER['HTTP_ACCEPT_CHARSET'];
			$_SESSION['ENCODING'] = $_SERVER['HTTP_ACCEPT_ENCODING'];
			$_SESSION['IP']       = self::IP();
		}
		
		/**
		 * Correspond � la fonction session_start() mais en version s�curis�e
		 */
		public static function Start(){
			if(!isset($_SESSION)) { session_start(); }
			
			if(!isset($_SESSION['IP'])){
				//Si la session n'a pas �t� initialis�e
				self::NewSession();
			}else{
				//V�rificationde l'adresse IP, de l'encodage accept�, des langues accept�s et du navigateur
				if(
					$_SESSION['AGENT'] !== $_SERVER['HTTP_USER_AGENT']
					|| $_SESSION['LANGUAGE'] !== $_SERVER['HTTP_ACCEPT_LANGUAGE']
					//|| $_SESSION['CHARSET'] !== $_SERVER['HTTP_ACCEPT_CHARSET']
					|| $_SESSION['ENCODING'] !== $_SERVER['HTTP_ACCEPT_ENCODING']
					|| $_SESSION['IP'] !== self::IP()
				){
					//Si une valeur n'est pas correcte, on �crase tout et on r��crit
					self::NewSession();
				}
			}
		}
	}
?>