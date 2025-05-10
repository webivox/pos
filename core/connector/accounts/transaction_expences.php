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
			
			$data['titleTag'] 	= 'Expenses | '.$defCls->master('companyName');
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
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('AEXP-','',$search_no);
				$search_no=ltrim($search_no,'AEXP-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_account_id'])){ $search_account_id=$db->request('search_account_id'); }
			else{ $search_account_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
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
		global $AccountsTransactionChequeQuery;
		
		
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
			
			if(isset($_REQUEST['payee_id'])){ $data['payee_id'] = $db->request('payee_id'); }
			else{ $data['payee_id'] = ''; }
			
			if(isset($_REQUEST['expences_type_id'])){ $data['expences_type_id'] = $db->request('expences_type_id'); }
			else{ $data['expences_type_id'] = ''; }
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if(isset($_REQUEST['account_id']))
			{
				$data['account_id'] = $db->request('account_id');
				$account_balance = $AccountsMasterAccountsQuery->data($data['account_id'],'closing_balance');
				$data['account_balance'] = $defCls->num($account_balance);
			}
			else{ $data['account_id'] = 0; $data['account_balance'] = 0; }
			
			if(isset($_REQUEST['cheque_date'])){ $data['cheque_date'] = $db->request('cheque_date'); }
			else{ $data['cheque_date'] = ''; }
			
			if(isset($_REQUEST['cheque_no'])){ $data['cheque_no'] = $db->request('cheque_no'); }
			else{ $data['cheque_no'] = ''; }
			
			if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }
			
			$data['user_id'] = $userInfo['user_id'];

			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$AccountsMasterPayeeQuery->has($data['payee_id'])){ $error_msg[]="You must choose a payee"; $error_no++; }
				if(!$AccountsMasterExpencestypesQuery->has($data['expences_type_id'])){ $error_msg[]="You must choose a expences type".$data['expences_type_id']; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if($data['amount']>$data['account_balance']){ $error_msg[]="Account balance is lower than the given amount!"; $error_no++; }
				if($data['cheque_no'] && !$data['cheque_date'] || !$data['cheque_no'] && $data['cheque_date'])
				{
					$error_msg[]="You can't fill only one cheque field; you must enter both the cheque number and the date.!"; $error_no++;
				}
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				
				if(!$error_no)
				{
					
					$createdId = $AccountsTransactionsExpencesQuery->create($data);
					
					$transaction_no = $defCls->docNo('AEXP-',$createdId);
					$firewallCls->addLog("Account Expence Created: ".$transaction_no);
					
					$expenceInfo = $AccountsTransactionsExpencesQuery->get($createdId);
					
					if($data['cheque_no'] && $data['cheque_date'])
					{
						////Cheque
						$chequeData = [];
						$chequeData['reference_id'] = $createdId;
						$chequeData['added_date'] = $expenceInfo['added_date'];
						$chequeData['transaction_type'] = 'AEXP';
						$chequeData['type'] = 'Issued';
						$chequeData['bank_code'] = $expenceInfo['account_id'];
						$chequeData['cheque_date'] = $expenceInfo['cheque_date'];
						$chequeData['cheque_no'] = $expenceInfo['cheque_no'];
						$chequeData['amount'] = $expenceInfo['amount'];
						$chequeData['remarks'] = $transaction_no;
						$chequeData['deposited_account_id'] = $expenceInfo['account_id'];
						$chequeData['status'] = 0;
						
						$AccountsTransactionChequeQuery->create($chequeData);
					}
					else
					{
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
					}
					
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
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$expenceInfo = $AccountsTransactionsExpencesQuery->get($id);
			$chequeInfo = $AccountsTransactionChequeQuery->getByTrn($expenceInfo['expence_id'],'AEXP');
			
			if($expenceInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/transaction_expences/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['expence_id'] = $expenceInfo['expence_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
				$data['payee_list'] = $AccountsMasterPayeeQuery->gets("ORDER BY name ASC");
				$data['expencestype_list'] = $AccountsMasterExpencestypesQuery->gets("ORDER BY name ASC");
					
				$data['expence_no'] = $defCls->docNo('AEXP-',$expenceInfo['expence_id']);
				
			
				//$isSubmitted$db->request('isSubmitted')){ $data['isSubmitted'] = true; }
				//else{ $data['isSubmitted'] = false; }
			
				if(isset($_REQUEST['payee_id'])){  $data['payee_id'] = $db->request('payee_id'); }
				else{ $data['payee_id'] = $expenceInfo['payee_id']; }
				
				if(isset($_REQUEST['expences_type_id'])){$data['expences_type_id'] = $db->request('expences_type_id'); }
				else{ $data['expences_type_id'] = $expenceInfo['expences_type_id']; }
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $expenceInfo['location_id']; }
				
				if(isset($_REQUEST['account_id']))
				{
					$data['account_id'] = $db->request('account_id');
					$account_balance = $AccountsMasterAccountsQuery->data($data['account_id'],'closing_balance');
					$data['account_balance'] = $defCls->num($account_balance);
				}
				else
				{
					$data['account_id'] = $expenceInfo['account_id'];
					$data['account_balance'] = $AccountsMasterAccountsQuery->data($expenceInfo['account_id'],'closing_balance');
				}
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($expenceInfo['added_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($expenceInfo['amount']); }
			
				if(isset($_REQUEST['cheque_date'])){ $data['cheque_date'] = $db->request('cheque_date'); }
				else{ $data['cheque_date'] = $dateCls->showDate($expenceInfo['cheque_date']); }
				
				if(isset($_REQUEST['cheque_no'])){ $data['cheque_no'] = $db->request('cheque_no'); }
				else{ $data['cheque_no'] = $expenceInfo['cheque_no']; }
				
				if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $expenceInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
						
					
					if(!$AccountsMasterPayeeQuery->has($data['payee_id'])){ $error_msg[]="You must choose a payee"; $error_no++; }
					if(!$AccountsMasterExpencestypesQuery->has($data['expences_type_id'])){ $error_msg[]="You must choose a expences type"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
					if($data['amount']>$data['account_balance']+$expenceInfo['amount'])
					{
						$error_msg[]="Account balance is lower than the given amount!"; $error_no++;
					}
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				
					if($data['cheque_no'] && !$data['cheque_date'] || !$data['cheque_no'] && $data['cheque_date'])
					{
						$error_msg[]="You can't fill only one cheque field; you must enter both the cheque number and the date.!"; $error_no++;
					}
				
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
					if($chequeInfo && $chequeInfo['status']!==0)
					{
						$error_msg[]="Cheque already realized. Please Revert your cheque!"; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$AccountsTransactionsExpencesQuery->edit($data);
						$transaction_no = $defCls->docNo('AEXP-',$id);
						$firewallCls->addLog("Account Expence Updated: ".$transaction_no);
						
						$expenceInfo = $AccountsTransactionsExpencesQuery->get($id);
						
						//
						
						if($chequeInfo)
						{	
							if($data['cheque_no'] && $data['cheque_date'])
							{
								$chequeData = [];
								$chequeData['reference_id'] = $expenceInfo['expence_id'];
								$chequeData['added_date'] = $expenceInfo['added_date'];
								$chequeData['transaction_type'] = 'AEXP';
								$chequeData['type'] = 'Issued';
								$chequeData['bank_code'] = $expenceInfo['account_id'];
								$chequeData['cheque_date'] = $expenceInfo['cheque_date'];
								$chequeData['cheque_no'] = $expenceInfo['cheque_no'];
								$chequeData['amount'] = $expenceInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = $expenceInfo['account_id'];
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->update($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($expenceInfo['expence_id'],'AEXP');
								
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $expenceInfo['added_date'];
								$accountData['account_id'] = $expenceInfo['account_id'];
								$accountData['reference_id'] = $expenceInfo['expence_id'];
								$accountData['transaction_type'] = 'AEXP';
								$accountData['debit'] = 0;
								$accountData['credit'] = $expenceInfo['amount'];
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
								
							}
							
						}
						else
						{
							if($data['cheque_no'] && $data['cheque_date'])
							{
								
								$AccountsMasterAccountsQuery->transactionDelete($expenceInfo['expence_id'],'AEXP');
								
								/////
								$chequeData = [];
								$chequeData['reference_id'] = $expenceInfo['expence_id'];
								$chequeData['added_date'] = $expenceInfo['added_date'];
								$chequeData['transaction_type'] = 'AEXP';
								$chequeData['type'] = 'Issued';
								$chequeData['bank_code'] = $expenceInfo['account_id'];
								$chequeData['cheque_date'] = $expenceInfo['cheque_date'];
								$chequeData['cheque_no'] = $expenceInfo['cheque_no'];
								$chequeData['amount'] = $expenceInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = $expenceInfo['account_id'];
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->create($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($expenceInfo['account_id'],'AEXP');
							
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $expenceInfo['added_date'];
								$accountData['account_id'] = $expenceInfo['account_id'];
								$accountData['reference_id'] = $expenceInfo['expence_id'];
								$accountData['transaction_type'] = 'AEXP';
								$accountData['debit'] = 0;
								$accountData['credit'] = $expenceInfo['amount'];
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
							}
							
						}
					
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
