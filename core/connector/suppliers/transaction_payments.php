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
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('SPMNT-','',$search_no);
				$search_no=ltrim($search_no,'SPMNT-');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_supplier_id')!==''){ $search_supplier_id=$db->request('search_supplier_id'); }
			else{ $search_supplier_id=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
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
										'updateURL' => $defCls->genURL('suppliers/transaction_payments/edit/'.$cat['payment_id'])
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
			
			if($db->request('supplier_id')){ $data['supplier_id'] = $db->request('supplier_id'); }
			else{ $data['supplier_id'] = ''; }
			
			if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if($db->request('account_id')){ $data['account_id'] = $db->request('account_id'); }
			else{ $data['account_id'] = 0; }
			
			if($db->request('details')){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }
			
			$data['user_id'] = $userInfo['user_id'];

			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $SuppliersTransactionsPaymentsQuery->create($data);
					
					$transaction_no = $defCls->docNo('SPMNT-',$createdId);
					$firewallCls->addLog("Supplier Payment Created: ".$transaction_no);
					
					$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($createdId);
					
					
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
					
					
					////Account transaction update
					$accountData = [];
					$accountData['account_id'] = $data['account_id'];
					$accountData['reference_id'] = $createdId;
					$accountData['added_date'] = $paymentInfo['added_date'];
					$accountData['transaction_type'] = 'SPMNT';
					$accountData['debit'] = 0;
					$accountData['credit'] = $paymentInfo['amount'];
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
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $SuppliersTransactionsPaymentsQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."suppliers/transaction_payments/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['payment_id'] = $getDebitNoteInfo['payment_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
				$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['payment_no'] = $defCls->docNo('SPMNT-',$getDebitNoteInfo['payment_id']);
				
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getDebitNoteInfo['location_id']; }
				
				if($db->request('supplier_id')){ $data['supplier_id'] = $db->request('supplier_id'); }
				else{ $data['supplier_id'] = $getDebitNoteInfo['supplier_id']; }
				
				if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getDebitNoteInfo['added_date']); }
				
				if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($getDebitNoteInfo['amount']); }
				
				if($db->request('account_id')){ $data['account_id'] = $db->request('account_id'); }
				else{ $data['account_id'] = $getDebitNoteInfo['account_id']; }
				
				if($db->request('details')){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $getDebitNoteInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$SuppliersTransactionsPaymentsQuery->edit($data);
						$transaction_no = $defCls->docNo('SPMNT-',$id);
						$firewallCls->addLog("Supplier Payment Updated: ".$transaction_no);
						
						
						$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($id);
						
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
					
					
						////Account transaction update
						$accountData = [];
						$accountData['account_id'] = $data['account_id'];
						$accountData['reference_id'] = $id;
						$accountData['added_date'] = $paymentInfo['added_date'];
						$accountData['transaction_type'] = 'SPMNT';
						$accountData['debit'] = 0;
						$accountData['credit'] = $paymentInfo['amount'];
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
	
}
