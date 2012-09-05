<?php
	define("RELurl", '../');
	//Le cache : appel de la page de classe
	require_once(RELurl.'inc/inc.class.cache.php');	
	
	//On définit un array des possibilités de vote
	$table = array(
		1 => "trad_text",
		2 => "text_note",
		3 => "social_doc_note",
		1 => "social_doc_trad_text",
	);

	//On crée les fonctions
	function save_vote($vote, $table, $item) {
		global $connectBDD;
		$query = $connectBDD->prepare("SELECT id FROM rate WHERE `table` = ? AND `item` = ? AND user = ? LIMIT 1");
		$query->execute(array($table, $item, $_SESSION['uid']));
		if($query->rowCount() == 0) // Si on a aucun enregistrement pour ces données pour l'utilisateur, on insert le vote
		{
			//On prépare la requete de sauvegarde
			$ins = $connectBDD->prepare("INSERT INTO rate (`id`, `table`, `item`, `user`, `note`) VALUES ('', ?, ?, ?, ?)");
			$ins->execute(array($table, $item, $_SESSION['uid'], $vote));
			//On l'execute
		}
		else // Sinon on l'up
		{
			//On prépare la requete de sauvegarde
			$ins = $connectBDD->prepare("UPDATE rate SET note= ? WHERE `table`= ? AND `item`= ? AND `user`= ? ");
			$ins->execute(array($vote, $table, $item, $_SESSION['uid']));
			//On l'execute
		}
	}
	function get_options() {
		return array(
			1 => 'Not so great',
			2 => 'Quite good',
			3 => 'Good',
			4 => 'Great!',
			5 => 'Excellent!',
		);
	}
	
	function in_range($val, $from=0, $to=100) {
		return min($to, max($from, (int)$val));
	}
	
	function get_votes($table, $item) {
		global $connectBDD;
		$query = $connectBDD->prepare("SELECT AVG(note) AS Moy, SUM(note) AS Som, COUNT(note) as Vo FROM `rate` WHERE `table` = ? AND `item` = ? ");
		$query->execute(array($table, $item));
		if($query->rowCount() >= 1)
		{
			$d = $query->fetch(PDO::FETCH_OBJ);
			return array('votes' => (int) $d->Vo, 'sum' => (int) $d->Som, 'avg' => (int) round($d->Moy));
		}
		else
		{
			return array('votes' => 0, 'sum' => 0, 'avg' => 0);
		}
	}
	
	function print_vote($table, $item) {
		$db = get_votes($table, $item);
		echo json_encode($db);
	}
	
	require_once(RELurl.'inc/inc.conn.php');
	//Si on a un vote, on ne lance pas le cache tout de suite
	if(isset($_GET['rate']) && isset($_GET['t']) && isset($_GET['i']) && (sessionBolean() == true))
	{
		//On appelle inc.conn.php
		//Si et seulement si on est connecté
			//On  int les $_GET
			$_GET['t'] = (int) $_GET['t'];
			$_GET['i'] = (int) $_GET['i'];
			//On crée le vote
			$vote = in_range($_GET['rate'],0,5);
			save_vote($vote, $table[$_GET['t']], $_GET['i']);
			
			//On crée le cache
			
				//On crée le reload
				$cache = new start_cache("json.vote", "rate-".$_GET['t'], $_GET['i'], true);//Pagename, Table, Item, clearcache
				//On imprime les votes
				print_vote($table[$_GET['t']], $_GET['i']);
				//Fin du cache
	}
	elseif(isset($_GET['t']) && isset($_GET['i']))
	{
		//On assure nos arrières
		$_GET['t'] = (int) $_GET['t'];
		$_GET['i'] = (int) $_GET['i'];		//On lance d'abord le cache
		$cache = new start_cache("json.vote", "rate-".$_GET['t'], $_GET['i']);//Pagename, Table, Item, clearcache
	
		//On a 
		// $_GET -> i => L'id de l'item
		// $_GET -> t => L'id de la table de l'item
		// $_POS -> vote => le vote reçu
		
		//On appelle inc.conn.php
		require_once(RELurl.'inc/inc.conn.php');
		
		//On vérifie qu'on a une connexion
		if(!is_string($connectBDD))
		{
			
				//Action si on a un vote
				if(isset($_GET['rate']) && (sessionBolean() == true))
				{
					$vote = in_range($_GET['rate'],0,5);
					save_vote($vote, $table[$_GET['t']], $_GET['i']);
				}
				
				//De toute façon affiche la note qui potentiellement sera mise en cache et détruite de temps en temps
				print_vote($table[$_GET['t']], $_GET['i']);
		}
	}
?>