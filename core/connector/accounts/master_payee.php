<?php

class accountsMasterPayeeConnector {

    public function index() {
		
		require_once _QUERY."accounts/master_payee.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsMasterPayeeQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Payee | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("accounts/master_payee/create");
			$data['load_table_url'] = $defCls->genURL('accounts/master_payee/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/master_payee.php";
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
		global $AccountsMasterPayeeQuery;
		
		
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
			
			$sql=" WHERE payee_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status!==''){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsMasterPayeeQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$payees = $AccountsMasterPayeeQuery->gets($sql);
			
			$data['payee'] = array();
			
			foreach($payees as $payee)
			{
				$data['payee'][] = array(
										'payee_id' => $payee['payee_id'],
										'name' => $payee['name'],
										'status' => $defCls->getMasterStatus($payee['status']),
										'updateURL' => $defCls->genURL('accounts/master_payee/edit/'.$payee['payee_id']),
										'deleteURL' => $defCls->genURL('accounts/master_payee/delete/'.$payee['payee_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($payees).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/master_payee_table.php';
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
		global $AccountsMasterPayeeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."accounts/master_payee/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countPayeesByName = $AccountsMasterPayeeQuery->gets("WHERE name='".$data['name']."'");
				$countPayeesByName = count($countPayeesByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countPayeesByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$AccountsMasterPayeeQuery->create($data);
					$firewallCls->addLog("Payee Created: ".$data['name']);
					
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
				}header('Content-Type: application/json');
				echo json_encode($json);
				
			}
			else
			{
	
				$this_required_file = _HTML.'accounts/master_payee_form.php';
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
		global $AccountsMasterPayeeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getPayeeInfo = $AccountsMasterPayeeQuery->get($id);
			
			if($getPayeeInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/master_payee/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['payee_id'] = $getPayeeInfo['payee_id'];
					
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getPayeeInfo['name']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status'); }
				else{ $data['status'] = $getPayeeInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getPayeeInfo['name'])
					{
						$countPayeesByName = $AccountsMasterPayeeQuery->gets("WHERE name='".$data['name']."'");
						$countPayeesByName = count($countPayeesByName);
						
						if($countPayeesByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$AccountsMasterPayeeQuery->edit($data);
						$firewallCls->addLog("Payee Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'accounts/master_payee_form.php';
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
				$error_msg[]="Invalid payee Id"; $error_no++;
					
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
		global $AccountsMasterPayeeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $AccountsMasterPayeeQuery->get($id);
			
			if($getInfo)
			{
				$name = $getInfo['name'];
				
				$deleteValue = $AccountsMasterPayeeQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("Payee Deleted: ".$name);
				
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
					$error_msg[]="An error occurred while attempting to delete the payee!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid payee Id"; $error_no++;
				
				
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
