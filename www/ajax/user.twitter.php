<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.function.security.php');
	require_once(RELurl.'inc/inc.conn.php');
	if(sessionBolean() && isset($_POST['name']))
	{
		$reg_user = $connectBDD->prepare("UPDATE social_users SET twitter = ? WHERE user= ? LIMIT 1");
		$reg_user->execute(array($_POST['name'], $_SESSION['uid']));
		if($reg_user->rowCount() == 1)
		{
			success(0);
		}
		else
		{
			error(0);
		}
	}
?>