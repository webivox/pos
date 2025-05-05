<?php

class AccountsTransactionTransfersConnector {

    public function index() {
		
		require_once _QUERY."accounts/transaction_transfers.php";
		
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
			
			$data['create_url'] 	= $defCls->genURL("accounts/transaction_transfers/create");
			$data['load_table_url'] = $defCls->genURL('accounts/transaction_transfers/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/transaction_transfers.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		require_once _QUERY."accounts/transaction_transfers.php";
		require_once _QUERY."accounts/master_accounts.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsTransactionsTransfersQuery;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('ATRN-','',$search_no);
				$search_no=ltrim($search_no,'ATRN-');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('account_from')){ $account_from=$db->request('account_from'); }
			else{ $account_from=''; }
			
			if($db->request('account_to')){ $account_to=$db->request('account_to'); }
			else{ $account_to=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE transfer_id!=0";
			
			if($search_no){ $sql.=" AND transfer_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($account_from){ $sql.=" AND account_from_id='".$account_from."'"; }
			if($account_to){ $sql.=" AND account_to_id='".$account_to."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsTransactionsTransfersQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY transfer_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$transfers = $AccountsTransactionsTransfersQuery->gets($sql);
			
			$data['transfers'] = array();
			
			foreach($transfers as $cat)
			{
				$data['transfers'][] = array(
										'transfer_id' => $cat['transfer_id'],
										'transfer_no' => $defCls->docNo('ATRN-',$cat['transfer_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'account_from' => $AccountsMasterAccountsQuery->data($cat['account_from_id'],'name'),
										'account_to' => $AccountsMasterAccountsQuery->data($cat['account_to_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('accounts/transaction_transfers/edit/'.$cat['transfer_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($transfers).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/transaction_transfers_table.php';
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
		
		
		require_once _QUERY."system/master_locations.php";
		require_once _QUERY."accounts/master_accounts.php";
		require_once _QUERY."accounts/transaction_transfers.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsTransactionsTransfersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."accounts/transaction_transfers/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			
			$data['transfer_no'] = 'New';
			
			if($db->request('account_from_id'))
			{
				$data['account_from_id'] = $db->request('account_from_id');
				$account_balance = $AccountsMasterAccountsQuery->data($data['account_from_id'],'closing_balance');
				$data['account_balance'] = $defCls->num($account_balance);
			}
			else{ $data['account_from_id'] = 0; $data['account_balance'] = 0; }
			
			if($db->request('account_to_id')){ $data['account_to_id'] = $db->request('account_to_id'); }
			else{ $data['account_to_id'] = ''; }
			
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
				if($data['account_from_id']==$data['account_to_id']){ $error_msg[]="You can't transfer between same account"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['account_from_id'])){ $error_msg[]="You must choose a account from"; $error_no++; }
				if($data['amount']>$data['account_balance']){ $error_msg[]="Account balance is lower than the given amount!"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['account_to_id'])){ $error_msg[]="You must choose a account to"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $AccountsTransactionsTransfersQuery->create($data);
					
					$transaction_no = $defCls->docNo('ATRN-',$createdId);
					$firewallCls->addLog("Account Transfer Created: ".$transaction_no);
					
					$transferInfo = $AccountsTransactionsTransfersQuery->get($createdId);
					
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $transferInfo['added_date'];
					$accountData['account_id'] = $data['account_from_id'];
					$accountData['reference_id'] = $createdId;
					$accountData['transaction_type'] = 'ATRNOUT';
					$accountData['debit'] = 0;
					$accountData['credit'] = $transferInfo['amount'];
					$accountData['remarks'] = $transaction_no;
					
					$AccountsMasterAccountsQuery->transactionAdd($accountData);
					
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $transferInfo['added_date'];
					$accountData['account_id'] = $data['account_to_id'];
					$accountData['reference_id'] = $createdId;
					$accountData['transaction_type'] = 'ATRNIN';
					$accountData['debit'] = $transferInfo['amount'];
					$accountData['credit'] = 0;
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
	
				$this_required_file = _HTML.'accounts/transaction_transfers_form.php';
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
		
		
		require_once _QUERY."system/master_locations.php";
		require_once _QUERY."accounts/master_accounts.php";
		require_once _QUERY."accounts/transaction_transfers.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $AccountsMasterAccountsQuery;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsTransfersQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $AccountsTransactionsTransfersQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/transaction_transfers/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['transfer_id'] = $getDebitNoteInfo['transfer_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['transfer_no'] = $defCls->docNo('ATRN-',$getDebitNoteInfo['transfer_id']);
				
				
				if($db->request('account_from_id'))
				{
					$data['account_from_id'] = $db->request('account_from_id');
					$account_balance = $AccountsMasterAccountsQuery->data($data['account_from_id'],'closing_balance');
					$data['account_balance'] = $defCls->num($account_balance);
				}
				else
				{
					$data['account_from_id'] = $getDebitNoteInfo['account_from_id'];
					$data['account_balance'] = $AccountsMasterAccountsQuery->data($getDebitNoteInfo['account_from_id'],'closing_balance');
				}
				
				if($db->request('account_to_id')){ $data['account_to_id'] = $db->request('account_to_id'); }
				else{ $data['account_to_id'] = $getDebitNoteInfo['account_to_id']; }
				
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getDebitNoteInfo['location_id']; }
				
				if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getDebitNoteInfo['added_date']); }
				
				if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($getDebitNoteInfo['amount']); }
				
				if($db->request('details')){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $getDebitNoteInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if($data['account_from_id']==$data['account_to_id']){ $error_msg[]="You can't transfer between same account"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['account_from_id'])){ $error_msg[]="You must choose a account from"; $error_no++; }
					if($data['amount']>$data['account_balance']+$getDebitNoteInfo['amount'])
					{
						$error_msg[]="Account balance is lower than the given amount!"; $error_no++;
					}
					if(!$AccountsMasterAccountsQuery->has($data['account_to_id'])){ $error_msg[]="You must choose a account to"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$AccountsTransactionsTransfersQuery->edit($data);
						$transaction_no = $defCls->docNo('ATRN-',$id);
						$firewallCls->addLog("Account Transfer Updated: ".$transaction_no);
						
						
						$transferInfo = $AccountsTransactionsTransfersQuery->get($id);
						
						////Account transactipn update
						$accountData = [];
						$accountData['added_date'] = $transferInfo['added_date'];
						$accountData['account_id'] = $transferInfo['account_from_id'];
						$accountData['reference_id'] = $transferInfo['transfer_id'];
						$accountData['transaction_type'] = 'ATRNOUT';
						$accountData['debit'] = 0;
						$accountData['credit'] = $transferInfo['amount'];
						$accountData['remarks'] = $transaction_no;
						
						$AccountsMasterAccountsQuery->transactionEdit($accountData);
						
						////Account transactipn update
						$accountData = [];
						$accountData['added_date'] = $transferInfo['added_date'];
						$accountData['account_id'] = $transferInfo['account_to_id'];
						$accountData['reference_id'] = $transferInfo['transfer_id'];
						$accountData['transaction_type'] = 'ATRNIN';
						$accountData['debit'] = $transferInfo['amount'];
						$accountData['credit'] = 0;
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
		
					$this_required_file = _HTML.'accounts/transaction_transfers_form.php';
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
				$error_msg[]="Invalid transfer Id"; $error_no++;
					
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
