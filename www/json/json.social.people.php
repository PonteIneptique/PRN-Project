<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.class.cache.php');
	
	require_once(RELurl.'inc/inc.conn.php');
	if(!is_string($connectBDD) && (socialBolean()))
	{
		if(isset($_GET['myfriend']))//Si on cherche les amis en particuliers
		{
			//$cache = new start_cache("json.social.people", "friends", $_SESSION['uid']);//Pagename, Table, Item
			$req = "SELECT u.name as username, un.name as university, u.id FROM users u, social_users su, university un WHERE u.id=su.user AND u.university=un.id AND su.status=1 AND (su.user IN (SELECT user FROM social_link sl WHERE sl.friend= ? ))"; 
			$array = array($_SESSION['uid']);
			$key1 = "Text";
			$key2 = "Value";
			//
		}
		else
		{
			$term = '%'.$_GET['term'].'%';//Truc envoyé par autocomplete
			$req = "SELECT u.name as username, un.name as university, u.id FROM users u, social_users su, university un WHERE u.id=su.user AND u.university=un.id AND su.status=1 AND (u.name LIKE ? OR u.email LIKE ?)";
			$array = array($term, $term);
			$key1 = "value";
			$key2 = "id";
		} 
		$row_set = array();
		$row2 = array();
		
		$query = $connectBDD->prepare($req);
		$query->execute($array);
		if($query->rowCount() > 0)
		{
			while ($row = $query->fetch(PDO::FETCH_OBJ))//loop through the retrieved values
			{
					$row2[$key1]= $row->username . " (".$row->university.")";
					$row2[$key2]= (int) $row->id;
					$row_set[] = $row2;//build an array
			}
		}
		
		echo json_encode($row_set);//format the array into json data
	}
?>