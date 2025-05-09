<?php

class AccountsRChequesConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Cheque Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('accounts/r_cheques/view/');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/r_cheques.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function view()
	{
		global $db;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			if(isset($_REQUEST['search_cheque_no'])){
				$search_cheque_no=$db->request('search_cheque_no');
			}
			else{ $search_cheque_no=''; }
			
			if(isset($_REQUEST['search_added_date_from'])){ $search_added_date_from=$db->request('search_added_date_from'); }
			else{ $search_added_date_from=''; }
			
			if(isset($_REQUEST['search_added_date_to'])){ $search_added_date_to=$db->request('search_added_date_to'); }
			else{ $search_added_date_to=''; }
			
			if(isset($_REQUEST['search_cheque_date_from'])){ $search_cheque_date_from=$db->request('search_cheque_date_from'); }
			else{ $search_cheque_date_from=''; }
			
			if(isset($_REQUEST['search_cheque_date_to'])){ $search_cheque_date_to=$db->request('search_cheque_date_to'); }
			else{ $search_cheque_date_to=''; }
			
			if(isset($_REQUEST['search_realized_date_from'])){ $search_realized_date_from=$db->request('search_realized_date_from'); }
			else{ $search_realized_date_from=''; }
			
			if(isset($_REQUEST['search_realized_date_to'])){ $search_realized_date_to=$db->request('search_realized_date_to'); }
			else{ $search_realized_date_to=''; }
			
			if(isset($_REQUEST['search_type'])){ $search_type=$db->request('search_type'); }
			else{ $search_type=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
					
			if($search_status == 1){ $statusTxt = 'Realized'; }
			else{ $statusTxt = 'Pending'; }
	
			$filter_heading = '';
			if($search_added_date_from){ $filter_heading .= ' | Added Date From : '.$search_added_date_from; }
			if($search_added_date_to){ $filter_heading .= ' | Added Date To : '.$search_added_date_to; }
			if($search_cheque_date_from){ $filter_heading .= ' | Cheque Date From : '.$search_cheque_date_from; }
			if($search_cheque_date_to){ $filter_heading .= ' | Cheque Date To : '.$search_cheque_date_to; }
			if($search_realized_date_from){ $filter_heading .= ' | Realized Date From : '.$search_realized_date_from; }
			if($search_realized_date_to){ $filter_heading .= ' | Realized Date To : '.$search_realized_date_to; }
			if($search_type){ $filter_heading .= ' | Type : '.$search_type; }
			if($search_status){ $filter_heading .= ' | Status : '.$statusTxt; }
			
			$data['title_tag'] = 'Cheque Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE cheque_id != 0";
			
			if($search_cheque_no){ $sql.=" AND cheque_no='".$search_cheque_no."'"; }
			
			if($search_added_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_added_date_from)."'"; }
			if($search_added_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_added_date_to)."'"; }
			
			if($search_cheque_date_from){ $sql.=" AND DATE(cheque_date)>='".$dateCls->dateToDB($search_cheque_date_from)."'"; }
			if($search_cheque_date_to){ $sql.=" AND DATE(cheque_date)<='".$dateCls->dateToDB($search_cheque_date_to)."'"; }
			
			if($search_realized_date_from){ $sql.=" AND DATE(realized_date)>='".$dateCls->dateToDB($search_realized_date_from)."'"; }
			if($search_realized_date_to){ $sql.=" AND DATE(realized_date)<='".$dateCls->dateToDB($search_realized_date_to)."'"; }
			
			if($search_type){ $sql.=" AND `type`='".$search_type."'"; }
			
			if($search_status==0 || $search_status==1){ $sql.=" AND `status`='".$search_status."'"; }
			
			$sql.="  ORDER BY cheque_id ASC";
			
			$getRows = $AccountsTransactionChequeQuery->gets($sql);
				
			$data['rows'] = array();
				
			$tAmount = 0;
			
			if(count($getRows))
			{
				
				foreach($getRows as $cat)
				{
					
					$tAmount += $cat['amount'];
					
					$usedDetails = '';
					
					if($cat['realized_date'] == '0000-00-00' || empty($cat['realized_date'])){ $realizedDate = date('d-m-Y',strtotime($cat['realized_date'])); }
					else{ $realizedDate = ''; }
					
					if($cat['type'] == 'Received')
					{
						$bankCode = $defCls->showText($cat['bank_code']);
						
						if($cat['deposited_account_id']){ $account = $AccountsMasterAccountsQuery->data($cat['deposited_account_id'],'name'); }
						else{ $account = ''; }
					}
					else
					{
						$bankCode = '';
						$account = $AccountsMasterAccountsQuery->data($cat['bank_code'],'name');
					}
					
					if($cat['status'] == 1){ $status = 'Realized'; }
					else{ $status = ''; }
					
					
					$data['rows'][] = array(
											'chequeId' => $defCls->showText($cat['cheque_id']),
											'referenceId' => $defCls->showText($cat['reference_id']),
											'addedDate' => date('d-m-Y',strtotime($cat['added_date'])),
											'transactionType' => $defCls->showText($cat['transaction_type']),
											'type' => $defCls->showText($cat['type']),
											'bankCode' => $bankCode,
											'chequeDate' => date('d-m-Y',strtotime($cat['cheque_date'])),
											'realizedDate' => $realizedDate,
											'chequeNo' => $defCls->showText($cat['cheque_no']),
											'amount' => $defCls->money($cat['amount']),
											'remarks' => $defCls->showText($cat['remarks']),
											'depositedAccount' => $defCls->showText($account),
											'status' => $status
										);
												
				}
			}
				
			$data['tAmount'] = $defCls->money($tAmount);
				
			$this_required_file = _HTML.'accounts/r_cheques_view.php';
			
			
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	
}
