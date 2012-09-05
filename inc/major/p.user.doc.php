<?php if(sessionBolean()) { ?>
<h1>Mes documents privés :</h1>
<?php
	echo '<ul>';
	
	$docs = $connectBDD->prepare("SELECT * FROM social_doc_src WHERE owner = ? ");
	$docs->execute(array($_SESSION['uid']));
	if($docs->rowCount() >= 1)
	{
		echo '<li>Documents possédés</li><ul>';
		while($docdata = $docs->fetch(PDO::FETCH_OBJ))
		{
			echo '<li>'.$docdata->titre.'</li>';
		}
		echo '</ul>';
	}
	
	//On vide pour réutiliser les même noms
	unset($docs, $docdata);
	
	$docs = $connectBDD->prepare("SELECT sds.titre, sds.id as sid, u.name FROM social_doc_src sds, social_doc_share sdsh, users u  WHERE sds.id=sdsh.doc AND sdsh.user = ? AND u.id=sds.owner ");
	$docs->execute(array($_SESSION['uid']));
	if($docs->rowCount() >= 1)
	{
		echo '<li>Documents partagés</li><ul>';
		while($docdata = $docs->fetch(PDO::FETCH_OBJ))
		{
			echo '<li>'.$docdata->titre.' ('.$docdata->name.'</li>';
		}
		echo '</ul>';
	}
	
	echo '</ul>';
?>
<?php } ?>