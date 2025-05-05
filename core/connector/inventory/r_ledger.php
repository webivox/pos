<?php

class InventoryRLedgerConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('inventory/r_ledger/view/');
			
			$data['item_list']	= $InventoryMasterItemsQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/r_ledger.php";
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
		global $InventoryMasterItemsQuery;
		global $stockTransactionsCls;
		
		
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
			
			if($db->request('search_item')!==''){ $search_item=$db->request('search_item'); }
			else{ $search_item=0; }
			
			$filter_heading = '';
			if($search_item){ $filter_heading .= ' | Item : '.$InventoryMasterItemsQuery->data($search_item,'name'); }
			
			$data['title_tag'] = 'Item Ledger Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			
			/////////////
			
			$sql=" WHERE transaction_id != 0";
			
			$sql.=" AND item_id='".$search_item."'";
			
			///////////
			
			$sql.="  ORDER BY added_date ASC";
			
			$getRows = $stockTransactionsCls->transactionGets($sql);
			
			$data['rows'] = array();
			
			$tDebit = 0;
			$tCredit = 0;
			$tBalance = 0;
			
			foreach($getRows as $cat)
			{
				
				$tDebit += $cat['qty_in'];
				$tCredit += $cat['qty_out'];
				$tBalance += $cat['stock_balance'];
				
				$data['rows'][] = array(
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'transaction_type' => $defCls->showText($cat['transaction_type']),
										'remarks' => $defCls->showText($cat['remarks']),
										'amount' => $defCls->money($cat['amount']),
										'debit' => $defCls->money($cat['qty_in']),
										'credit' => $defCls->money($cat['qty_out']),
										'balance' => $defCls->money($cat['stock_balance'])
									);
											
			}
			
			$data['tDebit'] = $defCls->money($tDebit);
			$data['tCredit'] = $defCls->money($tCredit);
			$data['tBalance'] = $defCls->money($tDebit-$tCredit);

	
			$this_required_file = _HTML.'inventory/r_ledger_view.php';
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
