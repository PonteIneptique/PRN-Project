<?php
	if(isset($_GET['b']) && ($_GET['b'] > 0))
	{
		$b_r=$connectBDD->prepare("SELECT b.name, b.id, a.name as authname, a.lage as alage FROM book b, author a WHERE b.id= ? AND a.id=b.author LIMIT 1");
		$b_r->execute(array($_GET['b']));
		$book = $b_r->fetch(PDO::FETCH_OBJ);
	}
?>