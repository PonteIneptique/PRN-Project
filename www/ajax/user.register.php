<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.function.security.php');
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_POST['name']))
	{
		$erreur = "";
		$champs = array("Identifiant" => "login", "Adresse email" => "email", "Mot de passe" => "pwd", "Vérification mot de passe" => "pwd2", "Nom" => "name", "Université" => "university", "Langue maternelle" => "lage");
		foreach($champs as $key => $value)
		{
			if(!isset($_POST[$value]) ||( $_POST[$value]==''))
			{
				die(error(6, $key));
			}
			elseif(($value == "pwd2") && ($_POST['pwd'] != $_POST['pwd2']))
			{
				die(error(7));
			}
			elseif((($value == "login") || ($value == "email") || ($value == "name")) && (checkUser($value, $_POST) == 'false'))
			{
				die(error(8, $key . " (".$_POST[$value].")"));
			}
			elseif(($value == "lage") && (strlen($_POST["lage"] ) < 2))
			{
				die(error(6, $key));
			}
		}
		//On a die si on a eu une erreur
		$k = microtime();
		$k = md5($_POST['pwd']).$k.$_POST['login'];
		$k = sha256($k);
		$k = substr($k, 12, 24);
		//On fait l'insert
		$reg_user = $connectBDD->prepare("INSERT INTO users (id, login, pwd, name, signup, verif_key, email, university, lage) VALUES ('', ? , ? , ? , ? , ? , ? , ? , ?)");
		$reg_user->execute(array($_POST['login'], sha256($_POST['pwd']), $_POST['name'], date("y-m-d"), $k, $_POST['email'], $_POST['university'], $_POST['lage']));
		//$count = $connectBDD->exec("INSERT INTO users (id, login, pwd, name, signup, verif_key, email, university) VALUES ('', '".$_POST['login']."', '".sha256($_POST['pwd'])."', '".$_POST['name']."', '".date("y-m-d")."', '".$k."', '".$_POST['email']."', '".$_POST['university']."')");
		if($reg_user->rowCount() ==1)
		{
			success('Compte enregistré. <br />Vous recevrez un email pour confirmer votre compte.');
		//Action EMAIL
			mail($_POST['email'], "Bienvenue sur PRN, ".$_POST['name'], "Bonjour, pour valider votre mail : <a href='http://www.algorythme.net/beta/user/validation/".$k."/'>Cliquez-ici");
		}
		else
		{
			error(0);
		}
	}
?>