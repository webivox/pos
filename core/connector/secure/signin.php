<?php

class SecureSigninConnector {

    public function index() {
				
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		
		
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			header("location:"._SERVER.$userInfo['loginRedirectTo']);
		}
		else
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['formUrl'] 			= _SERVER."secure/signin/validate";
			
			require_once _HTML."secure/signin.php";
		}
		
		
	}
	
	
    public function validate() {
		
		
		global $db;
		global $sessionCls;
		global $dateCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		
		$json = [];
		$data = [];
		$error_msg = [];
		$error_no = 0;
		
		
		if(isset($_REQUEST['txtUserPos'])){ $data['username'] = $db->escape($_REQUEST['txtUserPos']);}
		else{ $data['username'] = ''; }
		
		if(isset($_REQUEST['txtPassPos'])){ $data['password'] = $_REQUEST['txtPassPos'];}
		else{ $data['password'] = ''; }
		
		
		if(strlen($data['username'])==5){ $error_msg[]="Username must be 5 letters"; $error_no++; }
		if(strlen($data['password'])<8){ $error_msg[]="Password must be minimum 8 letters"; $error_no++; }
		
		
		if(!$firewallCls->validate($data['username'], sha1($data['password'])))
		{
			$error_msg[]="Invalid Login Details!"; $error_no++;
		}
		
		if(!$error_no)
		{
			$userInfo = $SystemMasterUsersQuery->getUSerDataByUserPass($data['username'], sha1($data['password']));
			
			if($userInfo)
			{
				function generateToken($length = 32) {
					return bin2hex(random_bytes($length / 2));
				}
				
				$token = generateToken(32);
				
				$nowDate = $dateCls->nowDb();
								
				$sessionCls->set('signedUserId',$userInfo['user_id']);
				$sessionCls->set('signedToken',$token);
				
				$db->execute("UPDATE secure_users SET token='".$token."', lastLogin=thisLogin, thisLogin='".$nowDate."' WHERE user_id = '".$userInfo['user_id']."'");
				
				$firewallCls->addLog("User signed in successfully.");
				
				$json['success']=true;
				$json['redirect']=_SERVER.$userInfo['loginRedirectTo'];
				
			}
			else{ $error_msg[]="Invalid Login Details!"; $error_no++; }
		}
		
		if($error_no)
		{
			
			$error_msg_list='';
			foreach($error_msg as $e)
			{
				if($e)
				{
					$error_msg_list.='<li>'.$e.'</li>';
				}
			}
			$json['error']=true;
			$json['error_msg']=$error_msg_list;
		}
		echo json_encode($json);
		
		
	}
}
