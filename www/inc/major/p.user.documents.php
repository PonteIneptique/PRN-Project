<?php if(sessionBolean()) { ?>
<h1>Mes documents privés :</h1>
<?php
	echo '<ul class="unstyled">';
	
	$docs = $connectBDD->prepare("SELECT * FROM social_doc_src WHERE owner = ? ");
	$docs->execute(array($_SESSION['uid']));
	if($docs->rowCount() >= 1)
	{
		echo '<li class="nav-parent link" hierarchie="doc-possede"><i class="icon-folder-open"></i> Documents possédés ('.$docs->rowCount().')</li><ul class="unstyled" style="margin-left:10px;">';
		while($docdata = $docs->fetch(PDO::FETCH_OBJ))
		{
			echo '<li class="nav-children parent-doc-possede"><a href="/document/'.$docdata->id.'/'.$docdata->titre.'.html"><i class="icon-file"></i> '.$docdata->titre.'</a></li>';
		}
		echo '</ul>';
	}
	
	//On vide pour réutiliser les même noms
	unset($docs, $docdata);
	
	$docs = $connectBDD->prepare("SELECT sds.titre, sds.id as sid, u.name FROM social_doc_src sds, social_doc_share sdsh, users u  WHERE sds.id=sdsh.doc AND sdsh.user = ? AND u.id=sds.owner ");
	$docs->execute(array($_SESSION['uid']));
	if($docs->rowCount() >= 1)
	{
		echo '<li class="nav-parent link" hierarchie="doc-shared"><i class="icon-folder-open"></i> Documents partagés ('.$docs->rowCount().')</li><ul class="unstyled" style="margin-left:10px;">';
		while($docdata = $docs->fetch(PDO::FETCH_OBJ))
		{
			echo '<li class="nav-children parent-doc-shared"><a href="/document/'.$docdata->sid.'/'.$docdata->titre.'.html"><i class="icon-file"></i> '.$docdata->titre.' ('.$docdata->name.')</a></li>';
		}
		echo '</ul>';
	}
	
	echo '</ul>';
?>
<?php } ?>