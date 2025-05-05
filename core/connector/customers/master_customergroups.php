<?php

class CustomersMasterCustomergroupsConnector {

    public function index() {
		
		require_once _QUERY."customers/master_customergroups.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("customers/master_customergroups/create");
			$data['load_table_url'] = $defCls->genURL('customers/master_customergroups/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/master_customergroups.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		require_once _QUERY."customers/master_customergroups.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_name')){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if($db->request('search_status')!==''){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_group_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status!==''){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $CustomersMasterCustomergroupsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$customergroups = $CustomersMasterCustomergroupsQuery->gets($sql);
			
			$data['customergroups'] = array();
			
			foreach($customergroups as $cat)
			{
				$data['customergroups'][] = array(
										'customer_group_id' => $cat['customer_group_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('customers/master_customergroups/edit/'.$cat['customer_group_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($customergroups).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'customers/master_customergroups_table.php';
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
		
		
		require_once _QUERY."customers/master_customergroups.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."customers/master_customergroups/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if($db->request('name')){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if($db->request('status')){ $data['status'] = $db->request('status');}
			elseif($db->request('status')==0){ $data['status'] = 0; }
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countCustomergroupsByName = $CustomersMasterCustomergroupsQuery->gets("WHERE name='".$data['name']."'");
				$countCustomergroupsByName = count($countCustomergroupsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countCustomergroupsByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$CustomersMasterCustomergroupsQuery->create($data);
					$firewallCls->addLog("Customergroup Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'customers/master_customergroups_form.php';
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
		
		
		require_once _QUERY."customers/master_customergroups.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getCustomergroupInfo = $CustomersMasterCustomergroupsQuery->get($id);
			
			if($getCustomergroupInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."customers/master_customergroups/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['customer_group_id'] = $getCustomergroupInfo['customer_group_id'];
					
				if($db->request('name')){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getCustomergroupInfo['name']; }
				
				if($db->request('status')){ $data['status'] = $db->request('status');}
				elseif($db->request('status')==0){ $data['status'] = 0; }
				else{ $data['status'] = $getCustomergroupInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getCustomergroupInfo['name'])
					{
						$countCustomergroupsByName = $CustomersMasterCustomergroupsQuery->gets("WHERE name='".$data['name']."'");
						$countCustomergroupsByName = count($countCustomergroupsByName);
						
						if($countCustomergroupsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$CustomersMasterCustomergroupsQuery->edit($data);
						$firewallCls->addLog("Customergroups Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'customers/master_customergroups_form.php';
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
				$error_msg[]="Invalid customergroup Id"; $error_no++;
					
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
	
}
