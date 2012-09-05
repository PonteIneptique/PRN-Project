<?php
	require_once(RELurl.'./inc/inc.class.OPSession.php');
	require_once(RELurl.'./inc/inc.function.security.php');

	OPSession::Start();
	
	class document {
		var $authorname;
		var $authorid;
		var $book;
		var $bookname;
		var $text;
		var $textbook;//Lien entre toute les données qu'on obtient via text source
		var $textsrc;
		var $access = false;
		var $connectBDD;
		var $userisowner;
		
		function ConnexionBDD($serverPath, $port, $user, $pass, $base)
		{
			$PARAM_hote=$serverPath; // le chemin vers le serveur
			$PARAM_port=$port;
			$PARAM_nom_bd=$base; // le nom de votre base de données
			$PARAM_utilisateur=$user; // nom d'utilisateur pour se connecter
			$PARAM_mot_passe=$pass; // mot de passe de l'utilisateur pour se connecter
			
			try
			{
				$connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd,
				$PARAM_utilisateur,
				$PARAM_mot_passe);
				return $connexion;
			}
			catch(Exception $e)
			{
				$erreure =  'Erreur : '.$e->getMessage().'<br />';
				$erreure .= 'N° : '.$e->getCode();
				return $erreure;
			}
		}
		
		function __construct($id, $datas = true) {
			//On se connecte
			//On vérifie que global n'existe pas
			global $connectBDD;
			if(isset($connectBDD) && !is_string($connectBDD))
			{
				$this->connectBDD = $connectBDD;
			}
			else
			{
				$this->connectBDD = $this->ConnexionBDD('localhost', '3306', 'epigrammata', 'TAtUUT', 'epigrammata');
				if(!is_string($connectBDD))
				{		
					$result=$this->connectBDD->query("SET NAMES 'utf8'");
				}
				else
				{
					exit('Site hors service');
				}
			}
			if($id != 0)//0 évite le constructe
			{
				$this->getfromid($id, $datas);
			}
		}
		
		function getaccessfrompart($part, $datas = false)
		{
			$document = $this->connectBDD->prepare("SELECT sdtt.text FROM social_doc_trad_text sdtt, social_doc_trad_part sdtp WHERE sdtp.id= ? AND sdtt.id=sdtp.id ");
			$document->execute(array($part));
			if($document->rowCount() == 1)
			{
				$datas = $document->fetch(PDO::FETCH_OBJ);
				$this->getfromid($datas->text, $datas);
			}
		}
		
		function getfromid($id, $datas)
		{
			$document = $this->connectBDD->prepare("SELECT * FROM social_doc_src WHERE id= ? LIMIT 1");
			$document->execute(array($id));
			if($document->rowCount() == 1)
			{
				$datas = $document->fetch(PDO::FETCH_OBJ);
				if($datas->owner == $_SESSION['uid']) // Si c'est un owner
				{
					$this->access = true;
					$this->userisowner = true;
				}
				else//Sinon on vérifie plutôt les share
				{
					$shared = $this->connectBDD->prepare("SELECT id FROM social_doc_share WHERE doc= ? AND user = ? LIMIT 1");
					$shared->execute(array($id, $_SESSION['uid']));
					if($shared->rowCount() == 1)
					{
						$this->access = true;
					}
				}
				
				if(isset($this->access) && ($datas==true))//Si on a un accès et qu'on a demandés les datas
				{
					$docowner = $this->connectBDD->prepare("SELECT name FROM users WHERE id = ?");
					$docowner->execute(array($datas->owner));
					if($docowner->rowCount())
					{
						$text = $docowner->fetch(PDO::FETCH_OBJ);
					}
					//On set les données du text
					$this->text = $datas->id;
					$this->textsrc = $datas->src;
					$this->title = $datas->titre;
					$this->authorname = $text->name;
					
				}
			}
		}
	}
?>