<?php

class SalesMasterRepConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesMasterRepQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Sales Rep | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("sales/master_rep/create");
			$data['load_table_url'] = $defCls->genURL('sales/master_rep/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/master_rep.php";
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
		global $SalesMasterRepQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE rep_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SalesMasterRepQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$warranties = $SalesMasterRepQuery->gets($sql);
			
			$data['rep'] = array();
			
			foreach($warranties as $rep)
			{
				$data['rep'][] = array(
										'rep_id' => $rep['rep_id'],
										'name' => $rep['name'],
										'status' => $defCls->getMasterStatus($rep['status']),
										'updateURL' => $defCls->genURL('sales/master_rep/edit/'.$rep['rep_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($warranties).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'sales/master_rep_table.php';
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
		global $SalesMasterRepQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."sales/master_rep/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countRepsByName = $SalesMasterRepQuery->gets("WHERE name='".$data['name']."'");
				$countRepsByName = count($countRepsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countRepsByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SalesMasterRepQuery->create($data);
					$firewallCls->addLog("Rep Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'sales/master_rep_form.php';
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
		global $SalesMasterRepQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getRepInfo = $SalesMasterRepQuery->get($id);
			
			if($getRepInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."sales/master_rep/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['rep_id'] = $getRepInfo['rep_id'];
					
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getRepInfo['name']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = $getRepInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getRepInfo['name'])
					{
						$countRepsByName = $SalesMasterRepQuery->gets("WHERE name='".$data['name']."'");
						$countRepsByName = count($countRepsByName);
						
						if($countRepsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$SalesMasterRepQuery->edit($data);
						$firewallCls->addLog("Rep Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'sales/master_rep_form.php';
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
				$error_msg[]="Invalid rep Id"; $error_no++;
					
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
