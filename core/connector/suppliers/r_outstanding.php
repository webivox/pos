<?php

class SuppliersROutstandingConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('suppliers/r_outstanding/view/');
			
			$data['supplier_list']	= $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."suppliers/r_outstanding.php";
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
		global $SuppliersMasterSuppliersQuery;
		global $SuppliersMasterSuppliergroupsQuery;
		
		
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
			
			if($db->request('search_supplier')!==''){ $search_supplier=$db->request('search_supplier'); }
			else{ $search_supplier=''; }
			
			/////////////
			
			$sql=" WHERE closing_balance > 0";
			
			
			if($search_supplier){ $sql.=" AND supplier_id='".$search_supplier."'"; }
			
			///////////
			
			$sql.="  ORDER BY name ASC";
			
			$getRows = $SuppliersMasterSuppliersQuery->gets($sql);
			
			$data['rows'] = array();
			
			$totalOutstanding = 0;
			
			foreach($getRows as $cat)
			{
			
				$data['rows'][] = array(
										'no' => $defCls->docNo('',$cat['supplier_id']),
										'name' => $defCls->showText($cat['name']),
										'contact_person' => $defCls->showText($cat['contact_person']),
										'email' => $cat['email'],
										'phone' => $cat['phone_number'],
										'address' => $cat['address'].', '.$cat['city'].', '.$cat['state'].', '.$cat['country'],
										'payment_terms' => $cat['payment_terms'],
										'bank_details' => $cat['bank_details'],
										'tax_number' => $cat['tax_number'],
										'closing_balance' => $defCls->money($cat['closing_balance']),
										'status' => $defCls->getMasterStatus($cat['status'])
									);
				
				$totalOutstanding+=	$cat['closing_balance'];					
			}
			
			$data['totalOutstanding'] = $defCls->money($totalOutstanding);
	
			$this_required_file = _HTML.'suppliers/r_outstanding_view.php';
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
