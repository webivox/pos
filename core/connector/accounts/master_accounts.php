<?php

class AccountsMasterAccountsConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("accounts/master_accounts/create");
			$data['load_table_url'] = $defCls->genURL('accounts/master_accounts/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/master_accounts.php";
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
		global $AccountsMasterAccountsQuery;
		
		
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
			
			$sql=" WHERE account_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status!==''){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsMasterAccountsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$accounts = $AccountsMasterAccountsQuery->gets($sql);
			
			$data['accounts'] = array();
			
			foreach($accounts as $cat)
			{
				$data['accounts'][] = array(
										'account_id' => $cat['account_id'],
										'name' => $cat['name'],
										'balance' => $defCls->money($cat['closing_balance']),
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('accounts/master_accounts/edit/'.$cat['account_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($accounts).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/master_accounts_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	
	public function autoComplete()
	{	
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$json = [];
		
		if($firewallCls->verifyUser())
		{
			
			$term = $db->request('term');
			
			$sql = "WHERE name LIKE \"%".$term."%\"";
	
			$accountInfo = $AccountsMasterAccountsQuery->gets($sql);
		
			foreach($accountInfo as $itm)
			{
				
				$json[]=array(
						'value'=> $itm['account_id'],
						'label'=> $itm['name']
							);
			}
		}
		
		echo json_encode($json);
	
	}

    public function create() {
		
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."accounts/master_accounts/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if($db->request('name')){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if($db->request('payment_method')){ $data['payment_method'] = $db->request('payment_method');}
			else{ $data['payment_method'] = 0; }
			
			if($db->request('status')){ $data['status'] = $db->request('status');}
			elseif($db->request('status')==0){ $data['status'] = 0; }
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countAccountsByName = $AccountsMasterAccountsQuery->gets("WHERE name='".$data['name']."'");
				$countAccountsByName = count($countAccountsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countAccountsByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$AccountsMasterAccountsQuery->create($data);
					$firewallCls->addLog("Account Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'accounts/master_accounts_form.php';
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
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getAccountInfo = $AccountsMasterAccountsQuery->get($id);
			
			if($getAccountInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/master_accounts/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['account_id'] = $getAccountInfo['account_id'];
					
				if($db->request('name')){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getAccountInfo['name']; }
				
				if($db->request('payment_method')){ $data['payment_method'] = $db->request('payment_method');}
				elseif($db->request('payment_method')=='0'){ $data['payment_method'] = 0; }
				else{ $data['payment_method'] = $getAccountInfo['payment_method']; }
				
				if($db->request('status')){ $data['status'] = $db->request('status');}
				elseif($db->request('status')=='0'){ $data['status'] = 0; }
				else{ $data['status'] = $getAccountInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getAccountInfo['name'])
					{
						$countAccountsByName = $AccountsMasterAccountsQuery->gets("WHERE name='".$data['name']."'");
						$countAccountsByName = count($countAccountsByName);
						
						if($countAccountsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$AccountsMasterAccountsQuery->edit($data);
						$firewallCls->addLog("Accounts Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'accounts/master_accounts_form.php';
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
				$error_msg[]="Invalid account Id"; $error_no++;
					
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
