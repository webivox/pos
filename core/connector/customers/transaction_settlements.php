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
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('CSETT-','',$search_no);
				$search_no=ltrim($search_no,'CSETT-');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_customer_id')!==''){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
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
										'updateURL' => $defCls->genURL('customers/transaction_settlements/edit/'.$cat['settlement_id'])
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
			
			if($db->request('customer_id')){ $data['customer_id'] = $db->request('customer_id'); }
			else{ $data['customer_id'] = ''; }
			
			if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if($db->request('account_id')){ $data['account_id'] = $db->request('account_id'); }
			else{ $data['account_id'] = 0; }
			
			if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if($db->request('details')){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }

			
			$data['user_id'] = $userInfo['user_id'];
			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $CustomersTransactionsSettlementsQuery->create($data);
					
					$transaction_no = $defCls->docNo('CSETT-',$createdId);
					$firewallCls->addLog("Customer Settlement Created: ".$transaction_no);
					
					$settlementInfo = $CustomersTransactionsSettlementsQuery->get($createdId);
					
					
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
					
					
					////Account transaction update
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
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $CustomersTransactionsSettlementsQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."customers/transaction_settlements/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['settlement_id'] = $getDebitNoteInfo['settlement_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['settlement_no'] = $defCls->docNo('CSETT-',$getDebitNoteInfo['settlement_id']);
				
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getDebitNoteInfo['location_id']; }
				
				if($db->request('customer_id')){ $data['customer_id'] = $db->request('customer_id'); }
				else{ $data['customer_id'] = $getDebitNoteInfo['customer_id']; }
				
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
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$data['account_id']){ $error_msg[]="You must choose a account"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$CustomersTransactionsSettlementsQuery->edit($data);
						$transaction_no = $defCls->docNo('CSETT-',$id);
						$firewallCls->addLog("Customer Settlement Updated: ".$transaction_no);
						
						
						$settlementInfo = $CustomersTransactionsSettlementsQuery->get($id);
						
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
					
					
						////Account transaction update
						$accountData = [];
						$accountData['account_id'] = $data['account_id'];
						$accountData['reference_id'] = $id;
						$accountData['added_date'] = $settlementInfo['added_date'];
						$accountData['transaction_type'] = 'CSETT';
						$accountData['debit'] = $settlementInfo['amount'];
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
	
}
