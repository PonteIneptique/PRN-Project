<?php
	if(isset($_GET['a']) && ($_GET['a'] > 0))
	{
		$a_r=$connectBDD->prepare("SELECT * FROM author WHERE id= ? LIMIT 1");
		$a_r->execute(array($_GET['a']));
		$author = $a_r->fetch(PDO::FETCH_OBJ);
	}
?>