<?php
if(isset($_GET['verif_key']))
{
	$v_k = $connectBDD->prepare("SELECT id, name FROM users WHERE verif_key= ? LIMIT 1");
	$v_k->execute(array($_GET['verif_key']));
	if($v_k)
	{
		$k_info = $v_k->fetch(PDO::FETCH_OBJ);
		if($k_info)
		{
			$up_key = $connectBDD->prepare("UPDATE users SET verif_key='0' WHERE id= ? LIMIT 1");
			$up_key->execute(array($k_info->id));
			if($up_key->rowCount() == 1)
			{
			echo '<div class="success">Votre compte a été validé. <br />Vous pouvez désormais vous connecter. Bienvenue '.$k_info->name.'.</div>';
			}
			else
			{
				echo '<div class="error">Erreur serveur.</div>';
			}
		}
		else
		{
			echo '<div class="error">Cette clé ne correspond à aucun compte</div>';
		}
	}
}
?>