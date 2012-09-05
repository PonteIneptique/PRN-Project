<?php
	function sessionBolean()
	{
		if(isset($_SESSION['uid']))
		{
			global $connectBDD;
			if(!is_string($connectBDD))
			{
				$query = $connectBDD->prepare("SELECT verif_key FROM users WHERE id= ? LIMIT 1");
				$query->execute(array($_SESSION['uid']));
				if($query->rowCount() == 1)
				{
					$user = $query->fetch(PDO::FETCH_OBJ);
					if(($user) && ($user->verif_key == 0))
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
	}
	function sessionString2Var($echo, $var = true)
	{
		if(sessionBolean() == $var)
		{
			return $echo;
		}
	}
	function sessionString($echo, $var = true)
	{
		if(sessionBolean() == $var)
		{
			echo $echo;
		}
	}
	function session2var($connected, $disconnected)
	{
		if(sessionBolean() == true)
		{
			return $connected;
		}
		else
		{
			return $disconnected;
		}
	}
	function session2Strings($connected, $disconnected)
	{
		if(sessionBolean() == true)
		{
			echo $connected;
		}
		else
		{
			echo $disconnected;
		}
	}
?>