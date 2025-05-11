<?php

class CustomersTransactionSettlementsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsSettlementsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Customer Settlements | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("customers/transaction_settlements/create");
			$data['load_table_url'] = $defCls->genURL('customers/transaction_settlements/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/transaction_settlements.php";
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsSettlementsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('CSETT-','',$search_no);
				$search_no=ltrim($search_no,'CSETT-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_customer_id'])){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_id!=0";
			
			if($search_no){ $sql.=" AND settlement_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer_id){ $sql.=" AND customer_id='".$search_customer_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $CustomersTransactionsSettlementsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY settlement_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$settlements = $CustomersTransactionsSettlementsQuery->gets($sql);
			
			$data['settlements'] = array();
			
			foreach($settlements as $cat)
			{
				$data['settlements'][] = array(
										'settlement_id' => $cat['settlement_id'],
										'settlement_no' => $defCls->docNo('CSETT-',$cat['settlement_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'customer_id' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('customers/transaction_settlements/edit/'.$cat['settlement_id']),
										'printURL' => $defCls->genURL('customers/transaction_settlements/printView/'.$cat['settlement_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($settlements).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'customers/transaction_settlements_table.php';
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsSettlementsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."customers/transaction_settlements/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			
			$data['settlement_no'] = 'New';
			
			if(isset($_REQUEST['customer_id']))
			{
				$data['customer_id'] = $db->request('customer_id');
				$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
				$data['outstanding'] = $defCls->num($closing_balance);
			}
			else{ $data['customer_id'] = ''; $data['outstanding'] = '0.00'; }
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['account_id'])){ $data['account_id'] = $db->request('account_id'); }
			else{ $data['account_id'] = 0; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }
			
			if(isset($_REQUEST['bank_code'])){ $data['bank_code'] = $db->request('bank_code'); }
			else{ $data['bank_code'] = ''; }
			
			if(isset($_REQUEST['cheque_date'])){ $data['cheque_date'] = $db->request('cheque_date'); }
			else{ $data['cheque_date'] = ''; }
			
			if(isset($_REQUEST['cheque_no'])){ $data['cheque_no'] = $db->request('cheque_no'); }
			else{ $data['cheque_no'] = ''; }
			
			if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }

			
			$data['user_id'] = $userInfo['user_id'];
			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if($data['amount']>$data['outstanding']){ $error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++; }
				if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
				if($data['bank_code'] || $data['cheque_no'] || $data['cheque_date'])
				{
					if(!$data['bank_code'] || !$data['cheque_no'] || !$data['cheque_date'])
					{
						$error_msg[]="You can't fill only one cheque field; you must enter the bank number, cheque number, and the date!"; $error_no++;
					}
				}
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $CustomersTransactionsSettlementsQuery->create($data);
					
					$transaction_no = $defCls->docNo('CSETT-',$createdId);
					$firewallCls->addLog("Customer Settlement Created: ".$transaction_no);
					
					$settlementInfo = $CustomersTransactionsSettlementsQuery->get($createdId);
					
					if($data['bank_code'] && $data['cheque_no'] && $data['cheque_date'])
					{
						////Cheque
						$chequeData = [];
						$chequeData['reference_id'] = $createdId;
						$chequeData['added_date'] = $settlementInfo['added_date'];
						$chequeData['transaction_type'] = 'CSETT';
						$chequeData['type'] = 'Received';
						$chequeData['bank_code'] = $settlementInfo['bank_code'];
						$chequeData['cheque_date'] = $settlementInfo['cheque_date'];
						$chequeData['cheque_no'] = $settlementInfo['cheque_no'];
						$chequeData['amount'] = $settlementInfo['amount'];
						$chequeData['remarks'] = $transaction_no;
						$chequeData['deposited_account_id'] = 0;
						$chequeData['status'] = 0;
						
						$AccountsTransactionChequeQuery->create($chequeData);
					}
					else
					{
						////Account transactipn update
						$accountData = [];
						$accountData['account_id'] = $data['account_id'];
						$accountData['reference_id'] = $createdId;
						$accountData['added_date'] = $settlementInfo['added_date'];
						$accountData['transaction_type'] = 'CSETT';
						$accountData['debit'] = $settlementInfo['amount'];
						$accountData['credit'] = 0;
						$accountData['remarks'] = $transaction_no;
						
						$AccountsMasterAccountsQuery->transactionAdd($accountData);
					}
					
					
					////Customer transactipn update
					$customerData = [];
					$customerData['added_date'] = $settlementInfo['added_date'];
					$customerData['customer_id'] = $data['customer_id'];
					$customerData['reference_id'] = $createdId;
					$customerData['transaction_type'] = 'CSETT';
					$customerData['debit'] = 0;
					$customerData['credit'] = $settlementInfo['amount'];
					$customerData['remarks'] = $transaction_no;
					
					$CustomersMasterCustomersQuery->transactionAdd($customerData);
					
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
	
				$this_required_file = _HTML.'customers/transaction_settlements_form.php';
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsSettlementsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$settlementInfo = $CustomersTransactionsSettlementsQuery->get($id);
			$chequeInfo = $AccountsTransactionChequeQuery->getByTrn($settlementInfo['settlement_id'],'CSETT');
			
			if($settlementInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."customers/transaction_settlements/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['settlement_id'] = $settlementInfo['settlement_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['settlement_no'] = $defCls->docNo('CSETT-',$settlementInfo['settlement_id']);
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $settlementInfo['location_id']; }
				
				if(isset($_REQUEST['customer_id']))
				{
					$data['customer_id'] = $db->request('customer_id');
					$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				else
				{
					$data['customer_id'] = $settlementInfo['customer_id'];
					$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				
				if(isset($_REQUEST['account_id'])){ $data['account_id'] = $db->request('account_id'); }
				else{ $data['account_id'] = $settlementInfo['account_id']; }
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($settlementInfo['added_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($settlementInfo['amount']); }
			
				if(isset($_REQUEST['bank_code'])){ $data['bank_code'] = $db->request('bank_code'); }
				else{ $data['bank_code'] = $settlementInfo['bank_code']; }
				
				if(isset($_REQUEST['cheque_date'])){ $data['cheque_date'] = $db->request('cheque_date'); }
				elseif($settlementInfo['cheque_date']=='0000-00-00'){ $data['cheque_date'] = ''; }
				else{ $data['cheque_date'] = $defCls->showText($settlementInfo['cheque_date']); }
				
				if(isset($_REQUEST['cheque_no'])){ $data['cheque_no'] = $db->request('cheque_no'); }
				else{ $data['cheque_no'] = $settlementInfo['cheque_no']; }
				
				if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $settlementInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if($data['amount']>$data['outstanding']+$settlementInfo['amount'])
					{
						$error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++;
					}
					if($data['bank_code'] || $data['cheque_no'] || $data['cheque_date'])
					{
						if(!$data['bank_code'] || !$data['cheque_no'] || !$data['cheque_date'])
						{
							$error_msg[]="You can't fill only one cheque field; you must enter the bank number, cheque number, and the date!"; $error_no++;
						}
						
					}
					
					
					if($chequeInfo && $chequeInfo['status']!==0)
					{
						$error_msg[]="Cheque already realized. Please Revert your cheque!"; $error_no++;
					}
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$CustomersTransactionsSettlementsQuery->edit($data);
						$transaction_no = $defCls->docNo('CSETT-',$id);
						$firewallCls->addLog("Customer Settlement Updated: ".$transaction_no);
						
						
						$settlementInfo = $CustomersTransactionsSettlementsQuery->get($id);
						//
						
						if($chequeInfo)
						{	
							if($data['bank_code'] && $data['cheque_no'] && $data['cheque_date'])
							{
								$chequeData = [];
								$chequeData['reference_id'] = $settlementInfo['settlement_id'];
								$chequeData['added_date'] = $settlementInfo['added_date'];
								$chequeData['transaction_type'] = 'CSETT';
								$chequeData['type'] = 'Received';
								$chequeData['bank_code'] = $settlementInfo['bank_code'];
								$chequeData['cheque_date'] = $settlementInfo['cheque_date'];
								$chequeData['cheque_no'] = $settlementInfo['cheque_no'];
								$chequeData['amount'] = $settlementInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = 0;
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->update($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($settlementInfo['settlement_id'],'CSETT');
								
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $settlementInfo['added_date'];
								$accountData['account_id'] = $settlementInfo['account_id'];
								$accountData['reference_id'] = $settlementInfo['settlement_id'];
								$accountData['transaction_type'] = 'CSETT';
								$accountData['debit'] = $settlementInfo['amount'];
								$accountData['credit'] = 0;
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
								
							}
							
						}
						else
						{
							if($data['bank_code'] && $data['cheque_no'] && $data['cheque_date'])
							{
								
								$AccountsMasterAccountsQuery->transactionDelete($settlementInfo['settlement_id'],'CSETT');
								
								/////
								$chequeData = [];
								$chequeData['reference_id'] = $settlementInfo['settlement_id'];
								$chequeData['added_date'] = $settlementInfo['added_date'];
								$chequeData['transaction_type'] = 'CSETT';
								$chequeData['type'] = 'Received';
								$chequeData['bank_code'] = $settlementInfo['bank_code'];
								$chequeData['cheque_date'] = $settlementInfo['cheque_date'];
								$chequeData['cheque_no'] = $settlementInfo['cheque_no'];
								$chequeData['amount'] = $settlementInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = 0;
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->create($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($settlementInfo['account_id'],'CSETT');
							
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $settlementInfo['added_date'];
								$accountData['account_id'] = $settlementInfo['account_id'];
								$accountData['reference_id'] = $settlementInfo['settlement_id'];
								$accountData['transaction_type'] = 'CSETT';
								$accountData['debit'] = $settlementInfo['amount'];
								$accountData['credit'] = 0;
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
							}
							
						}
						
						////Customer transactipn update
						$customerData = [];
						$customerData['added_date'] = $settlementInfo['added_date'];
						$customerData['customer_id'] = $settlementInfo['customer_id'];
						$customerData['reference_id'] = $settlementInfo['settlement_id'];
						$customerData['transaction_type'] = 'CSETT';
						$customerData['debit'] = 0;
						$customerData['credit'] = $settlementInfo['amount'];
						$customerData['remarks'] = $transaction_no;
						
						$CustomersMasterCustomersQuery->transactionEdit($customerData);
					
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
		
					$this_required_file = _HTML.'customers/transaction_settlements_form.php';
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
				$error_msg[]="Invalid settlement Id"; $error_no++;
					
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
	
	
	

    public function printView() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsSettlementsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$settlementInfo = $CustomersTransactionsSettlementsQuery->get($id);
			
			if($settlementInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Customer Payments Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['settlement_id'] = $settlementInfo['settlement_id'];
					
				$data['settlement_no'] = $defCls->docNo('CSETT-',$settlementInfo['settlement_id']);
				
				$data['added_date'] = $dateCls->showDate($settlementInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($settlementInfo['location_id'],'name');
				
				$data['customer_id'] = $CustomersMasterCustomersQuery->data($settlementInfo['customer_id'],'name');
				
				$data['amount'] = $defCls->money($settlementInfo['amount']);
				
				$data['account_id'] = $AccountsMasterAccountsQuery->data($settlementInfo['account_id'],'name');
				
				$data['cheque_no'] = $settlementInfo['cheque_no']; 
				
				$data['cheque_date'] = $dateCls->showDate($settlementInfo['cheque_date']);
			
				if(isset($_REQUEST['cheque_date'])){$data['cheque_date'] = $db->request('cheque_date'); }
				else{ $data['cheque_date'] = $dateCls->showDate($settlementInfo['cheque_date']); }
				
				$data['details'] = $defCls->showText($settlementInfo['details']);
				
				$data['user'] = $SystemMasterUsersQuery->data($settlementInfo['user_id'],'name');

				$this_required_file = _HTML.'customers/transaction_settlements_print.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}
			else
			{
				$error_msg[]="Invalid settlement Id"; $error_no++;
					
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
