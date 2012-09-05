<?php
	if(isset($_POST['betatitle']) && isset($_POST['betacontent']))
	{
		$sql = $connectBDD->prepare("SELECT * FROM debug WHERE title= ? AND content= ? ORDER BY id DESC");
		$sql->execute(array($_POST['betatitle'], $_POST['betacontent']));
		if($sql->rowCount() == 0)
		{
			$sql = $connectBDD->prepare('INSERT INTO `debug` (id, title, content, user, state) VALUES ("", ? , ? , ? , ?)');
			$sql->execute(array($_POST['betatitle'], $_POST['betacontent'], $_SESSION['uid'], 0));
			success(0);
		}
	}
?>
<h1>Liste des bugs soumis</h1>
<table class="table table-striped">
	<thead>
    	<tr><th>Id</th><th>Titre - Page</th><th>Description</th><th>Etat</th></tr>
    </thead>
    <tbody>
    <?php
		$sql = $connectBDD->prepare("SELECT * FROM debug ORDER BY id DESC");
		$sql->execute();
		while($data = $sql->fetch(PDO::FETCH_OBJ))
		{
			$st = array("Non traité", "En traitement", "Reporté", "Traité");
			$span = array("label", "label label-info", "label label-important", "label label-success");
			echo '<tr><td>'.$data->id.'</td><td>'.$data->title.'</td><td>'.$data->content.'</td><td><span class="'.$span[$data->state].'">'.$st[$data->state].'</span></td></tr>';
		}
	?>
    </tbody>
</table>