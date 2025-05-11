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
			
			$data['titleTag'] 	= 'Invoices | '.$defCls->master('companyName');
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
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_customer_id'])){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
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
										'status' => $cat['status'],
										'total_sale' => $defCls->money($cat['total_sale']),
										'updateURL' => $defCls->genURL('sales/transaction_invoices/edit/'.$cat['invoice_id']),
										'printURL' => $defCls->genURL('sales/screen/posprint/'.$cat['invoice_id'])
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
	
    public function cancellation() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInvoiceInfo = $SalesTransactionInvoicesQuery->get($id);
			
			if($getInvoiceInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."suppliers/master_suppliers/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['invoice_id'] = $getInvoiceInfo['invoice_id'];
				
				$SalesTransactionInvoicesQuery->cancellation($data);
				$firewallCls->addLog("Invoice Cancelled: ".$getInvoiceInfo['invoice_no']);
				
				$json['success']=true;
				$json['success_msg']="Sucessfully Updated";
					
				echo json_encode($json);
				
			}
			else
			{
				$error_msg[]="Invalid invoice Id"; $error_no++;
					
				if($error_no)
				{
					
					$error_msg_list='';
					foreach($error_msg as $e)
					{
						if($e)
						{
							$error_msg_list.='<li>'.$e.'</li>';
						}
					}
					$json['error']=true;
					$json['error_msg']=$error_msg_list;
				}
				echo json_encode($json);
				
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	
/*
    public function cancellation() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesMasterRepQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInvoiceInfo = $SalesTransactionInvoicesQuery->get($id);
			
			
			if($getInvoiceInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."sales/transaction_invoices/edit/".$id;
				$data['customer_create_url'] = $defCls->genURL("customers/master_customers/create");
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['invoice_id'] = $getInvoiceInfo['invoice_id'];
			
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['cashierpoints_list'] = $SystemMasterCashierpointsQuery->gets("ORDER BY name ASC");
				$data['sales_rep_list'] = $SalesMasterRepQuery->gets("ORDER BY name ASC");
					
				$data['invoice_no'] = $getInvoiceInfo['invoice_no'];
					
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getInvoiceInfo['location_id']; }
					
				$data['user'] = $SystemMasterUsersQuery->data($getInvoiceInfo['user_id'],'name');
					
				if($db->request('cashier_point_id')){ $data['cashier_point_id'] = $db->request('cashier_point_id'); }
				else{ $data['cashier_point_id'] = $getInvoiceInfo['cashier_point_id']; }
					
				if($db->request('customer_id'))
				{
					$data['customer_id'] = $db->request('customer_id');
					$data['customer_id_txt'] = $CustomersMasterCustomersQuery->data($data['customer_id'],'name');
				}
				else
				{
					$data['customer_id'] = $getInvoiceInfo['customer_id'];
					$data['customer_id_txt'] = $CustomersMasterCustomersQuery->data($data['customer_id'],'name');
				}
					
				if($db->request('sales_rep_id')){ $data['sales_rep_id'] = $db->request('sales_rep_id'); }
				else{ $data['sales_rep_id'] = $getInvoiceInfo['sales_rep_id']; }
					
				if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $getInvoiceInfo['added_date']; }
					
				$data['no_of_items'] = $getInvoiceInfo['no_of_items'];
					
				$data['no_of_qty'] = $getInvoiceInfo['no_of_qty'];
					
				$data['total_sale'] = $getInvoiceInfo['total_sale'];
					
				if($db->request('discount_type')){ $data['discount_type'] = $db->request('discount_type'); }
				else{ $data['discount_type'] = $getInvoiceInfo['discount_type']; }
					
				if($db->request('discount_value')){ $data['discount_value'] = $db->request('discount_value'); }
				else{ $data['discount_value'] = $getInvoiceInfo['discount_value']; }
					
				if($db->request('discount_amount')){ $data['discount_amount'] = $db->request('discount_amount'); }
				else{ $data['discount_amount'] = $getInvoiceInfo['discount_amount']; }
					
				$data['total_sale_cost'] = $getInvoiceInfo['total_sale_cost'];
					
				$data['total_paid'] = $getInvoiceInfo['total_paid'];
					
				$data['cash_sales'] = $getInvoiceInfo['cash_sales'];
					
				$data['card_sales'] = $getInvoiceInfo['card_sales'];
					
				$data['return_sales'] = $getInvoiceInfo['return_sales'];
					
				$data['gift_card_sales'] = $getInvoiceInfo['gift_card_sales'];
					
				$data['loyalty_sales'] = $getInvoiceInfo['loyalty_sales'];
					
				$data['credit_sales'] = $getInvoiceInfo['credit_sales'];
					
				$data['cheque_sales'] = $getInvoiceInfo['cheque_sales'];
					
				if($db->request('comments')){ $data['comments'] = $db->request('comments'); }
				else{ $data['comments'] = $getInvoiceInfo['comments']; }
					
				if($db->request('status')){ $data['status'] = $db->request('status'); }
				else{ $data['status'] = $getInvoiceInfo['status']; }
				
				
				
				
				$data['item_lists'] = $SalesTransactionInvoicesQuery->getItems("WHERE invoice_id='".$id."' ORDER BY invoice_item_id ASC");
				
				

				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location from"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
					$noofItems = 0;
					$data['items'] = [];
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_qty = $_REQUEST['added_qty'];
						$added_amount = $_REQUEST['added_amount'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
							
							$adiscount = isset($added_discount[$index]) ? $added_discount[$index] : 0;
							
							$discCalc = $aamount*$adiscount/100;
							
							$afinalAmount = $aamount-$discCalc;
							
							if(!$InventoryMasterItemsQuery->has($aitemId))
							{
								$error_msg[]="Invalid item found!"; $error_no++;
							}
							elseif(!$aqty || !$aamount)
							{
								$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty or amount"; $error_no++;
							}
							else
							{
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'qty' => $aqty,
															'amount' => $aamount,
															'discount' => $adiscount,
															'finalAmount' => $afinalAmount,
															'total' => $afinalAmount*$aqty
								
													);
								$noofItems+=1;
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['invoice_item_id']]) ? $_REQUEST['eqty'.$i['invoice_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['invoice_item_id']]) ? $_REQUEST['eamount'.$i['invoice_item_id']] : 0;
						$ediscount = isset($_REQUEST['ediscount'.$i['invoice_item_id']]) ? $_REQUEST['ediscount'.$i['invoice_item_id']] : 0;
						
						$discCalc = $eamount*$ediscount/100;
							
						$efinalAmount = $eamount-$discCalc;
							
						
						
						if($eqty)
						{
						
							$data['eitems'][] = array(
							
														'invoice_item_id' => $i['invoice_item_id'],
														'itemId' => $i['item_id'],
														'qty' => $eqty,
														'amount' => $eamount,
														'discount' => $ediscount,
														'finalAmount' => $efinalAmount,
														'total' => $efinalAmount*$eqty
							
												);
							$noofItems+=1;
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'invoice_item_id' => $i['invoice_item_id'],
														'qty' => 0
							
												);
							
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$SalesTransactionInvoicesQuery->edit($data);
						$SalesTransactionInvoicesQuery->runTotal($id);
						$transaction_no = $defCls->docNo('QTE-',$id);
						$firewallCls->addLog("Invoices Updated: ".$transaction_no);
						
						$json['success']=true;
						$json['success_msg']="Sucessfully Updated";
						
					}
					
					if($error_no)
					{
						
						$error_msg_list='';
						foreach($error_msg as $e)
						{
							if($e)
							{
								$error_msg_list.='<li>'.$e.'</li>';
							}
						}
						$json['error']=true;
						$json['error_msg']=$error_msg_list;
					}
					echo json_encode($json);
					
				}
				else
				{
		
					$this_required_file = _HTML.'sales/transaction_invoices_form.php';
					if (!file_exists($this_required_file)) {
						error_log("File not found: ".$this_required_file);
						die('File not found:'.$this_required_file);
					}
					else {
		
						require_once($this_required_file);
						
					}
				}	
			}
			else
			{
				$error_msg[]="Invalid invoice Id"; $error_no++;
					
				if($error_no)
				{
					
					$error_msg_list='';
					foreach($error_msg as $e)
					{
						if($e)
						{
							$error_msg_list.='<li>'.$e.'</li>';
						}
					}
					$json['error']=true;
					$json['error_msg']=$error_msg_list;
				}
				echo json_encode($json);
				
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	*/
}
