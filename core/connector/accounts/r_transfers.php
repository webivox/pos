<?php

class AccountsRTransfersConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsTransfersQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('accounts/r_transfers/view/');
			
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/r_transfers.php";
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
		global $AccountsTransactionsTransfersQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_location')!==''){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if($db->request('search_account_from')!==''){ $search_account_from=$db->request('search_account_from'); }
			else{ $search_account_from=''; }
			
			if($db->request('search_account_to')!==''){ $search_account_to=$db->request('search_account_to'); }
			else{ $search_account_to=''; }
			
			if($db->request('search_user')!==''){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_account_from){ $filter_heading .= ' | Account From : '.$AccountsMasterAccountsQuery->data($search_account_from,'name'); }
			if($search_account_to){ $filter_heading .= ' | Account To : '.$AccountsMasterAccountsQuery->data($search_account_to,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			
			$data['title_tag'] = 'Account Transfer Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE transfer_id != 0";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND location_id='".$search_location."'"; }
			if($search_account_from){ $sql.=" AND account_from_id='".$search_account_from."'"; }
			if($search_account_to){ $sql.=" AND account_to_id='".$search_account_to."'"; }
			if($search_user){ $sql.=" AND user_id='".$search_user."'"; }
			
			///////////
			
			$sql.="  ORDER BY transfer_id DESC";
			
			$getRows = $AccountsTransactionsTransfersQuery->gets($sql);
			
			$data['rows'] = array();
			
			$amount = 0;
			
			foreach($getRows as $cat)
			{
			
				$amount += $cat['amount'];
				
				$data['rows'][] = array(
										'no' => $defCls->docNo('ATRN-',$cat['transfer_id']),
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'amount' => $defCls->money($cat['amount']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'account_from' => $AccountsMasterAccountsQuery->data($cat['account_from_id'],'name'),
										'account_to' => $AccountsMasterAccountsQuery->data($cat['account_to_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name')
									);
											
			}
			
			$data['amount'] = $defCls->money($amount);

	
			$this_required_file = _HTML.'accounts/r_transfers_view.php';
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
