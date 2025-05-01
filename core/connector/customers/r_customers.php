<?php

class CustomersRCustomersConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('customers/r_customers/view/');
			
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/r_customers.php";
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
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
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
			
			if($db->request('search_customer')!==''){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			/////////////
			
			$sql=" WHERE customer_id != 0";
			
			
			if($search_customer){ $sql.=" AND customer_id='".$search_customer."'"; }
			
			///////////
			
			$sql.="  ORDER BY name ASC";
			
			$getRows = $CustomersMasterCustomersQuery->gets($sql);
			
			$data['rows'] = array();
			
			$totalOutstanding = 0;
			
			foreach($getRows as $cat)
			{
			
				$data['rows'][] = array(
										'no' => $defCls->docNo('',$cat['customer_id']),
										'group' => $CustomersMasterCustomergroupsQuery->data($cat['customer_group_id'],'name'),
										'name' => $defCls->showText($cat['name']),
										'phone' => $cat['phone_number'],
										'email' => $cat['email'],
										'address' => $cat['address'],
										'credit_limit' => $defCls->money($cat['credit_limit']),
										'settlement_days' => $defCls->num($cat['settlement_days']),
										'card_no' => $cat['card_no'],
										'closing_balance' => $defCls->money($cat['closing_balance']),
										'loyalty_points' => $defCls->num($cat['loyalty_points']),
										'status' => $defCls->getMasterStatus($cat['status'])
									);
				
				$totalOutstanding+=	$cat['closing_balance'];					
			}
			
			$data['totalOutstanding'] = $defCls->money($totalOutstanding);
	
			$this_required_file = _HTML.'customers/r_customers_view.php';
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
