<h3>Rechercher par mot-clef</h3>
<div class="well">
<?php
	$kql = $connectBDD->prepare("SELECT DISTINCT kn.id, kn.name FROM keyword_use ku, keyword_name kn WHERE ku.book= ? AND ku.kw=kn.id");
	$kql->execute(array($book->id));
	while($kw = $kql->fetch(PDO::FETCH_OBJ))
	{
		echo '<span class="label link label-info kw-filter text-kw-filter" data-active="0" data-target="text" data-src="'.$kw->id.'">'.$kw->name.'</span> ';
	}
	echo '<div style="margin:10px;"><span class="btn-small link btn-primary kw-filter-raz" data-target="text"> Supprimer les filtres</span></div>';
	echo '</div>';
	
	echo $minor;
?>