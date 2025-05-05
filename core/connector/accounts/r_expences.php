<?php

class AccountsRExpencesConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $AccountsMasterExpencestypesQuery;
		global $AccountsMasterPayee;
		global $AccountsTransactionsExpencesQuery;
		global $AccountsMasterPayeeQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('accounts/r_expences/view/');
			
			$data['payee_list']	= $AccountsMasterPayeeQuery->gets("ORDER BY name ASC");
			$data['expences_type_list']	= $AccountsMasterExpencestypesQuery->gets("ORDER BY name ASC");
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['account_list']	= $AccountsMasterAccountsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/r_expences.php";
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
		global $AccountsMasterExpencestypesQuery;
		global $AccountsMasterPayeeQuery;
		global $AccountsTransactionsExpencesQuery;
		global $AccountsMasterAccountsQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			
			$filter_heading = '';
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from');  }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_payee')!==''){ $search_payee=$db->request('search_payee'); }
			else{ $search_payee=''; }
			
			if($db->request('search_expences_type')!==''){ $search_expences_type=$db->request('search_expences_type'); }
			else{ $search_expences_type=''; }
			
			if($db->request('search_location')!==''){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if($db->request('search_account')!==''){ $search_account=$db->request('search_account'); }
			else{ $search_account=''; }
			
			if($db->request('search_user')!==''){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_payee){ $filter_heading .= ' | Payee : '.$AccountsMasterPayeeQuery->data($search_payee,'name'); }
			if($search_expences_type){ $filter_heading .= ' | Type : '.$AccountsMasterExpencestypesQuery->data($search_expences_type,'name'); }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_account){ $filter_heading .= ' | Account : '.$AccountsMasterAccountsQuery->data($search_account,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			
			$data['title_tag'] = 'Expences Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE expence_id != 0";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_payee){ $sql.=" AND payee_id='".$search_payee."'"; }
			if($search_expences_type){ $sql.=" AND expences_type_id='".$search_expences_type."'"; }
			if($search_location){ $sql.=" AND location_id='".$search_location."'"; }
			if($search_account){ $sql.=" AND account_id='".$search_account."'"; }
			if($search_user){ $sql.=" AND user_id='".$search_user."'"; }
			
			///////////
			
			$sql.="  ORDER BY expence_id DESC";
			
			$getRows = $AccountsTransactionsExpencesQuery->gets($sql);
			
			$data['rows'] = array();
			
			$amount = 0;
			
			foreach($getRows as $cat)
			{
			
				$amount += $cat['amount'];
				
				$data['rows'][] = array(
										'no' => $defCls->docNo('AEXP-',$cat['expence_id']),
										'payee' => $AccountsMasterPayeeQuery->data($cat['payee_id'],'name'),
										'expences_type' => $AccountsMasterExpencestypesQuery->data($cat['expences_type_id'],'name'),
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'amount' => $defCls->money($cat['amount']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'account' => $AccountsMasterAccountsQuery->data($cat['account_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name')
									);
											
			}
			
			$data['amount'] = $defCls->money($amount);

	
			$this_required_file = _HTML.'accounts/r_expences_view.php';
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
