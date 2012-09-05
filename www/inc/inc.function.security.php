<?php
	if(function_exists("sha256") == false)
	{
		function sha256($key)
		{
			return hash('sha256', $key);
		}
	}
	function error($error, $field = "")
	{
		switch($error)
		{
			case 0:
				$error = "Erreur système";
				break;
			case 1:
				$error = "Certaines données sont manquantes";
				break;
			case 2:
				$error = "Ce document privé existe déjà";
				break;
			case 3:
				$error = "Accès refusé";
				break;
			case 4:
				$error = "Manuscrit inconnu";
				break;
			case 5:
				$error = "Cet enregistrement existe déjà";
				break;
			case 6:
				$error = "Le champs ".$field." est vide ou incorrect.";
				break;
			case 7:
				$error = "Les mots de passe sont différents.";
				break;
			case 8:
				$error = "Une inscription pour ".$field." existe déjà.";
				break;
			case 9:
				$error = "Le document n'existe pas.";
				break;
			case 10:
				$error = $field." n'existe pas.";
			default:
				$error = $error;
				break;
		}

		echo '<div class="alert alert-error">'.$error.'</div>';

	}
	function success($success, $text = "")
	{
		switch($success)
		{
			case 0:
				$success = "Enregistrement effectué";
				break;
			default:
				$success = $success;
				break;
		}
		if($text != '') { $text = ' ('.$text.')'; }
		echo '<div class="alert alert-success">'.$success.$text.'</div>';
	}
?>