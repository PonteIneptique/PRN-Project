<h1>Langues disponibles</h1>
<?php
	//Exemple de query
	$src = langList();
	
	//Normalement on reçoit $_POST qu'on transforme en data		
	$results = array();
	$row_array = array();
	
	$m_q=$connectBDD->prepare("SELECT DISTINCT lage FROM author ORDER BY lage");
	$m_q->execute();
	if($m_q)
	{
		$temp = array();
		while($man = $m_q->fetch(PDO::FETCH_OBJ))
		{
			$temp[$src[$man->lage]] = $man->lage;
		}
		ksort($temp);//On trie
		$echo = '<ul class="unstyled">';
		foreach($temp as $key => $value)
		{
			$echo .= '<li><i class="icon-chevron-right"></i> ' .$key. '</li>';
		}
		echo $echo.'</ul>';
	}
?>