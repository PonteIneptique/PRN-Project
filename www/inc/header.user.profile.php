<?php
	if(sessionBolean())
	{
		if(isset($_GET['uid']))
		{
			$usid = $_GET['uid'];
			$profileaccess = socialBolean($usid);
		}
		else
		{
			$usid = $_SESSION['uid'];
			$profileaccess = true;
		}
		
		$socialme = $connectBDD->prepare("SELECT * FROM social_users WHERE user=? LIMIT 1");
		$socialme->execute(array($usid));
		$su = $socialme->fetch(PDO::FETCH_OBJ);
		
		$usinfo = $connectBDD->prepare("SELECT users.id, users.name, users.lage, users.email, university.name as uname FROM users, university WHERE users.id=? AND users.university=university.id LIMIT 1");
		$usinfo->execute(array($usid));
		$us = $usinfo->fetch(PDO::FETCH_OBJ);
	}
?>