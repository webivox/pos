<?php

class AccountsTransactionExpencesConnector {

    public function index() {
		
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsExpencesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("accounts/transaction_expences/create");
			$data['load_table_url'] = $defCls->genURL('accounts/transaction_expences/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/transaction_expences.php";
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
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsMasterExpencestypesQuery;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsExpencesQuery;
		global $accountsls;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('AEXP-','',$search_no);
				$search_no=ltrim($search_no,'AEXP-');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_account_id')!==''){ $search_account_id=$db->request('search_account_id'); }
			else{ $search_account_id=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE account_id!=0";
			
			if($search_no){ $sql.=" AND expence_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_account_id){ $sql.=" AND account_id='".$search_account_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsTransactionsExpencesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY expence_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$expences = $AccountsTransactionsExpencesQuery->gets($sql);
			
			$data['expences'] = array();
			
			foreach($expences as $cat)
			{
				$data['expences'][] = array(
										'expence_id' => $cat['expence_id'],
										'expence_no' => $defCls->docNo('AEXP-',$cat['expence_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'account_id' => $AccountsMasterAccountsQuery->data($cat['account_id'],'name'),
										'payee' => $AccountsMasterPayeeQuery->data($cat['account_id'],'name'),
										'expences_type' => $AccountsMasterExpencestypesQuery->data($cat['expences_type_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('accounts/transaction_expences/edit/'.$cat['expence_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($expences).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/transaction_expences_table.php';
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
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsMasterExpencestypesQuery;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsExpencesQuery;
		global $SystemMasterLocationsQuery;
		global $accountsls;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."accounts/transaction_expences/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			$data['payee_list'] = $AccountsMasterPayeeQuery->gets("ORDER BY name ASC");
			$data['expencestype_list'] = $AccountsMasterExpencestypesQuery->gets("ORDER BY name ASC");
			
			$data['expence_no'] = 'New';
			
			if($db->request('payee_id')){ $data['payee_id'] = $db->request('payee_id'); }
			else{ $data['payee_id'] = ''; }
			
			if($db->request('expences_type_id')){ $data['expences_type_id'] = $db->request('expences_type_id'); }
			else{ $data['expences_type_id'] = ''; }
			
			if($db->request('account_id')){ $data['account_id'] = $db->request('account_id'); }
			else{ $data['account_id'] = ''; }
			
			if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if($db->request('details')){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }
			
			$data['user_id'] = $userInfo['user_id'];

			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$AccountsMasterPayeeQuery->has($data['payee_id'])){ $error_msg[]="You must choose a payee"; $error_no++; }
				if(!$AccountsMasterExpencestypesQuery->has($data['expences_type_id'])){ $error_msg[]="You must choose a expences type".$data['expences_type_id']; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $AccountsTransactionsExpencesQuery->create($data);
					
					$transaction_no = $defCls->docNo('AEXP-',$createdId);
					$firewallCls->addLog("Account Expence Created: ".$transaction_no);
					
					$expenceInfo = $AccountsTransactionsExpencesQuery->get($createdId);
					
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $expenceInfo['added_date'];
					$accountData['account_id'] = $data['account_id'];
					$accountData['reference_id'] = $createdId;
					$accountData['transaction_type'] = 'AEXP';
					$accountData['debit'] = 0;
					$accountData['credit'] = $expenceInfo['amount'];
					$accountData['remarks'] = $transaction_no;
					
					$AccountsMasterAccountsQuery->transactionAdd($accountData);
					
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
	
				$this_required_file = _HTML.'accounts/transaction_expences_form.php';
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
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsMasterExpencestypesQuery;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsExpencesQuery;
		global $SystemMasterLocationsQuery;
		global $accountsls;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $AccountsTransactionsExpencesQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/transaction_expences/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['expence_id'] = $getDebitNoteInfo['expence_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
				$data['payee_list'] = $AccountsMasterPayeeQuery->gets("ORDER BY name ASC");
				$data['expencestype_list'] = $AccountsMasterExpencestypesQuery->gets("ORDER BY name ASC");
					
				$data['expence_no'] = $defCls->docNo('AEXP-',$getDebitNoteInfo['expence_id']);
			
				if($db->request('payee_id')){ $data['payee_id'] = $db->request('payee_id'); }
				else{ $data['payee_id'] = $getDebitNoteInfo['payee_id']; }
				
				if($db->request('expences_type_id')){ $data['expences_type_id'] = $db->request('expences_type_id'); }
				else{ $data['expences_type_id'] = $getDebitNoteInfo['expences_type_id']; }
				
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getDebitNoteInfo['location_id']; }
				
				if($db->request('account_id')){ $data['account_id'] = $db->request('account_id'); }
				else{ $data['account_id'] = $getDebitNoteInfo['account_id']; }
				
				if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getDebitNoteInfo['added_date']); }
				
				if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($getDebitNoteInfo['amount']); }
				
				if($db->request('details')){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $getDebitNoteInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$AccountsMasterPayeeQuery->has($data['payee_id'])){ $error_msg[]="You must choose a payee"; $error_no++; }
					if(!$AccountsMasterExpencestypesQuery->has($data['expences_type_id'])){ $error_msg[]="You must choose a expences type"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$AccountsTransactionsExpencesQuery->edit($data);
						$transaction_no = $defCls->docNo('AEXP-',$id);
						$firewallCls->addLog("Account Expence Updated: ".$transaction_no);
						
						
						$expenceInfo = $AccountsTransactionsExpencesQuery->get($id);
						
						////Account transactipn update
						$accountData = [];
						$accountData['added_date'] = $expenceInfo['added_date'];
						$accountData['account_id'] = $expenceInfo['account_id'];
						$accountData['reference_id'] = $expenceInfo['expence_id'];
						$accountData['transaction_type'] = 'AEXP';
						$accountData['debit'] = 0;
						$accountData['credit'] = $expenceInfo['amount'];
						$accountData['remarks'] = $transaction_no;
						
						$AccountsMasterAccountsQuery->transactionEdit($accountData);
						
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
		
					$this_required_file = _HTML.'accounts/transaction_expences_form.php';
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
				$error_msg[]="Invalid expence Id"; $error_no++;
					
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
