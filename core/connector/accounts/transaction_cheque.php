<?php

class AccountsTransactionChequeConnector {

    public function index() {
		
		require_once _QUERY."accounts/transaction_cheque.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionChequeQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Cheque | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("accounts/transaction_cheque/create");
			$data['load_table_url'] = $defCls->genURL('accounts/transaction_cheque/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/transaction_cheque.php";
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
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionChequeQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_cheque_no'])){ $search_cheque_no=$db->request('search_cheque_no'); }
			else{ $search_cheque_no=''; }
			
			if(isset($_REQUEST['search_cheque_type'])){ $search_cheque_type=$db->request('search_cheque_type'); }
			else{ $search_cheque_type=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE cheque_id!=0";
			
			if($search_cheque_type){ $sql.=" AND type='".$search_cheque_type."'"; }
			if($search_cheque_no){ $sql.=" AND cheque_no='".$search_cheque_no."'"; }
			if($search_status!==''){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $AccountsTransactionChequeQuery->getPagination($sql,$pageno);
			
			$data['account_list'] = $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			
			$sql.="  ORDER BY cheque_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$cheques = $AccountsTransactionChequeQuery->gets($sql);
			
			$data['cheques'] = array();
			
			foreach($cheques as $cheque)
			{
				
				if($cheque['status']==1){ $status = 'Realized'; }
				else{ $status = 'Pending'; }
				
				if($cheque['type']=='Received'){ $bank_code = $cheque['bank_code']; }
				elseif($cheque['type']=='Issued'){ $bank_code = $AccountsMasterAccountsQuery->data($cheque['bank_code'],'name'); }
				
				$data['cheques'][] = array(
										'cheque_id' => $cheque['cheque_id'],
										'reference_id' => $cheque['reference_id'],
										'added_date' => $dateCls->showDate($cheque['added_date']),
										'type' => $cheque['type'],
										'bank_code' => $bank_code,
										'cheque_date' => $dateCls->showDate($cheque['cheque_date']),
										'cheque_no' => $cheque['cheque_no'],
										'amount' => $defCls->money($cheque['amount']),
										'remarks' => $cheque['remarks'],
										'deposited_account_id' => $cheque['deposited_account_id'],
										'status' => $status
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($cheques).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'accounts/transaction_cheque_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
    
    public function update() {
		
		global $id;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionChequeQuery;
		global $AccountsMasterAccountsQuery;
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$todayDate = $dateCls->todayDate('Y-m-d');
			
			if(isset($_REQUEST['accountId'])){ $accountId=$db->request('accountId'); }
			else{ $accountId=''; }
			
			if(isset($_REQUEST['realized_date'])){ $realized_date=$db->request('realized_date'); }
			else{ $realized_date=''; }
			
			$chequeInfo = $AccountsTransactionChequeQuery->get($id);
			
			if($chequeInfo && $chequeInfo['status']==0)
			{
				
				if($chequeInfo['type'] == 'Received')
				{
					if($chequeInfo['deposited_account_id']==0 && $chequeInfo['status']==0 && $AccountsMasterAccountsQuery->has($accountId))
					{
						$data['cheque_id'] = $id;
						$data['accountId'] = $accountId;
						$data['realized_date'] = $realized_date;
						
						if($AccountsTransactionChequeQuery->chequeStatusUpdate($data))
						{
							
							////Account transaction update
							$accountData = [];
							$accountData['account_id'] = $accountId;
							$accountData['reference_id'] = $id;
							$accountData['added_date'] = $data['realized_date'];
							$accountData['realized_date'] = $data['realized_date'];
							$accountData['transaction_type'] = 'CHEQUE';
							$accountData['debit'] = $chequeInfo['amount'];
							$accountData['credit'] = 0;
							$accountData['remarks'] = $chequeInfo['remarks'].' Cheque Realized';
							
							$AccountsMasterAccountsQuery->transactionAdd($accountData);
							$json['success']=true;
							
						}
						else
						{
							$error_msg[]="Error found"; $error_no++;
						}
						
					}
					else
					{
						$error_msg[]="Invalid account id"; $error_no++;
					}
				}
				elseif($chequeInfo['type'] == 'Issued')
				{
					$data['cheque_id'] = $id;
					$data['accountId'] = $chequeInfo['deposited_account_id'];
					$data['realized_date'] = $realized_date;
					
					
					if($AccountsTransactionChequeQuery->chequeStatusUpdate($data))
					{
						
						////Account transaction update
						$accountData = [];
						$accountData['account_id'] = $data['accountId'];
						$accountData['reference_id'] = $id;
						$accountData['added_date'] = $data['realized_date'];
						$accountData['realized_date'] = $data['realized_date'];
						$accountData['transaction_type'] = 'CHEQUE';
						$accountData['debit'] = 0;
						$accountData['credit'] = $chequeInfo['amount'];
						$accountData['remarks'] = $chequeInfo['remarks'].' Cheque Realized';
						
						$AccountsMasterAccountsQuery->transactionAdd($accountData);
						$json['success']=true;
						
					}
					else
					{
						$error_msg[]="Error found"; $error_no++;
					}
				}
				else
				{
					$error_msg[]="Invalid status"; $error_no++;
				}
				
				
			}
			else
			{
				$error_msg[]="Error Found: Invalid cheque id or status"; $error_no++;
				
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
	}
	
    
    public function revertupdate() {
		
		global $id;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionChequeQuery;
		global $AccountsMasterAccountsQuery;
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		$json = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$todayDate = $dateCls->todayDate('Y-m-d');
			
			$chequeInfo = $AccountsTransactionChequeQuery->get($id);
			
			if($chequeInfo && $chequeInfo['status']==1)
			{
				if($AccountsTransactionChequeQuery->chequeRevert($id))
				{
				
					////Account transactipn update
					
					$AccountsMasterAccountsQuery->transactionDelete($id,'CHEQUE');
						$json['success']=true;
				
				}
				else
				{
					$error_msg[]="Invalid status"; $error_no++;
				}
				
				
			}
			else
			{
				$error_msg[]="Error Found: Invalid cheque id or status"; $error_no++;
				
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
	}
	
	
	
	
	
	

    public function delete() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $SystemMasterUsersQuery->get($id);
			
			if($getInfo)
			{
				$name = $getInfo['name'];
				
				$deleteValue = $SystemMasterUsersQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("User Deleted: ".$name);
				
					$json['success']=true;
					$json['success_msg']="Sucessfully Updated";
				
				}
				elseif(is_array($deleteValue))
				{
					foreach($deleteValue as $v)
					{
						$error_msg[]=$v; $error_no++;
					}
					
				}
				else
				{
					$error_msg[]="An error occurred while attempting to delete the user!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid user Id"; $error_no++;
				
				
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
			header("location:"._SERVER);
		}
		
	}
		
}
