<?php
//Login Module
$connexionDialog = '';
if(isset($_POST['login']) && ($_POST['login'] != '') && isset($_POST['pwd']) && ($_POST['pwd'] != ''))
{
	$connectUSER = $connectBDD->prepare("SELECT id as uid, name as username, lage FROM users WHERE login= ? AND pwd= ? AND verif_key='0' LIMIT 1");
	$connectUSER->execute(array($_POST['login'], sha256($_POST['pwd'])));
	if($connectUSER)
	{
		$usr = $connectUSER->fetch(PDO::FETCH_OBJ);
		if($usr)
		{
			$_SESSION['uid'] = $usr->uid;
			$_SESSION['username'] = $usr->username;
			$_SESSION['lage'] = $usr->lage;
			$connexionDialog = '<div class="success">Bonjour '.$usr->username.'<br />Vous êtes désormais connecté-e.</div>';
		}
		else
		{
			$connexionDialog = '<div class="error">Votre identifiant et votre mot de passe ne correspondent à aucun utilisateur. Veuillez réessayer.</div>';
		}
	}
	else
	{
		$connexionDialog = '<div class="error">Erreur serveur.</div>';
	}
}
?>