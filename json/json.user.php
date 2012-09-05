<?php
	define("RELurl", '../');
	require_once(RELurl.'inc/inc.conn.php');
	if(!isset($_GET['row']) && (($_GET['row'] != 'login') || ($_GET['row'] != 'name') || ($_GET['row'] != 'email')))
	{
		exit('Tentative de hacking');
	}
	$datatype = $_GET['row'];
	if(isset($_REQUEST[$datatype]))//Si la donnée correspondante à la row existe
	{
		echo checkUser($datatype, $_REQUEST);
	}
?>