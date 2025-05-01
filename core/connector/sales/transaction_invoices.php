<?php

class SalesTransactionInvoicesConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("sales/transaction_invoices/create");
			$data['load_table_url'] = $defCls->genURL('sales/transaction_invoices/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/transaction_invoices.php";
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_customer_id')!==''){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_id!=0";
			
			if($search_no){ $sql.=" AND invoice_no='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer_id){ $sql.=" AND customer_id='".$search_customer_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SalesTransactionInvoicesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY invoice_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$return = $SalesTransactionInvoicesQuery->gets($sql);
			
			$data['return'] = array();
			
			foreach($return as $cat)
			{
				$data['return'][] = array(
										'invoice_id' => $cat['invoice_id'],
										'invoice_no' => $cat['invoice_no'],
										'added_date' => date('d-m-Y H:i:s',strtotime($cat['added_date'])),
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name'),
										'status' => $defCls->getMasterStatus($cat['total_sale']),
										'total_sale' => $defCls->money($cat['total_sale']),
										'updateURL' => $defCls->genURL('sales/transaction_invoices/edit/'.$cat['invoice_id'])
											);
										
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($return).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'sales/transaction_invoices_table.php';
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
