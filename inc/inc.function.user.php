<?php
	function checkUser($row, $array)
	{
		global $connectBDD;
		if(!is_string($connectBDD))
		{
			$query = "SELECT id FROM users WHERE `".$row."`= ? LIMIT 1";
			
			$v_q = $connectBDD->prepare($query);
			$v_q->execute(array($array[$row]));
			if($v_q)
			{
				if($v_q->rowCount() == 0)
				{
					return 'true';
				}
				else
				{
					return 'false';
				}
			}
			else
			{
				return 'false';
			}
		}
		else
		{
			return 'false';
		}
	}
	function UserExists($userid)
	{
		global $connectBDD;
		if(!is_string($connectBDD))
		{
			echo $userid;
			$user = $connectBDD->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
			$user->execute(array($userid));
			if($user->rowCount() == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	function thisfav($id, $table)
	{
		global $connectBDD;
		$req = $connectBDD->prepare("SELECT id FROM `fav` WHERE `user`= ? AND `table`= ? AND `text_src`= ? ");
		$req->execute(array($_SESSION['uid'], $table, $id));
		if($req->rowCount() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
?>