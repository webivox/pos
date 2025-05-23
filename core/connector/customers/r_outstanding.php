<?php

class CustomersROutstandingConnector {

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
			
			$data['titleTag'] 	= 'Customer Outstanding Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('customers/r_outstanding/view/');
			
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/r_outstanding.php";
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
		global $CustomersMasterCustomersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
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
			
			if(isset($_REQUEST['search_customer'])){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			$filter_heading = '';
			if($search_customer){ $filter_heading .= ' | Customer : '.$CustomersMasterCustomersQuery->data($search_customer,'name'); }
			
			$data['title_tag'] = 'Customer Outstanding Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE closing_balance > 0";
			
			
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
	
			$this_required_file = _HTML.'customers/r_outstanding_view.php';
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
