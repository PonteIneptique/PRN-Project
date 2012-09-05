<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.function.security.php');
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_POST['university']) && ($_SESSION['uid']))
	{
		$erreur = "";
		$champs = array("Université" => "university", "Langue maternelle" => "lage");
		foreach($champs as $key => $value)
		{
			if(!isset($_POST[$value]) ||( $_POST[$value]==''))
			{
				$erreur = "Le champs ".$key." est vide.";
				break;
			}
			elseif(($value == "university") && ($_POST[$value] == 0))
			{
				$erreur = "Erreur sur l'université choisie.";
				break;
			}
			elseif(($value == "lage") && (strlen($_POST["lage"] ) < 2))
			{
				$erreur = "Erreur sur la langue sélectionnée.";
				break;
			}
		}
		//Enregistrement
		if($erreur == "")
		{
			$reg_user = $connectBDD->prepare("UPDATE users SET university = ? , lage = ? WHERE id= ? LIMIT 1");
			$reg_user->execute(array($_POST['university'], $_POST['lage'], $_SESSION['uid']));
			if($reg_user->rowCount() ==1)
			{
				echo '<div class="success">Vos informations ont été modifiées</div><br />';
				$_SESSION['lage'] = $_POST['lage'];
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