<?php

class SystemMasterCashierpointsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Cashier Points | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("system/master_cashierpoints/create");
			$data['load_table_url'] = $defCls->genURL('system/master_cashierpoints/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."system/master_cashierpoints.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("cashierpoint:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		
		
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
			
			$sql=" WHERE cashierpoint_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SystemMasterCashierpointsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$cashierpoints = $SystemMasterCashierpointsQuery->gets($sql);
			
			$data['cashierpoints'] = array();
			
			foreach($cashierpoints as $cat)
			{
				$data['cashierpoints'][] = array(
										'cashierpoint_id' => $cat['cashierpoint_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('system/master_cashierpoints/edit/'.$cat['cashierpoint_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($cashierpoints).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'system/master_cashierpoints_table.php';
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
		global $SystemMasterCashierpointsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."system/master_cashierpoints/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
				
			if(isset($_REQUEST['name'])){ 
				$data['name'] = $db->request('name');
			} else { 
				$data['name'] = ''; 
			}
			
			if(isset($_REQUEST['location_id'])){ 
				$data['location_id'] = $db->request('location_id');
			} else { 
				$data['location_id'] = ''; 
			}
			
			if(isset($_REQUEST['cash_account_id'])){ 
				$data['cash_account_id'] = $db->request('cash_account_id');
			} else { 
				$data['cash_account_id'] = ''; 
			}
			
			
			if(isset($_REQUEST['transfer_account_id'])){ 
				$data['transfer_account_id'] = $db->request('transfer_account_id');
			} else { 
				$data['transfer_account_id'] = ''; 
			}
			
			for($x=1;$x<6;$x++){
				
				if(isset($_REQUEST['card_account_'.$x.'_name'])){ 
					$data['card_account_'.$x.'_name'] = $db->request('card_account_'.$x.'_name');
				} else { 
					$data['card_account_'.$x.'_name'] = ''; 
				}
				
				if(isset($_REQUEST['card_account_'.$x.'_id'])){ 
					$data['card_account_'.$x.'_id'] = $db->request('card_account_'.$x.'_id');
				} else { 
					$data['card_account_'.$x.'_id'] = ''; 
				}
			
			}
			
			if(isset($_REQUEST['status'])){ 
				$data['status'] = $db->request('status');
			}
			else { 
				$data['status'] = 0; 
			}

			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countCashierpointsByName = $SystemMasterCashierpointsQuery->gets("WHERE name='".$data['name']."'");
				$countCashierpointsByName = count($countCashierpointsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countCashierpointsByName){ $error_msg[]="The name already exists"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="Please choose a valid location!"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['cash_account_id'])){ $error_msg[]="Please choose a valid cash account!"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['transfer_account_id'])){ $error_msg[]="Please choose a valid transfer account!"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SystemMasterCashierpointsQuery->create($data);
					$firewallCls->addLog("Cashier Point Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'system/master_cashierpoints_form.php';
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
			header("cashierpoint:"._SERVER);
		}
		
	}
	
	

    public function edit() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getCashierpointInfo = $SystemMasterCashierpointsQuery->get($id);
			
			if($getCashierpointInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."system/master_cashierpoints/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['cashierpoint_id'] = $getCashierpointInfo['cashierpoint_id'];
					
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				if(isset($_REQUEST['name'])){ 
					$data['name'] = $db->request('name');
				} else { 
					$data['name'] = $getCashierpointInfo['name']; 
				}
				
				if(isset($_REQUEST['location_id'])){ 
					$data['location_id'] = $db->request('location_id');
				} else { 
					$data['location_id'] = $getCashierpointInfo['location_id']; 
				}
				
				if(isset($_REQUEST['cash_account_id'])){ 
					$data['cash_account_id'] = $db->request('cash_account_id');
				} else { 
					$data['cash_account_id'] = $getCashierpointInfo['cash_account_id']; 
				}
				
				
				if(isset($_REQUEST['transfer_account_id'])){ 
					$data['transfer_account_id'] = $db->request('transfer_account_id');
				} else { 
					$data['transfer_account_id'] = $getCashierpointInfo['transfer_account_id']; 
				}
				
				for($x=1;$x<6;$x++){
					
					if(isset($_REQUEST['card_account_'.$x.'_name'])){ 
						$data['card_account_'.$x.'_name'] = $db->request('card_account_'.$x.'_name');
					} else { 
						$data['card_account_'.$x.'_name'] = $getCashierpointInfo['card_account_'.$x.'_name']; 
					}
					
					if(isset($_REQUEST['card_account_'.$x.'_id'])){ 
						$data['card_account_'.$x.'_id'] = $db->request('card_account_'.$x.'_id');
					} else { 
						$data['card_account_'.$x.'_id'] = $getCashierpointInfo['card_account_'.$x.'_id']; 
					}
				
				}
					
				if(isset($_REQUEST['status'])){ 
					$data['status'] = $db->request('status');
				} 
				else { 
					$data['status'] = $getCashierpointInfo['status']; 
				}
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					if($countCashierpointsByName){ $error_msg[]="The name already exists"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="Please choose a valid location!"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['cash_account_id'])){ $error_msg[]="Please choose a valid cash account!"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['transfer_account_id'])){ $error_msg[]="Please choose a valid transfer account!"; $error_no++; }
					
					if(!$error_no)
					{
						
						$SystemMasterCashierpointsQuery->edit($data);
						$firewallCls->addLog("Cashier Points Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'system/master_cashierpoints_form.php';
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
				$error_msg[]="Invalid cashierpoint Id"; $error_no++;
					
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
			header("cashierpoint:"._SERVER);
		}
		
	}
	
}
