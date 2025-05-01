<?php
class firewall
{
	// done
	public function loginVerify($user_id, $token)
	{
		global $db;

		$row = $db->fetch("SELECT * FROM secure_users WHERE user_id= ? AND token=?", [$user_id, $token]);
		
		// Return $row if it's not empty, otherwise return an empty array
		return !empty($row) ? $row : [];

		
	}
	
	//done
	public function validate($username, $password) {

         global $db;

		$count = $db->fetch("SELECT username, password FROM secure_users WHERE username= ? AND password=?", [$username, $password]);

		// Check if $count has any result (i.e., not empty or null)
		return !empty($count) ? true : false;

    }
	
	//done
	public function verifyUser()
	{
		
		global $db;
		global $sessionCls;
		
		if($sessionCls->load('signedUserId') && $sessionCls->load('signedToken'))
		{
		
			$user_id = $sessionCls->load('signedUserId');
			$token = $sessionCls->load('signedToken');
			
			$row = $db->fetch("SELECT * FROM secure_users WHERE user_id= ? AND token=? AND status=1", [$user_id, $token]);
			
			
			if($row)
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
	
	public function ipBlockCheck()
	{
		
		global $db;
		global $ip;
		
		$res =  $db->fetchAll("SELECT * FROM secure_users_invalid_logins WHERE ip='".$ip->useIp()."'");
		$count = count($res);
		
		if($count>=5)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function addIp($userid=0)
	{
		
		global $db;
		global $ip;
		global $date;
		
		$nowdatetime = $date->nowDb();

		$sql = "INSERT INTO secure_users_invalid_logins(ip, user_id, logged_datetime) VALUES ('".$ip->useIp()."', '".$userid."', '".$nowdatetime."')";
		$insertResult = $db->execute($sql);
		
	}
	
	
	
	public function ipRemove()
	{
		
		global $db;
		global $ip;
		
		$sql = "DELETE FROM  secure_users_invalid_logins WHERE ip='".$ip->useIp()."'";
		$db->execute($sql);
				
	}
	
	// done
	public function addLog($details)
	{
		
		global $db;
		global $dateCls;
		global $sessionCls;
		
		$nowdatetime = $dateCls->nowDb();
		$user_logged_id = $sessionCls->load('signedUserId');
		
		$sql = "INSERT INTO secure_users_log(user_id,log_datetime,details)VALUES('".$user_logged_id."','".$nowdatetime."','".$details."')";
			  
		$db->execute($sql);  
	}
	
	
	public function priviledgeVerify($path,$file,$action)
	{
		/*
		global $db;
		
		if(isset($_SESSION[_SESSIONPREFIX.'valSes_userid'], $_SESSION[_SESSIONPREFIX.'valSes_token']))
		{
			$user_logged_id = $db->dbString($_SESSION[_SESSIONPREFIX.'valSes_userid']);
			$user_token = $db->dbString($_SESSION[_SESSIONPREFIX.'valSes_token']);
			
			$res = $db->query("SELECT * FROM "._DBPREFIX."secure_users WHERE user_id = '".$user_logged_id."' AND logged_token='".$user_token."'");
			$count = $res->num_rows;
			
			if($count)
			{
				$row = $res->fetch_assoc();
				
				$res_p = $db->query("SELECT * FROM "._DBPREFIX."secure_privileges WHERE privileges_id = '".$row['privilege_id']."' AND status=1");
				$row_p = $res_p->fetch_assoc();
				
				$priviledgeexp = explode('-fexp-',$row_p['roles']);
				
				foreach($priviledgeexp as $f)
				{
					$fexp=explode('-lexp-',$f);
					
					$orpath=$path.'/'.$file;
					
					if($orpath == $fexp[0])
					{
					
						$practionexp = explode('-exp-',$fexp[1]);
						
						if($practionexp[0])
						{
							
							
							if($action=='add')
							{
								if($practionexp[1])
								{
									return true;
								}
								else
								{
									return false;
								}
							}
							elseif($action=='edit')
							{
								
								if($practionexp[2])
								{
									return true;
								}
								else
								{
									return false;
								}
							}
							elseif($action=='delete')
							{
								if($practionexp[3])
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
								if($practionexp[0])
								{
									return true;
								}
								else
								{
									return false;
								}
							}
						}
						else
						{
							return false;
						}
						
					}
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
		*/
	}
	
}

$firewallCls=new firewall;


 

?>