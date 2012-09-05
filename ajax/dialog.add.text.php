<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	
	if(!is_string($connectBDD) && sessionBolean())
	{		
		$v_q = $connectBDD->prepare("SELECT * FROM author WHERE id= ? LIMIT 1");
		$v_q->execute(array($_POST['author']));
		if($v_q->rowCount() == 1)
		{
			$a = $v_q->fetch(PDO::FETCH_OBJ);
			if($a)
			{
				$v_b = $connectBDD->prepare("SELECT * FROM book WHERE id= ? AND author= ? LIMIT 1");
				$v_b->execute(array($_POST['book'], $_POST['author']));
				if($v_b->rowCount() == 1)
				{
					$b = $v_b->fetch(PDO::FETCH_OBJ);
					if($b)
					{
						$v_bt = $connectBDD->prepare("SELECT * FROM text_book WHERE book= ? AND chapter= ? AND sschapter= ? LIMIT 1") or exit('1');
						$v_bt->execute(array($b->id, $_POST['chapter'], $_POST['sschapter']));
						$v_t = $connectBDD->prepare("SELECT * FROM text_src WHERE text= ? LIMIT 1") or  exit('2');
						$v_t->execute(array($_POST['text']));
						if(($v_bt) && ($v_t))
						{
							if(($v_bt->rowCount() == 0) && ($v_t->rowCount() == 0))
							{
								$insert = $connectBDD->prepare("INSERT INTO text_src (id, text, author) VALUES ('', ? , ? )");
								$insert->execute(array($_POST['text'], $a->id));
								if($insert->rowCount() == 1)
								{
									$id = $connectBDD->lastInsertId();
									$storeLogs->execute(array('text_src', $id, $_SESSION['uid']));
									$insert2 = $connectBDD->prepare("INSERT INTO text_book (id, author, lage, book, text, chapter, sschapter) VALUES ('', ? , ? , ? , ? , ? , ?)");
									$insert2->execute(array($a->id, $a->lage, $b->id, $id, $_POST['chapter'], $_POST['sschapter']));
									if($insert2->rowCount() == 1) { success(0); $storeLogs->execute(array('text_book', $connectBDD->lastInsertId(), $_SESSION['uid'])); $cache->delete("json", "text", $b->id); } else { error(0); }
								}
								else
								{
									error(0);
								}
							}
							else
							{
								error(0);
							}
						}
						else
						{
							error(0);
						}
					}
					else
					{
						error(10, "Le livre");
					}
				}
				else
				{
					error(0);
				}
			}
			else
			{
				error(10, "L'auteur");
			}
		}
		else
		{
			error(0);
		}
	}
?>