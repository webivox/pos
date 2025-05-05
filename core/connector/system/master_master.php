<?php

class SystemMasterMasterConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterMasterQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("system/master_master/create");
			$data['load_table_url'] = $defCls->genURL('system/master_master/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."system/master_master.php";
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
		global $SystemMasterMasterQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_key')){ $search_key=$db->request('search_key'); }
			else{ $search_key=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE master_id!=0";
			
			if($search_key){ $sql.=" AND `key` LIKE '%$search_key%'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SystemMasterMasterQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY `key` ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$masters = $SystemMasterMasterQuery->gets($sql);
			
			$data['masters'] = array();
			
			foreach($masters as $cat)
			{
				$data['master'][] = array(
										'master_id' => $cat['master_id'],
										'key' => $cat['key'],
										'values' => $defCls->showText($cat['values']),
										'updateURL' => $defCls->genURL('system/master_master/edit/'.$cat['master_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($masters).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'system/master_master_table.php';
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
		global $SystemMasterMasterQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."system/master_master/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if($db->request('key')){ 
				$data['key'] = $db->request('key');
			} else { 
				$data['key'] = ''; 
			}
			
			if($db->request('values')){ 
				$data['values'] = $db->request('values');
			} else { 
				$data['values'] = ''; 
			}

			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countMasterByKey = $SystemMasterMasterQuery->gets("WHERE `key`='".$data['key']."'");
				$countMasterByKey = count($countMasterByKey);
				
				if(strlen($data['key'])<3){ $error_msg[]="Key must be minimum 3 letters"; $error_no++; }
				if($countMasterByKey){ $error_msg[]="The key already exists"; $error_no++; }
				if(strlen($data['values'])<3){ $error_msg[]="Value must be minimum 3 letters"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SystemMasterMasterQuery->create($data);
					$firewallCls->addLog("Master Created: ".$data['key']);
					
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
	
				$this_required_file = _HTML.'system/master_master_form.php';
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
		global $SystemMasterMasterQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getMasterInfo = $SystemMasterMasterQuery->get($id);
			
			if($getMasterInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."system/master_master/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['master_id'] = $getMasterInfo['master_id'];
				
				if($db->request('key')){ 
					$data['key'] = $db->request('key');
				} else { 
					$data['key'] = $getMasterInfo['key']; 
				}
				
				if($db->request('values')){ 
					$data['values'] = $db->request('values');
				} else { 
					$data['values'] = $getMasterInfo['values']; 
				}
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['key'])<3){ $error_msg[]="Key must be minimum 3 letters"; $error_no++; }
					
					if($db->request('key')!==$getMasterInfo['key'])
					{
						$countMasterByKey = $SystemMasterMasterQuery->gets("WHERE `key`='".$data['key']."'");
						$countMasterByKey = count($countMasterByKey);
						
						if($countMasterByKey){ $error_msg[]="The key already exists"; $error_no++; }
					}
					
					if(strlen($data['values'])<3){ $error_msg[]="Value must be minimum 3 letters"; $error_no++; }
					
					if(!$error_no)
					{
						
						$SystemMasterMasterQuery->edit($data);
						$firewallCls->addLog("Master Updated: ".$data['key']);
						
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
		
					$this_required_file = _HTML.'system/master_master_form.php';
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
				$error_msg[]="Invalid master Id"; $error_no++;
					
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
