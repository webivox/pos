<?php

class SystemMasterUsersConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Users | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("system/master_users/create");
			$data['load_table_url'] = $defCls->genURL('system/master_users/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."system/master_users.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			if($sessionCls->load('signedUserId')==1){ $sql=" WHERE user_id!=0"; }
			else{ $sql=" WHERE user_id!=1"; }
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SystemMasterUsersQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$users = $SystemMasterUsersQuery->gets($sql);
			
			$data['users'] = array();
			
			foreach($users as $cat)
			{
				$data['users'][] = array(
										'user_id' => $cat['user_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('system/master_users/edit/'.$cat['user_id']),
										'deleteURL' => $defCls->genURL('system/master_users/delete/'.$cat['user_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($users).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'system/master_users_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}

    public function create() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."system/master_users/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['user_group_list'] = $SystemMasterUsergroupsQuery->gets("ORDER BY name ASC");
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				
			if(isset($_REQUEST['group_id'])){ 
				$data['group_id'] = $db->request('group_id'); 
			} else { 
				$data['group_id'] = 0; 
			}
			
			if(isset($_REQUEST['location_id'])){ 
				$data['location_id'] = $db->request('location_id'); 
			} else { 
				$data['location_id'] = 0; 
			}
			
			if(isset($_REQUEST['name'])){ 
				$data['name'] = $db->request('name'); 
			} else { 
				$data['name'] = ''; 
			}
			
			if(isset($_REQUEST['username'])){ 
				$data['username'] = $db->request('username'); 
			} else { 
				$data['username'] = ''; 
			}
			
			if(isset($_REQUEST['password'])){ 
				$data['password'] = $db->request('password'); 
			} else { 
				$data['password'] = ''; 
			}
				
			if(isset($_REQUEST['confirm_password'])){ 
				$data['confirm_password'] = $db->request('confirm_password'); 
			} else { 
				$data['confirm_password'] = ''; 
			}
				
			if(isset($_REQUEST['loginRedirectTo'])){ 
				$data['loginRedirectTo'] = $db->request('loginRedirectTo'); 
			} else { 
				$data['loginRedirectTo'] = ''; 
			}

			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$masterUserLimit = $defCls->master('users_limit');
				
				$countUsers = $SystemMasterUsersQuery->gets("");
				$countUsers = count($countUsers);
				
				$countUsersByUserName = $SystemMasterUsersQuery->gets("WHERE username='".$data['username']."'");
				$countUsersByUserName = count($countUsersByUserName);
				
				if($countUsers>$masterUserLimit){ $error_msg[]="You have reached the account user limit!"; $error_no++; }
				
				if(!$SystemMasterUsergroupsQuery->has($data['group_id'])){ $error_msg[]="Please choose a valid user group!"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="Please choose a valid location!"; $error_no++; }
				if(strlen($data['name'])<5){ $error_msg[]="Name must be minimum 5 letters"; $error_no++; }
				if(strlen($data['username'])<5){ $error_msg[]="Name must be minimum 5 letters"; $error_no++; }
				if($countUsersByUserName){ $error_msg[]="The username already exists"; $error_no++; }
				
				if(strlen($data['password'])<8){ $error_msg[]="Password must be minimum 8 letters"; $error_no++; }
				if($data['password']!==$data['confirm_password']){ $error_msg[]="Wrong confirm password"; $error_no++; }
				
				if(!$data['loginRedirectTo']){ $error_msg[]="Please choose a valid Login Redirect!"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SystemMasterUsersQuery->create($data);
					$firewallCls->addLog("User Created: ".$data['name']);
					
					$json['success']=true;
					$json['success_msg']="Sucessfully Created";

					
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
			else
			{
	
				$this_required_file = _HTML.'system/master_users_form.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}			
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	

    public function edit() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getUserInfo = $SystemMasterUsersQuery->get($id);
			
			$data['user_group_list'] = $SystemMasterUsergroupsQuery->gets("ORDER BY name ASC");
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			
			if($getUserInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."system/master_users/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['user_id'] = $getUserInfo['user_id'];
					
				if(isset($_REQUEST['group_id'])){ 
					$data['group_id'] = $db->request('group_id'); 
				} else { 
					$data['group_id'] = $getUserInfo['group_id']; 
				}
				
				if(isset($_REQUEST['location_id'])){ 
					$data['location_id'] = $db->request('location_id'); 
				} else { 
					$data['location_id'] = $getUserInfo['location_id']; 
				}
				
				if(isset($_REQUEST['name'])){ 
					$data['name'] = $db->request('name'); 
				} else { 
					$data['name'] = $getUserInfo['name']; 
				}
				
				if(isset($_REQUEST['username'])){ 
					$data['username'] = $db->request('username'); 
				} else { 
					$data['username'] = $getUserInfo['username']; 
				}
				
				if(isset($_REQUEST['password'])){ 
					$data['password'] = $db->request('password'); 
				} else { 
					$data['password'] = ''; 
				}
				
				if(isset($_REQUEST['confirm_password'])){ 
					$data['confirm_password'] = $db->request('confirm_password'); 
				} else { 
					$data['confirm_password'] = ''; 
				}
				
				if(isset($_REQUEST['loginRedirectTo'])){ 
					$data['loginRedirectTo'] = $db->request('loginRedirectTo'); 
				} else { 
					$data['loginRedirectTo'] = $getUserInfo['loginRedirectTo']; 
				}
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = $getUserInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					
					if(!$SystemMasterUsergroupsQuery->has($data['group_id'])){ $error_msg[]="Please choose a valid user group!"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="Please choose a valid location!"; $error_no++; }
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if(strlen($data['username'])<5){ $error_msg[]="Name must be minimum 5 letters"; $error_no++; }
					if($db->request('username')!==$getUserInfo['username'])
					{
						$countUsersByUserName = $SystemMasterUsersQuery->gets("WHERE username='".$data['username']."'");
						$countUsersByUserName = count($countUsersByUserName);
						
						if($countUsersByUserName){ $error_msg[]="The username already exists"; $error_no++; }
					}
				
					if($data['password'])
					{
						if(strlen($data['password'])<8){ $error_msg[]="Password must be minimum 8 letters"; $error_no++; }
						if($data['password']!==$data['confirm_password']){ $error_msg[]="Wrong confirm password"; $error_no++; }
					}
					
					if(!$data['loginRedirectTo']){ $error_msg[]="Please choose a valid Login Redirect!"; $error_no++; }
					
					if(!$error_no)
					{
						
						$SystemMasterUsersQuery->edit($data);
						$firewallCls->addLog("Users Updated: ".$data['name']);
						
						$json['success']=true;
						$json['success_msg']="Sucessfully Updated";
						
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
				else
				{
		
					$this_required_file = _HTML.'system/master_users_form.php';
					if (!file_exists($this_required_file)) {
						error_log("File not found: ".$this_required_file);
						die('File not found:'.$this_required_file);
					}
					else {
		
						require_once($this_required_file);
						
					}
				}	
			}
			else
			{
				$error_msg[]="Invalid user Id"; $error_no++;
					
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
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	
	
	
	

    public function delete() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			if($id==1)
			{
				$error_msg[]="You can't delete this user!"; $error_no++;
			}
			else
			{
				$getInfo = $SystemMasterUsersQuery->get($id);
				
				if($getInfo)
				{
					$name = $getInfo['name'];
					
					$deleteValue = $SystemMasterUsersQuery->delete($id);
					
					if($deleteValue=='deleted')
					{
						$firewallCls->addLog("User Deleted: ".$name);
					
						$json['success']=true;
						$json['success_msg']="Sucessfully Updated";
					
					}
					elseif(is_array($deleteValue))
					{
						foreach($deleteValue as $v)
						{
							$error_msg[]=$v; $error_no++;
						}
						
					}
					else
					{
						$error_msg[]="An error occurred while attempting to delete the user!"; $error_no++;
					}	
				}
				else
				{
					$error_msg[]="Invalid user Id"; $error_no++;
					
					
				}
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
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
}
