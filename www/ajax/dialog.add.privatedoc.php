<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(!is_string($connectBDD))
	{
		if(isset($_POST['title']) && isset($_POST['text']) && (sessionBolean()) )
		{
			//On check d'abord qu'on ne retrouve pas la même chose
			$array = array($_POST['title'], $_POST['text'], $_SESSION['uid']); //Par chance, ce sera le même array les deux fois
			
			$verif = $connectBDD->prepare("SELECT id FROM social_doc_src WHERE titre = ? AND src = ? AND owner = ? ");
			$verif->execute($array);
			if($verif->rowCount() == 1)
			{
				error(2);
			}
			else
			{		
				$insert = $connectBDD->prepare("INSERT INTO social_doc_src (id, titre, src, owner) VALUES ('', ? , ? , ? )");
				$insert->execute($array);
				if($insert->rowCount() == 1)
				{
					if(isset($_POST['shared']) && (count($_POST['shared']) >= 1))
					{
						$doc = $connectBDD->lastInsertId();
						$share = $connectBDD->prepare("INSERT INTO social_doc_share (id, doc, user) VALUES ('', ? , ? )");
						foreach($_POST['shared'] as $key => $value)
						{
							if(UserExists($value) == true)
							{
								$share->execute(array($doc, $value));
								$success = 1;
							}
						}
						if(isset($success)) { success(0); }
						else { success(0, 'Partages non-enregistrés'); }
					}
					else
					{
						success(0);
					}
				}
				else
				{
					error(0);
				}
			}
		}
		else
		{
			error(1);
		}
	}
?>