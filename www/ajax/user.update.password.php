<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.function.security.php');
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_POST['realpwd']) && ($_SESSION['uid']))
	{
		$erreur = "";
		$champs = array("Mot de passe original" => "realpwd", "Mot de passe" => "pwd", "Vérification mot de passe" => "pwd2");
		foreach($champs as $key => $value)
		{
			if(!isset($_POST[$value]) ||( $_POST[$value]==''))
			{
				$erreur = "Le champs ".$key." est vide.";
				break;
			}
			elseif(($value == "pwd2") && ($_POST['pwd'] != $_POST['pwd2']))
			{
				$erreur = "Les mots de passes tapés sont différents.";
				break;
			}
		}
		//Vérification du mot de passe
		$query = $connectBDD->prepare("SELECT id FROM users WHERE id= ? AND pwd = ? LIMIT 1");
		$query->execute(array($_SESSION['uid'], sha256($_POST['realpwd'])));
		if($query->rowCount() != 1)
		{
			$erreur = "Votre mot de passe est erroné.";
		}
		//Enregistrement
		if($erreur == "")
		{
			$reg_user = $connectBDD->prepare("UPDATE users SET pwd = ? WHERE id= ? LIMIT 1");
			$reg_user->execute(array(sha256($_POST['pwd']), $_SESSION['uid']));
			if($reg_user->rowCount() ==1)
			{
				echo '<div class="success">Votre mot de passe a été modifié !</div><br />';
			}
			else
			{
				echo '<div class="error">Erreur système lors de l\'enregistrement</div>';
			}
		}
		else
		{
			echo '<div class="error">'.$erreur.'</div>';
		}
	}
?>