<?php

class CustomersRLedgerConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Customer Ledger Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('customers/r_ledger/view/');
			
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/r_ledger.php";
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
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_location'])){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if(isset($_REQUEST['search_customer'])){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			if(isset($_REQUEST['search_user'])){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_customer){ $filter_heading .= ' | Customer : '.$CustomersMasterCustomersQuery->data($search_customer,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			
			$data['title_tag'] = 'Customer Ledger Listing Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE transaction_id != 0";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			$sql.=" AND customer_id='".$search_customer."'";
			
			///////////
			
			$sql.="  ORDER BY added_date ASC";
			
			$getRows = $CustomersMasterCustomersQuery->transactionGets($sql);
			
			$data['rows'] = array();
			
			$tDebit = 0;
			$tCredit = 0;
			$tBalance = 0;
			
			foreach($getRows as $cat)
			{
				
				$tDebit += $cat['debit'];
				$tCredit += $cat['credit'];
				$tBalance += $cat['balance'];
				
				$data['rows'][] = array(
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'remarks' => $defCls->showText($cat['remarks']),
										'debit' => $defCls->money($cat['debit']),
										'credit' => $defCls->money($cat['credit']),
										'balance' => $defCls->money($cat['balance']),
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name')
									);
											
			}
			
			$data['tDebit'] = $defCls->money($tDebit);
			$data['tCredit'] = $defCls->money($tCredit);
			$data['tBalance'] = $defCls->money($tDebit-$tCredit);

	
			$this_required_file = _HTML.'customers/r_ledger_view.php';
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
