<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(!sessionBolean())
	{
		die(error(3));
	}
	if(!is_string($connectBDD))
	{
		$v_q = $connectBDD->prepare("SELECT * FROM author WHERE id= ? LIMIT 1");
		$v_q->execute(array($_POST['author']));
		if($v_q)
		{
			$a = $v_q->fetch(PDO::FETCH_OBJ);
			if($a)
			{
				$v_b = $connectBDD->prepare("SELECT * FROM book WHERE name= ? LIMIT 1");
				$v_b->execute(array($_POST['name']));
				if($v_b)
				{
					if($v_b->rowCount() == 0)
					{
						$insert = $connectBDD->prepare("INSERT INTO book (id, name, author) VALUES ('', ? , ? )");
						$insert->execute(array($_POST['name'], $a->id));
						if($insert->rowCount() == 1) { 
							success(0); $storeLogs->execute(array('book', $connectBDD->lastInsertId(), $_SESSION['uid']));
							$cache->delete("json", "book", $a->id);
							} else { error(0); }
					}
					else
					{
						error(5);
					}
				}
				else
				{
					error(0);
				}
			}
			else
			{
				error(10, 'L\'auteur');
			}
		}
		else
		{
			error(0);
		}
	}
?>