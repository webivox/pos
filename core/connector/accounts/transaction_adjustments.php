<?php

class AccountsTransactionAdjustmentsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsMasterPayeeQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("accounts/transaction_adjustments/create");
			$data['load_table_url'] = $defCls->genURL('accounts/transaction_adjustments/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/transaction_adjustments.php";
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
		global $SystemMasterUsersQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsTransactionsAdjustmentsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('AADJ-','',$search_no);
				$search_no=ltrim($search_no,'AADJ-');
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
			
			if($search_no){ $sql.=" AND adjustment_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_account_id){ $sql.=" AND account_id='".$search_account_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsTransactionsAdjustmentsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY adjustment_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$adjustments = $AccountsTransactionsAdjustmentsQuery->gets($sql);
			
			$data['adjustments'] = array();
			
			foreach($adjustments as $cat)
			{
				$data['adjustments'][] = array(
										'adjustment_id' => $cat['adjustment_id'],
										'adjustment_no' => $defCls->docNo('AADJ-',$cat['adjustment_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'account_id' => $AccountsMasterAccountsQuery->data($cat['account_id'],'name'),
										'type' => $cat['type'],
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('accounts/transaction_adjustments/edit/'.$cat['adjustment_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($adjustments).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/transaction_adjustments_table.php';
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
		global $SystemMasterUsersQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsTransactionsAdjustmentsQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."accounts/transaction_adjustments/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			
			$data['adjustment_no'] = 'New';
			
			if($db->request('type')){ $data['type'] = $db->request('type'); }
			else{ $data['type'] = ''; }
			
			if($db->request('adjustments_type_id')){ $data['adjustments_type_id'] = $db->request('adjustments_type_id'); }
			else{ $data['adjustments_type_id'] = ''; }
			
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
				
				if(!$data['type']){ $error_msg[]="You must choose a type"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $AccountsTransactionsAdjustmentsQuery->create($data);
					
					$transaction_no = $defCls->docNo('AADJ-',$createdId);
					$firewallCls->addLog("Account Adjustment Created: ".$transaction_no);
					
					$adjustmentInfo = $AccountsTransactionsAdjustmentsQuery->get($createdId);
					
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $adjustmentInfo['added_date'];
					$accountData['account_id'] = $data['account_id'];
					$accountData['reference_id'] = $createdId;
					$accountData['transaction_type'] = 'AADJ';
					
					 if($data['type']=='Credit')
					 {
					 	$accountData['debit'] = $adjustmentInfo['amount'];
					 	$accountData['credit'] = 0;
					 }
					 elseif($data['type']=='Debit')
					 {
					 	$accountData['debit'] = 0;
					 	$accountData['credit'] = $adjustmentInfo['amount'];
					 }
					 
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
	
				$this_required_file = _HTML.'accounts/transaction_adjustments_form.php';
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
		global $SystemMasterUsersQuery;
		global $AccountsMasterPayeeQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsTransactionsAdjustmentsQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $AccountsTransactionsAdjustmentsQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."accounts/transaction_adjustments/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['adjustment_id'] = $getDebitNoteInfo['adjustment_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
					
				$data['adjustment_no'] = $defCls->docNo('AADJ-',$getDebitNoteInfo['adjustment_id']);
			
				if($db->request('type')){ $data['type'] = $db->request('type'); }
				else{ $data['type'] = $getDebitNoteInfo['type']; }
				
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
					
					if(!$data['type']){ $error_msg[]="You must choose a type"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$AccountsMasterAccountsQuery->has($data['account_id'])){ $error_msg[]="You must choose a account"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$AccountsTransactionsAdjustmentsQuery->edit($data);
						$transaction_no = $defCls->docNo('AADJ-',$id);
						$firewallCls->addLog("Account Adjustment Updated: ".$transaction_no);
						
						
						$adjustmentInfo = $AccountsTransactionsAdjustmentsQuery->get($id);
						
						////Account transactipn update
						$accountData = [];
						$accountData['added_date'] = $adjustmentInfo['added_date'];
						$accountData['account_id'] = $adjustmentInfo['account_id'];
						$accountData['reference_id'] = $adjustmentInfo['adjustment_id'];
						$accountData['transaction_type'] = 'AADJ';
						
						if($data['type']=='Credit')
						 {
							$accountData['debit'] = $adjustmentInfo['amount'];
							$accountData['credit'] = 0;
						 }
						 elseif($data['type']=='Debit')
						 {
							$accountData['debit'] = 0;
							$accountData['credit'] = $adjustmentInfo['amount'];
						 }
						
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
		
					$this_required_file = _HTML.'accounts/transaction_adjustments_form.php';
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
				$error_msg[]="Invalid adjustment Id"; $error_no++;
					
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
