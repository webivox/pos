<?php

class SuppliersTransactionPaymentsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionsPaymentsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Supplier Payments | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("suppliers/transaction_payments/create");
			$data['load_table_url'] = $defCls->genURL('suppliers/transaction_payments/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."suppliers/transaction_payments.php";
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionsPaymentsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('SPMNT-','',$search_no);
				$search_no=ltrim($search_no,'SPMNT-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_supplier_id'])){ $search_supplier_id=$db->request('search_supplier_id'); }
			else{ $search_supplier_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE supplier_id!=0";
			
			if($search_no){ $sql.=" AND payment_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_supplier_id){ $sql.=" AND supplier_id='".$search_supplier_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SuppliersTransactionsPaymentsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY payment_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$payments = $SuppliersTransactionsPaymentsQuery->gets($sql);
			
			$data['payments'] = array();
			
			foreach($payments as $cat)
			{
				$data['payments'][] = array(
										'payment_id' => $cat['payment_id'],
										'payment_no' => $defCls->docNo('SPMNT-',$cat['payment_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'supplier_id' => $SuppliersMasterSuppliersQuery->data($cat['supplier_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('suppliers/transaction_payments/edit/'.$cat['payment_id']),
										'printURL' => $defCls->genURL('suppliers/transaction_payments/printView/'.$cat['payment_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($payments).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'suppliers/transaction_payments_table.php';
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionsPaymentsQuery;
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
			
			$data['form_url'] 	= _SERVER."suppliers/transaction_payments/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			
			$data['payment_no'] = 'New';
			
			if(isset($_REQUEST['supplier_id']))
			{
				$data['supplier_id'] = $db->request('supplier_id');
				$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
				$data['outstanding'] = $defCls->num($closing_balance);
			}
			else{ $data['supplier_id'] = ''; $data['outstanding'] = '0.00'; }
			
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
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if($data['amount']>$data['account_balance'] && !$data['cheque_no']){ $error_msg[]="Account balance is lower than the given amount!"; $error_no++; }
				if($data['amount']>$data['outstanding']){ $error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++; }
				if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
				if($data['cheque_no'] && !$data['cheque_date'] || !$data['cheque_no'] && $data['cheque_date'])
				{
					$error_msg[]="You can't fill only one cheque field; you must enter both the cheque number and the date.!"; $error_no++;
				}
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $SuppliersTransactionsPaymentsQuery->create($data);
					
					$transaction_no = $defCls->docNo('SPMNT-',$createdId);
					$firewallCls->addLog("Supplier Payment Created: ".$transaction_no);
					
					$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($createdId);
					
					
					if($data['cheque_no'] && $data['cheque_date'])
					{
						////Cheque
						$chequeData = [];
						$chequeData['reference_id'] = $createdId;
						$chequeData['added_date'] = $paymentInfo['added_date'];
						$chequeData['transaction_type'] = 'SPMNT';
						$chequeData['type'] = 'Issued';
						$chequeData['bank_code'] = $paymentInfo['account_id'];
						$chequeData['cheque_date'] = $paymentInfo['cheque_date'];
						$chequeData['cheque_no'] = $paymentInfo['cheque_no'];
						$chequeData['amount'] = $paymentInfo['amount'];
						$chequeData['remarks'] = $transaction_no;
						$chequeData['deposited_account_id'] = $paymentInfo['account_id'];
						$chequeData['status'] = 0;
						
						$AccountsTransactionChequeQuery->create($chequeData);
					}
					else
					{
						////Account transactipn update
						$accountData = [];
						$accountData['added_date'] = $paymentInfo['added_date'];
						$accountData['account_id'] = $data['account_id'];
						$accountData['reference_id'] = $createdId;
						$accountData['transaction_type'] = 'SPMNT';
						$accountData['debit'] = 0;
						$accountData['credit'] = $paymentInfo['amount'];
						$accountData['remarks'] = $transaction_no;
						
						$AccountsMasterAccountsQuery->transactionAdd($accountData);
					}
					
					////Supplier transactipn update
					$supplierData = [];
					$supplierData['added_date'] = $paymentInfo['added_date'];
					$supplierData['supplier_id'] = $data['supplier_id'];
					$supplierData['reference_id'] = $createdId;
					$supplierData['transaction_type'] = 'SPMNT';
					$supplierData['debit'] = 0;
					$supplierData['credit'] = $paymentInfo['amount'];
					$supplierData['remarks'] = $transaction_no;
					
					$SuppliersMasterSuppliersQuery->transactionAdd($supplierData);
					
					
					
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
	
				$this_required_file = _HTML.'suppliers/transaction_payments_form.php';
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionsPaymentsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($id);
			$chequeInfo = $AccountsTransactionChequeQuery->getByTrn($paymentInfo['payment_id'],'SPMNT');
			
			if($paymentInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."suppliers/transaction_payments/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['payment_id'] = $paymentInfo['payment_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
				$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['payment_no'] = $defCls->docNo('SPMNT-',$paymentInfo['payment_id']);
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $paymentInfo['location_id']; }
				
				if(isset($_REQUEST['supplier_id']))
				{
					$data['supplier_id'] = $db->request('supplier_id');
					$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				else
				{
					$data['supplier_id'] = $paymentInfo['supplier_id'];
					$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($paymentInfo['added_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($paymentInfo['amount']); }
				
				if(isset($_REQUEST['account_id']))
				{
					$data['account_id'] = $db->request('account_id');
					$account_balance = $AccountsMasterAccountsQuery->data($data['account_id'],'closing_balance');
					$data['account_balance'] = $defCls->num($account_balance);
				}
				else
				{
					$data['account_id'] = $paymentInfo['account_id'];
					$data['account_balance'] = $AccountsMasterAccountsQuery->data($paymentInfo['account_id'],'closing_balance');
				}
			
				if(isset($_REQUEST['cheque_date'])){$data['cheque_date'] = $db->request('cheque_date'); }
				else{ $data['cheque_date'] = $dateCls->showDate($paymentInfo['cheque_date']); }
				
				if(isset($_REQUEST['cheque_no'])){$data['cheque_no'] = $db->request('cheque_no'); }
				else{ $data['cheque_no'] = $paymentInfo['cheque_no']; }
				
				if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $paymentInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if($data['amount']>$data['account_balance']+$paymentInfo['amount'] && !$data['cheque_no'])
					{
						$error_msg[]="Account balance is lower than the given amount!"; $error_no++;
					}
					if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
					if($data['cheque_no'] && !$data['cheque_date'] || !$data['cheque_no'] && $data['cheque_date'])
					{
						$error_msg[]="You can't fill only one cheque field; you must enter both the cheque number and the date.!"; $error_no++;
					}
					
					if($chequeInfo && $chequeInfo['status']!==0)
					{
						$error_msg[]="Cheque already realized. Please Revert your cheque!"; $error_no++;
					}
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
					///
					
						
					if(!$error_no)
					{
						
						$SuppliersTransactionsPaymentsQuery->edit($data);
						$transaction_no = $defCls->docNo('SPMNT-',$id);
						$firewallCls->addLog("Supplier Payment Updated: ".$transaction_no);
						
						
						$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($id);
						
						
						//
						
						if($chequeInfo)
						{	
							if($data['cheque_no'] && $data['cheque_date'])
							{
								$chequeData = [];
								$chequeData['reference_id'] = $paymentInfo['payment_id'];
								$chequeData['added_date'] = $paymentInfo['added_date'];
								$chequeData['transaction_type'] = 'SPMNT';
								$chequeData['type'] = 'Issued';
								$chequeData['bank_code'] = $paymentInfo['account_id'];
								$chequeData['cheque_date'] = $paymentInfo['cheque_date'];
								$chequeData['cheque_no'] = $paymentInfo['cheque_no'];
								$chequeData['amount'] = $paymentInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = $paymentInfo['account_id'];
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->update($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($paymentInfo['payment_id'],'SPMNT');
								
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $paymentInfo['added_date'];
								$accountData['account_id'] = $paymentInfo['account_id'];
								$accountData['reference_id'] = $paymentInfo['payment_id'];
								$accountData['transaction_type'] = 'SPMNT';
								$accountData['debit'] = 0;
								$accountData['credit'] = $paymentInfo['amount'];
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
								
							}
							
						}
						else
						{
							if($data['cheque_no'] && $data['cheque_date'])
							{
								
								$AccountsMasterAccountsQuery->transactionDelete($paymentInfo['payment_id'],'SPMNT');
								
								/////
								$chequeData = [];
								$chequeData['reference_id'] = $paymentInfo['payment_id'];
								$chequeData['added_date'] = $paymentInfo['added_date'];
								$chequeData['transaction_type'] = 'SPMNT';
								$chequeData['type'] = 'Issued';
								$chequeData['bank_code'] = $paymentInfo['account_id'];
								$chequeData['cheque_date'] = $paymentInfo['cheque_date'];
								$chequeData['cheque_no'] = $paymentInfo['cheque_no'];
								$chequeData['amount'] = $paymentInfo['amount'];
								$chequeData['remarks'] = $transaction_no;
								$chequeData['deposited_account_id'] = $paymentInfo['account_id'];
								$chequeData['status'] = 0;
								
								$AccountsTransactionChequeQuery->create($chequeData);
								
							}
							else
							{
								
								$AccountsTransactionChequeQuery->delete($paymentInfo['account_id'],'SPMNT');
							
								////Account transactipn update
								$accountData = [];
								$accountData['added_date'] = $paymentInfo['added_date'];
								$accountData['account_id'] = $paymentInfo['account_id'];
								$accountData['reference_id'] = $paymentInfo['payment_id'];
								$accountData['transaction_type'] = 'SPMNT';
								$accountData['debit'] = 0;
								$accountData['credit'] = $paymentInfo['amount'];
								$accountData['remarks'] = $transaction_no;
								
								$AccountsMasterAccountsQuery->transactionAdd($accountData);
								
							}
							
						}
						
						////Supplier transactipn update
						$supplierData = [];
						$supplierData['added_date'] = $paymentInfo['added_date'];
						$supplierData['supplier_id'] = $paymentInfo['supplier_id'];
						$supplierData['reference_id'] = $paymentInfo['payment_id'];
						$supplierData['transaction_type'] = 'SPMNT';
						$supplierData['debit'] = 0;
						$supplierData['credit'] = $paymentInfo['amount'];
						$supplierData['remarks'] = $transaction_no;
						
						$SuppliersMasterSuppliersQuery->transactionEdit($supplierData);
					
					
						
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
		
					$this_required_file = _HTML.'suppliers/transaction_payments_form.php';
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
				$error_msg[]="Invalid payment Id"; $error_no++;
					
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionsPaymentsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($id);
			
			if($paymentInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Supplier Payments Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['payment_id'] = $paymentInfo['payment_id'];
					
				$data['payment_no'] = $defCls->docNo('SPMNT-',$paymentInfo['payment_id']);
				
				$data['added_date'] = $dateCls->showDate($paymentInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($paymentInfo['location_id'],'name');
				
				$data['supplier_id'] = $SuppliersMasterSuppliersQuery->data($paymentInfo['supplier_id'],'name');
				
				$data['amount'] = $defCls->money($paymentInfo['amount']);
				
				$data['account_id'] = $AccountsMasterAccountsQuery->data($paymentInfo['account_id'],'name');
				
				$data['cheque_no'] = $paymentInfo['cheque_no']; 
				
				$data['cheque_date'] = $dateCls->showDate($paymentInfo['cheque_date']);
			
				if(isset($_REQUEST['cheque_date'])){$data['cheque_date'] = $db->request('cheque_date'); }
				else{ $data['cheque_date'] = $dateCls->showDate($paymentInfo['cheque_date']); }
				
				$data['details'] = $defCls->showText($paymentInfo['details']);
				
				$data['user'] = $SystemMasterUsersQuery->data($paymentInfo['user_id'],'name');

				$this_required_file = _HTML.'suppliers/transaction_payments_print.php';
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
				$error_msg[]="Invalid payment Id"; $error_no++;
					
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
