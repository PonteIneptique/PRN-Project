<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_GET['term']))
	{
		$row = array();
		$sql = $connectBDD->prepare("SELECT id, name FROM keyword_name WHERE name LIKE ? LIMIT 10");
		$sql->execute(array('%'.$_GET['term'].'%'));
		while($data = $sql->fetch(PDO::FETCH_OBJ))
		{
			$row[] = array("value" => $data->name, "id" => $data->id);
		}
		$row[] = array("value" => "Nouveau", "id" => 0);
		echo json_encode($row);
	}
?>