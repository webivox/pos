<?php

class SalesTransactionKotPrintConnector {

    public function index() {
		
		
		global $db;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			$data['title_tag'] 		= 'KOT | '.$data['companyName'];
			
			
			
			/////////////
			
			$today = $dateCls->todayDate('Y-m-d').' 00:00:00';
			
			
			$getSales = $db->fetchAll("SELECT 
								sales_invoices.invoice_id AS si_invoice_id, 
								sales_invoices.invoice_no AS si_invoice_no, 
								sales_invoices.added_date AS si_added_date, 
								sales_invoices.printed AS si_printed,
								sales_invoices.status AS si_status,
								sales_invoice_items.item_id AS sii_item_id, 
								sales_invoice_items.qty AS sii_qty, 
								 
								inventory_items.kot_item AS ii_kot_item
							FROM 
								sales_invoices
							LEFT JOIN 
								sales_invoice_items 
								ON sales_invoice_items.invoice_id = sales_invoices.invoice_id
							LEFT JOIN 
								inventory_items 
								ON sales_invoice_items.item_id = inventory_items.item_id
							
							WHERE
								inventory_items.kot_item=1	
								AND sales_invoices.status=1
							
						");
			
			///////////
							//WHERE
							
							//sales_invoices.added_date>'".$today."'
			
			
			$data['rows'] = array();
			
			
			foreach($getSales as $cat)
			{
				if($cat['si_printed']==1){ $status='Printed'; }
				else{ $status='Not Printed'; }
				
				$data['rows'][] = array(
										'invoice_id' => $cat['si_invoice_id'],
										'added_date' => date('d-m-Y H:i:s',strtotime($cat['si_added_date'])),
										'invoice_no' => $cat['si_invoice_no'],
										'item' => $InventoryMasterItemsQuery->data($cat['sii_item_id'],'name'),
										'qty' => $defCls->num($cat['sii_qty']),
										'status' => $status,
										'printUrl' => $defCls->genURL('sales/transaction_kot_print/posprint/'.$cat['si_invoice_id'])
									);
											
			}
			
	
			$this_required_file = _HTML.'sales/transaction_kot_print.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	
	
	

    public function posprint() {
	
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $SalesMasterRepQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$invoiceInfo = $SalesTransactionInvoicesQuery->get($id);
		
			if($invoiceInfo)
			{
				$data['status'] = $invoiceInfo['status'];
				
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Sales KOT Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['invoice_no'] = $invoiceInfo['invoice_no'];
				
				$data['added_date'] = date('d-m-Y H:i:s',strtotime($invoiceInfo['added_date']));
				
				$data['cashier'] = $SystemMasterUsersQuery->data($invoiceInfo['user_id'],'name');
				
				$db->query("UPDATE sales_invoices SET printed=1 WHERE invoice_id='".$invoiceInfo['invoice_id']."'");
				
				$data['invoice_items'] = $db->fetchAll("SELECT 
															sales_invoices.invoice_id AS si_invoice_id, 
															sales_invoices.invoice_no AS si_invoice_no, 
															sales_invoices.added_date AS si_added_date, 
															sales_invoices.printed AS si_printed,
															sales_invoice_items.item_id AS sii_item_id, 
															sales_invoice_items.qty AS sii_qty, 
															 
															inventory_items.kot_item AS ii_kot_item
														FROM 
															sales_invoices
														LEFT JOIN 
															sales_invoice_items 
															ON sales_invoice_items.invoice_id = sales_invoices.invoice_id
														LEFT JOIN 
															inventory_items 
															ON sales_invoice_items.item_id = inventory_items.item_id
														
														WHERE
															inventory_items.kot_item=1
															AND sales_invoices.invoice_id='".$invoiceInfo['invoice_id']."'
														
													");
				
				

				$this_required_file = _HTML.'sales/poskotprint.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}
			else
			{
				$error_msg[]="Invalid invoice Id"; $error_no++;
				
				
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
}
