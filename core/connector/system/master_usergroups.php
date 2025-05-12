<?php

class SystemMasterUsergroupsConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'User Groups | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("system/master_usergroups/create");
			$data['load_table_url'] = $defCls->genURL('system/master_usergroups/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."system/master_usergroups.php";
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
		global $SystemMasterUsergroupsQuery;
		
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
			
			$sql=" WHERE group_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SystemMasterUsergroupsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$usergroups = $SystemMasterUsergroupsQuery->gets($sql);
			
			$data['usergroups'] = array();
			
			foreach($usergroups as $cat)
			{
				$data['usergroups'][] = array(
										'group_id' => $cat['group_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('system/master_usergroups/edit/'.$cat['group_id']),
										'deleteURL' => $defCls->genURL('system/master_usergroups/delete/'.$cat['group_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($usergroups).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'system/master_usergroups_table.php';
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
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."system/master_usergroups/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['openCheckBox'] = false;
				
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countUsergroupsByName = $SystemMasterUsergroupsQuery->gets("WHERE name='".$data['name']."'");
				$countUsergroupsByName = count($countUsergroupsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countUsergroupsByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					$perm=[];
					$res=$db->fetchAll("SELECT * FROM secure_users_group_paths");
					foreach($res as $row)
					{
						//if(isset($_REQUEST['path_id'.$row['path_id']]))
						//{
						//	$perm.=$db->request('path_id'.$row['path_id']).':';
						//}
						if(isset($_REQUEST['access'.$row['path_id']]) && $_REQUEST['access'.$row['path_id']])
						{ $access=1; }else{ $access=0; }
						
						if(isset($_REQUEST['create'.$row['path_id']]) && $_REQUEST['create'.$row['path_id']])
						{ $create=1; }else{ $create=0; }
						
						if(isset($_REQUEST['edit'.$row['path_id']]) && $_REQUEST['edit'.$row['path_id']])
						{ $edit=1; }else{ $edit=0; }
						
						if(isset($_REQUEST['delete'.$row['path_id']]) && $_REQUEST['delete'.$row['path_id']])
						{ $delete=1; }else{ $delete=0; }
						
						$perm[]=array('path'=>$row['path_id'],'permission'=>array($access,$create,$edit,$delete));
						
						
						
					}
					
					$data['permissions'] = json_encode($perm);
					
					$SystemMasterUsergroupsQuery->create($data);
					$firewallCls->addLog("Usergroup Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'system/master_usergroups_form.php';
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
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getUsergroupInfo = $SystemMasterUsergroupsQuery->get($id);
			
			if($getUsergroupInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."system/master_usergroups/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['group_id'] = $getUsergroupInfo['group_id'];
			
				$data['openCheckBox'] = true;
			
				$data['savedPermissions'] = json_decode($getUsergroupInfo['permissions'],true);
					
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getUsergroupInfo['name']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = $getUsergroupInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getUsergroupInfo['name'])
					{
						$countUsergroupsByName = $SystemMasterUsergroupsQuery->gets("WHERE name='".$data['name']."'");
						$countUsergroupsByName = count($countUsergroupsByName);
						
						if($countUsergroupsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$perm=[];
						$res=$db->fetchAll("SELECT * FROM secure_users_group_paths");
						foreach($res as $row)
						{
							//if(isset($_REQUEST['path_id'.$row['path_id']]))
							//{
							//	$perm.=$db->request('path_id'.$row['path_id']).':';
							//}
							if(isset($_REQUEST['access'.$row['path_id']]) && $_REQUEST['access'.$row['path_id']])
							{ $access=1; }else{ $access=0; }
							
							if(isset($_REQUEST['create'.$row['path_id']]) && $_REQUEST['create'.$row['path_id']])
							{ $create=1; }else{ $create=0; }
							
							if(isset($_REQUEST['edit'.$row['path_id']]) && $_REQUEST['edit'.$row['path_id']])
							{ $edit=1; }else{ $edit=0; }
							
							if(isset($_REQUEST['delete'.$row['path_id']]) && $_REQUEST['delete'.$row['path_id']])
							{ $delete=1; }else{ $delete=0; }
							
							$perm[]=array('path'=>$row['path_id'],'permission'=>array($access,$create,$edit,$delete));
							
							
							
						}
						
						$data['permissions'] = json_encode($perm);
						
						$SystemMasterUsergroupsQuery->edit($data);
						$firewallCls->addLog("Usergroups Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'system/master_usergroups_form.php';
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
				$error_msg[]="Invalid usergroup Id"; $error_no++;
					
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
		global $SystemMasterUsergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $SystemMasterUsergroupsQuery->get($id);
			
			if($getInfo)
			{
				$name = $getInfo['name'];
				
				$deleteValue = $SystemMasterUsergroupsQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("User Group Deleted: ".$name);
				
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
					$error_msg[]="An error occurred while attempting to delete the user group!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid user group Id"; $error_no++;
				
				
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
