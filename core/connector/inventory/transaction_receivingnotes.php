<?php

class InventoryTransactionReceivingnotesConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Good Receiving | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/transaction_receivingnotes/create");
			$data['load_table_url'] = $defCls->genURL('inventory/transaction_receivingnotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/transaction_receivingnotes.php";
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('RN-','',$search_no);
				$search_no=ltrim($search_no,'RN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_supplier_id'])){ $search_supplier_id=$db->request('search_supplier_id'); }
			else{ $search_supplier_id=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE supplier_id!=0";
			
			if($search_no){ $sql.=" AND receiving_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_supplier_id){ $sql.=" AND supplier_id='".$search_supplier_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryTransactionsReceivingnotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY receiving_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$receivingnotes = $InventoryTransactionsReceivingnotesQuery->gets($sql);
			
			$data['receivingnotes'] = array();
			
			foreach($receivingnotes as $cat)
			{
				$data['receivingnotes'][] = array(
										'receiving_note_id' => $cat['receiving_note_id'],
										'rn_no' => $defCls->docNo('RN-',$cat['receiving_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'supplier_id' => $SuppliersMasterSuppliersQuery->data($cat['supplier_id'],'name'),
										'items' => $defCls->num($cat['no_of_items']).'/'.$defCls->num($cat['no_of_qty']),
										'total_value' => $defCls->money($cat['total_value']),
										'updateURL' => $defCls->genURL('inventory/transaction_receivingnotes/edit/'.$cat['receiving_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($receivingnotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/transaction_receivingnotes_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}

    public function create() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		global $InventoryMasterItemsQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/transaction_receivingnotes/create";
			$data['supplier_create_url'] = $defCls->genURL("suppliers/master_suppliers/create");
			$data['item_create_url'] = $defCls->genURL("inventory/master_items/create");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				
			$data['rn_no'] = 'New';
			$data['po_no'] = 'None';
			
			$data['po_id'] = 0;
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['supplier_id']))
			{
				$data['supplier_id'] = $db->request('supplier_id');
				$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'name');
			}
			else
			{
				$data['supplier_id'] = '';
				$data['supplier_id_txt'] = '';
			}
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['invoice_no'])){ $data['invoice_no'] = $db->request('invoice_no'); }
			else{ $data['invoice_no'] = ''; }
			
			if(isset($_REQUEST['due_date'])){ $data['due_date'] = $db->request('due_date'); }
			else{ $data['due_date'] = ''; }
			
			if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
			else{ $data['remarks'] = ''; }
			
			$data['user_id'] = $userInfo['user_id'];

			$data['items'] = [];
			$data['total_value'] = 0;
			$data['no_of_items'] = 0;
			$data['no_of_qty'] = 0;
			
			$data['item_lists'] = [];
			
			$data['item_no'] = '01';
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				
				$noofItems = 0;
				
				if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount'], $_REQUEST['added_buying_price'], $_REQUEST['added_discount'])) {
					
					$added_item_id = $_REQUEST['added_item_id'];
					$added_qty = $_REQUEST['added_qty'];
					$added_amount = $_REQUEST['added_amount'];
					$added_buying_price = $_REQUEST['added_buying_price'];
					$added_discount = $_REQUEST['added_discount'];
					
			
					foreach ($added_item_id as $index => $q) {
						
						$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
						$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
						$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
						$abuyingPrice = isset($added_buying_price[$index]) ? $added_buying_price[$index] : 0;
						$adiscount = isset($added_discount[$index]) ? $added_discount[$index] : 0;
						
						if(!$InventoryMasterItemsQuery->has($aitemId))
						{
							$error_msg[]="Invalid item found!"; $error_no++;
						}
						elseif(!$aqty || !$aamount || !$abuyingPrice)
						{
							$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty, amount or price"; $error_no++;
						}
						else
						{
							$discountCal = $abuyingPrice*$adiscount/100;
							$finalPrice = $abuyingPrice-$discountCal;
							$totalLine = $finalPrice*$aqty;
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
														'qty' => $aqty,
														'amount' => $aamount,
														'buyingPrice' => $abuyingPrice,
														'discount' => $adiscount,
														'finalPrice' => $finalPrice,
														'total' => $totalLine
							
												);
							$noofItems+=1;						
												
						}
			
						
					}
				} else {
					$error_msg[]="No items found."; $error_no++;
				}
				
				if(!$error_no)
				{
					
					$createdId = $InventoryTransactionsReceivingnotesQuery->create($data);
					$InventoryTransactionsReceivingnotesQuery->updateTotals($createdId);
					
					$transaction_no = $defCls->docNo('RN-',$createdId);
					$firewallCls->addLog("Receivingnote Created: ".$transaction_no);
					
					$rnInfo = $InventoryTransactionsReceivingnotesQuery->get($createdId);
					
					
					////Supplier transactipn update
					$supplierData = [];
					$supplierData['added_date'] = $rnInfo['added_date'];
					$supplierData['supplier_id'] = $data['supplier_id'];
					$supplierData['reference_id'] = $createdId;
					$supplierData['transaction_type'] = 'RN';
					$supplierData['debit'] = $rnInfo['total_value'];
					$supplierData['credit'] = 0;
					$supplierData['remarks'] = $transaction_no;
					
					$SuppliersMasterSuppliersQuery->transactionAdd($supplierData);
					
					$json['success']=true;
					$json['success_msg']="Sucessfully Created";

					
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
	
				$this_required_file = _HTML.'inventory/transaction_receivingnotes_form.php';
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
			header("location:"._SERVER);
		}
		
	}
	
	

    public function edit() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getReceivingnoteInfo = $InventoryTransactionsReceivingnotesQuery->get($id);
			
			if($getReceivingnoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/transaction_receivingnotes/edit/".$id;
				$data['supplier_create_url'] = $defCls->genURL("suppliers/master_suppliers/create");
				$data['item_create_url'] = $defCls->genURL("inventory/master_items/create");
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['receiving_note_id'] = $getReceivingnoteInfo['receiving_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
					
				$data['rn_no'] = $defCls->docNo('RN-',$getReceivingnoteInfo['receiving_note_id']);;
				
				if($getReceivingnoteInfo['po_id']){ $data['po_no'] = $defCls->docNo('PO-',$getReceivingnoteInfo['po_id']); }
				else{ $data['po_no'] = 'N/A'; }
				
				$data['po_id'] = $getReceivingnoteInfo['po_id'];
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getReceivingnoteInfo['location_id']; }
				
			
				if(isset($_REQUEST['supplier_id']))
				{
					
					$data['supplier_id'] = $db->request('supplier_id');
					$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'name');
				}
				else
				{
					$data['supplier_id'] = $getReceivingnoteInfo['supplier_id'];
					$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($getReceivingnoteInfo['supplier_id'],'name');;
				}
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getReceivingnoteInfo['added_date']); }
				
				if(isset($_REQUEST['invoice_no'])){ $data['invoice_no'] = $db->request('invoice_no'); }
				else{ $data['invoice_no'] = $getReceivingnoteInfo['invoice_no']; }
				
				if(isset($_REQUEST['due_date'])){ $data['due_date'] = $db->request('due_date'); }
				else{ $data['due_date'] = $dateCls->showDate($getReceivingnoteInfo['due_date']); }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getReceivingnoteInfo['remarks']; }
				
				
				$data['item_lists'] = $InventoryTransactionsReceivingnotesQuery->getItems("WHERE receiving_note_id='".$id."' ORDER BY receiving_note_item_id ASC");
				
				
				$data['items'] = [];
				$data['total_value'] = $getReceivingnoteInfo['total_value'];
				$data['no_of_items'] = count($data['item_lists']);
				$data['no_of_qty'] = $getReceivingnoteInfo['no_of_qty'];

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
						
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
					$noofItems = 0;
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount'], $_REQUEST['added_buying_price'], $_REQUEST['added_discount']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_qty = $_REQUEST['added_qty'];
						$added_amount = $_REQUEST['added_amount'];
						$added_buying_price = $_REQUEST['added_buying_price'];
						$added_discount = $_REQUEST['added_discount'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
							$abuyingPrice = isset($added_buying_price[$index]) ? $added_buying_price[$index] : 0;
							$adiscount = isset($added_discount[$index]) ? $added_discount[$index] : 0;
							
							if(!$InventoryMasterItemsQuery->has($aitemId))
							{
								$error_msg[]="Invalid item found!"; $error_no++;
							}
							elseif(!$aqty || !$aamount || !$abuyingPrice)
							{
								$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty, amount or price"; $error_no++;
							}
							else
							{
								$discountCal = $abuyingPrice*$adiscount/100;
								$finalPrice = $abuyingPrice-$discountCal;
								$totalLine = $finalPrice*$aqty;
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'qty' => $aqty,
															'amount' => $aamount,
															'buyingPrice' => $abuyingPrice,
															'discount' => $adiscount,
															'finalPrice' => $finalPrice,
															'total' => $totalLine
								
													);
								$noofItems+=1;	
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['receiving_note_item_id']]) ? $_REQUEST['eqty'.$i['receiving_note_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['receiving_note_item_id']]) ? $_REQUEST['eamount'.$i['receiving_note_item_id']] : 0;
						$ebuyingPrice = isset($_REQUEST['ebuying_price'.$i['receiving_note_item_id']]) ? $_REQUEST['ebuying_price'.$i['receiving_note_item_id']] : 0;
						$ediscount = isset($_REQUEST['ediscount'.$i['receiving_note_item_id']]) ? $_REQUEST['ediscount'.$i['receiving_note_item_id']] : 0;
						
						$discountCal = $ebuyingPrice*$ediscount/100;
						$finalPrice = $ebuyingPrice-$discountCal;
						$totalLine = $finalPrice*$eqty;
						
						if($eqty && $eamount && $ebuyingPrice)
						{
						
							$data['eitems'][] = array(
							
														'receiving_note_item_id' => $i['receiving_note_item_id'],
														'itemId' => $i['item_id'],
														'qty' => $eqty,
														'amount' => $eamount,
														'buyingPrice' => $ebuyingPrice,
														'discount' => $ediscount,
														'finalPrice' => $finalPrice,
														'total' => $totalLine
							
												);
							$noofItems+=1;	
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'receiving_note_item_id' => $i['receiving_note_item_id'],
														'qty' => 0
							
												);
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$InventoryTransactionsReceivingnotesQuery->edit($data);
						$InventoryTransactionsReceivingnotesQuery->updateTotals($id);
						$transaction_no = $defCls->docNo('RN-',$id);
						$firewallCls->addLog("Receivingnotes Updated: ".$transaction_no);
						
						
						$rnInfo = $InventoryTransactionsReceivingnotesQuery->get($id);
						
						////Supplier transactipn update
						$supplierData = [];
						$supplierData['added_date'] = $rnInfo['added_date'];
						$supplierData['supplier_id'] = $rnInfo['supplier_id'];
						$supplierData['reference_id'] = $rnInfo['receiving_note_id'];
						$supplierData['transaction_type'] = 'RN';
						$supplierData['debit'] = $rnInfo['total_value'];
						$supplierData['credit'] = 0;
						$supplierData['remarks'] = $transaction_no;
						
						$SuppliersMasterSuppliersQuery->transactionEdit($supplierData);
						
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
		
					$this_required_file = _HTML.'inventory/transaction_receivingnotes_form.php';
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
				$error_msg[]="Invalid receivingnote Id"; $error_no++;
					
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
	
}
