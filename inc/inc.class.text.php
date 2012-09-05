<?php
	class text {
		var $authorname;
		var $authorid;
		var $book;
		var $bookname;
		var $chapter;
		var $sschapter;
		var $text;
		var $textbook;//Lien entre toute les données qu'on obtient via text source
		var $textsrc;
		var $lage;
		var $sql;
		
		function getconnected()
		{
			$connectBDD = ConnexionBDD('localhost', '3306', 'epigrammata', 'TAtUUT', 'epigrammata');
			if(!is_string($connectBDD))
			{
				$result=$connectBDD->query("SET NAMES 'utf8'");
				$this->sql = $connectBDD;
			}
			else
			{
				echo 'Erreur';
			}
		}
		function get_from_book_uid($uid)
		{
			if($uid == 0) { $uid = $this->book; }
			$sql = $this->getconnected();
			if($sql)
			{
				$q=$sql->query("SELECT author FROM book WHERE id='".$uid."' LIMIT 1")->fetch(PDO::FETCH_OBJ);
				if($q)
				{
					$q2=$sql->query("SELECT * FROM author WHERE id='".$q->author."' LIMIT 1");
					if($q2)
					{
						$d = $q2->fetch(PDO::FETCH_OBJ);
						$this->authorid = $d->id;
						$this->authorname = $d->name;
						$this->lage = $d->lage;
					}
					else
					{
						echo "Erreur";
					}
				}
			}
		}
		function get_text($uid) 
		{
			if($uid == 0) { $uid = $this->text; }
			$sql = $this->getconnected();
			if($sql)
			{
				$q2=$sql->query("SELECT * FROM text_src WHERE id='".$uid."' LIMIT 1");
				if($q2)
				{
					$d = $q2->fetch(PDO::FETCH_OBJ);
					$this->text = $d->id;
					$this->textsrc = $d->text;
					$this->authorid = $d->author;
				}
				else
				{
					exit('Erreur');
				}
			}
		}		
		function get_book($uid) 
		{
			if($uid == 0) { $uid = $this->book; }
			$sql = $this->getconnected();
			if($sql)
			{
				$q2=$sql->query("SELECT * FROM book WHERE id='".$uid."' LIMIT 1");
				if($q2)
				{
					$d = $q2->fetch(PDO::FETCH_OBJ);
					$this->bookname = $d->name;
				}
			}
		}
		
		function get_textbook_from_chapter($chapter, $sschapter, $book)
		{
			$sql = $this->getconnected();
			if($sql)
			{
				$q=$sql->query("SELECT * FROM text_book WHERE chapter='".$chapter."' AND sschapter='".$sschapter."' AND book='".$book."' LIMIT 1");
				if($q)
				{
					$d = $q->fetch(PDO::FETCH_OBJ) or exit("L'objet demandé n'existe pas.");
					$this->text = $d->text;
					$this->textbook = $d->id;
					$this->book = $d->book;
					$this->chapter = $d->chapter;
					$this->sschapter = $d->sschapter;
				}
			}
		}
		function get_textbook_from_text($uid) 
		{
			if($uid == 0) { $uid = $this->text; }
			$sql = $this->getconnected();
			if($sql)
			{
				$q=$sql->query("SELECT * FROM text_book WHERE text='".$uid."' LIMIT 1") or exit("L'objet demandé n'existe pas.");
				if($q)
				{
					$d = $q->fetch(PDO::FETCH_OBJ);
					$this->text = $d->text;
					$this->textbook = $d->id;
					$this->book = $d->book;
					$this->chapter = $d->chapter;
					$this->sschapter = $d->sschapter;
					
					return $this->book;
				}
			}
		}
		function everything_from_text($uid)
		{
			//En une requete :)
			//On connecte et set $this->sql
			$this->getconnected();
			//On balance
			$join = $this->sql->prepare("SELECT tb.id as tbid, tb.text as tbtext, tb.book as tbbook, tb.chapter as tbchapter, tb.sschapter as tbsschapter, book.name as bname, a.id as aid, a.name as aname, a.lage as alage, ts.text as textsrc FROM text_book tb, book, author a, text_src as ts WHERE tb.text= ? AND ts.id=tb.text AND book.id=tb.book AND a.id=book.author LIMIT 1");
			//On donne l'unique info liant tout ça
			$join->execute(array($uid));
			//On vérifie le nombre de réponse (1)
			if($join->rowCount() == 1)
			{
				$d = $join->fetch(PDO::FETCH_OBJ);
				$this->text = $d->tbtext;
				$this->textbook = $d->tbid;
				$this->book = $d->tbbook;
				$this->chapter = $d->tbchapter;
				$this->sschapter = $d->tbsschapter;
				$this->bookname = $d->bname;
				$this->authorid = $d->aid;
				$this->authorname = $d->aname;
				$this->lage = $d->alage;
				$this->textsrc = $d->textsrc;
			}
			
			/*
			//On récupère le textbook (=le link)
			$this->get_textbook_from_text($uid);
			//On récupère le reste
			$this->get_book(0);
			$this->get_from_book_uid(0);
			$this->get_text(0);
			*/
		}
		function everything_from_chap_sschap($chapter, $sschapter, $book)
		{
			/*
			//On récupère le textbook (=le link)
			$this->get_textbook_from_chapter($chapter, $sschapter, $book);
			//On récupère le reste
			$this->get_book(0);
			$this->get_from_book_uid(0);
			$this->get_text(0);
			*/
						$this->getconnected();
			//On balance
			$join = $this->sql->prepare("SELECT tb.id as tbid, tb.text as tbtext, tb.book as tbbook, tb.chapter as tbchapter, tb.sschapter as tbsschapter, book.name as bname, a.id as aid, a.name as aname, a.lage as alage, ts.text as textsrc FROM text_book tb, book, author a, text_src as ts WHERE tb.book= ? AND tb.chapter= ? AND tb.sschapter= ? AND ts.id=tb.text AND book.id=tb.book AND a.id=book.author LIMIT 1");
			//On donne l'unique info liant tout ça
			$join->execute(array($book, $chapter, $sschapter));
			//On vérifie le nombre de réponse (1)
			if($join->rowCount() == 1)
			{
				$d = $join->fetch(PDO::FETCH_OBJ);
				$this->text = $d->tbtext;
				$this->textbook = $d->tbid;
				$this->book = $d->tbbook;
				$this->chapter = $d->tbchapter;
				$this->sschapter = $d->tbsschapter;
				$this->bookname = $d->bname;
				$this->authorid = $d->aid;
				$this->authorname = $d->aname;
				$this->lage = $d->alage;
				$this->textsrc = $d->textsrc;
			}
		}

	}
?>