<?php
	if(!defined('RELurl')) { exit(); }
	
	require_once(RELurl.'./inc/inc.class.OPSession.php');
	OPSession::Start();
	//On balance ça en premier au cas où
	require_once(RELurl.'./inc/inc.traitement.post.get.php');
	
	/*Appels aux fonctions nécessaires*/
	require_once(RELurl.'inc/inc.function.security.php');
	require_once(RELurl.'inc/inc.function.user.php');
	require_once(RELurl.'inc/inc.function.session.php');
	require_once(RELurl.'inc/inc.function.social.php');
	require_once(RELurl.'inc/inc.function.cache.php');
	require_once(RELurl.'inc/inc.function.cache.future.php');
	require_once(RELurl.'inc/inc.function.text.php');
	require_once(RELurl.'inc/inc.func.lang.php');
	
	require_once(RELurl.'inc/inc.class.text.php');
	require_once(RELurl.'inc/inc.class.document.php');
	
	
	function ConnexionBDD($serverPath, $port, $user, $pass, $base)
	{
		$PARAM_hote=$serverPath; // le chemin vers le serveur
		$PARAM_port=$port;
		$PARAM_nom_bd=$base; // le nom de votre base de données
		$PARAM_utilisateur=$user; // nom d'utilisateur pour se connecter
		$PARAM_mot_passe=$pass; // mot de passe de l'utilisateur pour se connecter
		
		try
		{
			$connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd,
			$PARAM_utilisateur,
			$PARAM_mot_passe);
			return $connexion;
		}
		catch(Exception $e)
		{
			$erreure =  'Erreur : '.$e->getMessage().'<br />';
			$erreure .= 'N° : '.$e->getCode();
			return $erreure;
		}
	}
	
	$connectBDD = ConnexionBDD('localhost', '3306', 'USER', 'PASSWORD', 'DATABASE');
	if(!is_string($connectBDD))
	{		
		$result=$connectBDD->query("SET NAMES 'utf8'");
	}
	else
	{
		exit('Site hors service');
	}
	
	require_once(RELurl.'inc/inc.function.logs.php');
	if(!isset($cache)) { $cache = new cache(); }
?>