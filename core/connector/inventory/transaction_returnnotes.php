<?php

class InventoryTransactionReturnnotesConnector {

    public function index() {
		
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReturnnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Purchase Return | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/transaction_returnnotes/create");
			$data['load_table_url'] = $defCls->genURL('inventory/transaction_returnnotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/transaction_returnnotes.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		require_once _QUERY."inventory/transaction_returnnotes.php";
		require_once _QUERY."suppliers/master_suppliers.php";
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReturnnotesQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('RETN-','',$search_no);
				$search_no=ltrim($search_no,'RETN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_supplier_id'])){ $search_supplier_id=$db->request('search_supplier_id'); }
			else{ $search_supplier_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE supplier_id!=0";
			
			if($search_no){ $sql.=" AND return_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_supplier_id){ $sql.=" AND supplier_id='".$search_supplier_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryTransactionsReturnnotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY return_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$returnnotes = $InventoryTransactionsReturnnotesQuery->gets($sql);
			
			$data['returnnotes'] = array();
			
			foreach($returnnotes as $cat)
			{
				$data['returnnotes'][] = array(
										'return_note_id' => $cat['return_note_id'],
										'rn_no' => $defCls->docNo('RETN-',$cat['return_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'supplier_id' => $SuppliersMasterSuppliersQuery->data($cat['supplier_id'],'name'),
										'items' => $defCls->num($cat['no_of_items']).'/'.$defCls->num($cat['no_of_qty']),
										'total_value' => $defCls->money($cat['total_value']),
										'updateURL' => $defCls->genURL('inventory/transaction_returnnotes/edit/'.$cat['return_note_id']),
										'printURL' => $defCls->genURL('inventory/transaction_returnnotes/printView/'.$cat['return_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($returnnotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/transaction_returnnotes_table.php';
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
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReturnnotesQuery;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/transaction_returnnotes/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
				
			$data['return_no'] = 'New';
			
			if(isset($_REQUEST['rn_no'])){ $data['rn_no'] = $db->request('rn_no'); }
			else{ $data['rn_no'] = ''; }
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['supplier_id'])){ $data['supplier_id'] = $db->request('supplier_id'); }
			else{ $data['supplier_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
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
				
				if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount'])) {
					
					$added_item_id = $_REQUEST['added_item_id'];
					$added_qty = $_REQUEST['added_qty'];
					$added_amount = $_REQUEST['added_amount'];
					
			
					foreach ($added_item_id as $index => $q) {
						
						$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
						$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
						$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
						
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
							$totalLine = $aamount*$aqty;
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
														'qty' => $aqty,
														'amount' => $aamount,
														'total' => $totalLine
							
												);
												
												
						}
			
						
					}
				} else {
					$error_msg[]="No items found."; $error_no++;
				}
				
				if(!$error_no)
				{
					
					$createdId = $InventoryTransactionsReturnnotesQuery->create($data);
					$InventoryTransactionsReturnnotesQuery->updateTotals($createdId);
					$transaction_no = $defCls->docNo('RETN-',$createdId);
					$firewallCls->addLog("Return Note Created: ".$transaction_no);
					
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
	
				$this_required_file = _HTML.'inventory/transaction_returnnotes_form.php';
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
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SuppliersMasterSuppliersQuery;
		global $InventoryTransactionsReturnnotesQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getReturnnoteInfo = $InventoryTransactionsReturnnotesQuery->get($id);
			
			if($getReturnnoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/transaction_returnnotes/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['return_note_id'] = $getReturnnoteInfo['return_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
					
				$data['return_no'] = $defCls->docNo('RETN-',$getReturnnoteInfo['return_note_id']);;
				
				if(isset($_REQUEST['rn_no'])){ $data['rn_no'] = $db->request('rn_no'); }
				else{ $data['rn_no'] = $getReturnnoteInfo['rn_no']; }
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getReturnnoteInfo['location_id']; }
				
				if(isset($_REQUEST['supplier_id'])){ $data['supplier_id'] = $db->request('supplier_id'); }
				else{ $data['supplier_id'] = $getReturnnoteInfo['supplier_id']; }
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getReturnnoteInfo['added_date']); }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getReturnnoteInfo['remarks']; }
				
				
				$data['item_lists'] = $InventoryTransactionsReturnnotesQuery->getItems("WHERE return_note_id='".$id."' ORDER BY return_note_item_id ASC");
				
				
				$data['items'] = [];
				$data['total_value'] = $getReturnnoteInfo['total_value'];
				$data['no_of_items'] = count($data['item_lists']);
				$data['no_of_qty'] = $getReturnnoteInfo['no_of_qty'];

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
						
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
					$noofItems = 0;
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_qty = $_REQUEST['added_qty'];
						$added_amount = $_REQUEST['added_amount'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
							
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
								$totalLine = $aamount*$aqty;
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'qty' => $aqty,
															'amount' => $aamount,
															'total' => $totalLine
								
													);
													
								$noofItems+=1;	
								
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['return_note_item_id']]) ? $_REQUEST['eqty'.$i['return_note_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['return_note_item_id']]) ? $_REQUEST['eamount'.$i['return_note_item_id']] : 0;
						
						$totalLine = $eamount*$eqty;
						
						if($eqty && $eamount)
						{
						
							$data['eitems'][] = array(
							
														'return_note_item_id' => $i['return_note_item_id'],
														'itemId' => $i['item_id'],
														'qty' => $eqty,
														'amount' => $eamount,
														'total' => $totalLine
							
												);
							
							$noofItems+=1;	
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'return_note_item_id' => $i['return_note_item_id'],
														'qty' => 0
							
												);
							
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$InventoryTransactionsReturnnotesQuery->edit($data);
						$InventoryTransactionsReturnnotesQuery->updateTotals($id);
						$transaction_no = $defCls->docNo('RETN-',$id);
						$firewallCls->addLog("Return Notes Updated: ".$transaction_no);
						
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
		
					$this_required_file = _HTML.'inventory/transaction_returnnotes_form.php';
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
				$error_msg[]="Invalid return note Id"; $error_no++;
					
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
	
	

    public function printView() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReturnnotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$returnNoteInfo = $InventoryTransactionsReturnnotesQuery->get($id);
			
			if($returnNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Inventory Return Note Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['return_note_id'] = $returnNoteInfo['return_note_id'];
					
				$data['return_note_no'] = $defCls->docNo('RETN-',$returnNoteInfo['return_note_id']);
				
				$data['added_date'] = $dateCls->showDate($returnNoteInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($returnNoteInfo['location_id'],'name');
				
				$data['supplier_id'] = $SuppliersMasterSuppliersQuery->data($returnNoteInfo['supplier_id'],'name');
				
				$data['rn_no'] = $defCls->showText($returnNoteInfo['rn_no']);
				
				$data['remarks'] = $defCls->showText($returnNoteInfo['remarks']);
				
				$data['user'] = $SystemMasterUsersQuery->data($returnNoteInfo['user_id'],'name');
				
				$data['totalQty'] = $returnNoteInfo['no_of_qty'];
				
				$data['totalTotal'] = $returnNoteInfo['total_value'];
				
				
				$data['item_lists'] = $InventoryTransactionsReturnnotesQuery->getItems("WHERE return_note_id='".$id."' ORDER BY return_note_item_id ASC");

				$this_required_file = _HTML.'inventory/transaction_returnnotes_print.php';
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
				$error_msg[]="Invalid return note Id"; $error_no++;
					
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
