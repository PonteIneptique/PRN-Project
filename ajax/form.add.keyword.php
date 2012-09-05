<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(!is_string($connectBDD) && (socialBolean()))
	{
		if(is_array($_POST['kw']) && isset($_POST['text']))
		{
			$kw = array();
			$sql = $connectBDD->prepare("SELECT id FROM keyword_name WHERE id = ? AND id NOT IN (SELECT kw FROM keyword_use WHERE text = ?)");
			$sql2 = $connectBDD->prepare("SELECT name FROM keyword_name WHERE name = ?");
			$sql3 = $connectBDD->prepare("INSERT INTO keyword_name (id, name) VALUES ('', ?)");
			
			foreach($_POST['kw'] as $key => $value)
			{
				if(is_numeric($value))
				{
					$sql->execute(array($value, $_POST['text']));
					if($sql->rowCount() == 1)
					{
						$data = $sql->fetch(PDO::FETCH_OBJ);
						$kw[] = $data->id;
						unset($data);
					}
					echo 'a';
				}
				else
				{
					echo 'n';
					$sql2->execute(array($value));
					if($sql2->rowCount() == 0)
					{
						$sql3->execute(array($value));
						$kw[] = $connectBDD->lastInsertId();
					}
				}
			}
			unset($sql, $sql2, $sql3);
			if(count($kw) > 0)
			{
				#GET INFOS FROM TEXT
				$sql = $connectBDD->prepare("SELECT text, book, chapter, author, lage FROM text_book WHERE text = ? ");
				$sql->execute(array($_POST['text']));
				if($sql->rowCount() == 1)
				{
					#GET DATA
					$data = $sql->fetch(PDO::FETCH_OBJ);
					#DATA SET FOR INSERT
					$text = $data->text;
					$book = $data->book;
					$chapter = $data->chapter;
					$author = $data->author;
					$lage = $data->lage;
				}
				else
				{
					die(error(0));
				}
				unset($sql, $data);
				#INSERT
				$sql = $connectBDD->prepare("INSERT INTO keyword_use (id, lage, author, book, chapter, text, kw) VALUES ('', ?, ?, ?, ?, ?, ?)");
				foreach($kw as $key => $value)
				{
					$sql->execute(array($lage, $author, $book, $chapter, $text, $value));
				}
				if($sql->rowCount() > 0)
				{
					echo success(0);
				}
			}
			else
			{
			echo 'z';
					die(error(0));
			}
		}
	}
?>