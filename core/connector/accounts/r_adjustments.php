<?php

class AccountsRAdjustmentsConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsTransactionsAdjustmentsQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('accounts/r_adjustments/view/');
			
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/r_adjustments.php";
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
		global $AccountsTransactionsAdjustmentsQuery;
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
			
			if($db->request('search_type')!==''){ $search_type=$db->request('search_type'); }
			else{ $search_type=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_location')!==''){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if($db->request('search_account')!==''){ $search_account=$db->request('search_account'); }
			else{ $search_account=''; }
			
			if($db->request('search_user')!==''){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_type){ $filter_heading .= ' | Type : '.$search_type; }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_account){ $filter_heading .= ' | Account : '.$AccountsMasterAccountsQuery->data($search_account,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			
			$data['title_tag'] = 'Account Adjustment Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE adjustment_id != 0";
			
			if($search_type){ $sql.=" AND type='".$search_type."'"; }
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND location_id='".$search_location."'"; }
			if($search_account){ $sql.=" AND account_id='".$search_account."'"; }
			if($search_user){ $sql.=" AND user_id='".$search_user."'"; }
			
			///////////
			
			$sql.="  ORDER BY adjustment_id DESC";
			
			$getRows = $AccountsTransactionsAdjustmentsQuery->gets($sql);
			
			$data['rows'] = array();
			
			$amount = 0;
			
			foreach($getRows as $cat)
			{
			
				$amount += $cat['amount'];
				
				$data['rows'][] = array(
										'no' => $defCls->docNo('AADJ-',$cat['adjustment_id']),
										'type' => $defCls->showText($cat['type']),
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'amount' => $defCls->money($cat['amount']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'account' => $AccountsMasterAccountsQuery->data($cat['account_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name')
									);
											
			}
			
			$data['amount'] = $defCls->money($amount);

	
			$this_required_file = _HTML.'accounts/r_adjustments_view.php';
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
