<?php
	function socialBolean($uid = false)
	{
		if(isset($_SESSION['uid']) && $uid == false)
		{
			$uid = $_SESSION['uid'];
		}
		if($uid != false)
		{
			global $connectBDD;
			if(!is_string($connectBDD))
			{
				$query = $connectBDD->prepare("SELECT id FROM social_users WHERE user= ? AND status= ? LIMIT 1"); //6
				$query->execute(array($uid, 1));
				if($query->rowCount() == 1)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	function socialString2Var($echo, $var = true)
	{
		if(sessionBolean() == $var)
		{
			return $echo;
		}
	}
	function socialString($echo, $var = true)
	{
		if(socialBolean() == $var)
		{
			echo $echo;
		}
	}
	function social2var($connected, $disconnected)
	{
		if(socialBolean() == true)
		{
			return $connected;
		}
		else
		{
			return $disconnected;
		}
	}
	function social2Strings($connected, $disconnected)
	{
		if(socialBolean() == true)
		{
			echo $connected;
		}
		else
		{
			echo $disconnected;
		}
	}
	function people($user, $doc = false)
	{
		//Doc est utilisé pour vérifier si on est le owner et qu'on est dans un cas de partage
		global $connectBDD;
		$sql = $connectBDD->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
		$sql->execute(array($user));
		if($sql->rowCount() == 1)
		{
			$u = $sql->fetch(PDO::FETCH_OBJ);
			switch (isfriend($user))
			{
				case true:
					$class = "btn-info";
					break;
				case false:
					$class = "";
					break;					
			}
			switch ($doc)
			{
				case $user:
					$owningoption = '<i class="icon-lock"></i>';
					break;
				case $user:
					$owningoption = "";
					break;					
			}
			return ' <div class="btn '.$class.'"><i class="icon-user"></i> '.$u->name.' '.$owningoption.'</div> ';
		}
	}
	function isfriend($user)
	{
		$return = false;
		global $connectBDD;
		
		$sql = $connectBDD->prepare("SELECT id FROM social_link WHERE user = ? AND friend = ? LIMIT 1");
		$sql->execute(array($user, $_SESSION['uid']));
		if($sql->rowCount() > 0) { $return = true; }
		
		return $return;
	}
?>