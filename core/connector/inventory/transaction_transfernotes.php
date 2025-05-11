<?php

class InventoryTransactionTransfernotesConnector {

    public function index() {
		
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsTransfernotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Inventory Transfers | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/transaction_transfernotes/create");
			$data['load_table_url'] = $defCls->genURL('inventory/transaction_transfernotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/transaction_transfernotes.php";
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
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryTransactionsTransfernotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('TRN-','',$search_no);
				$search_no=ltrim($search_no,'TRN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE transfer_note_id !=0";
			
			if($search_no){ $sql.=" AND transfer_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryTransactionsTransfernotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY transfer_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$transfernotes = $InventoryTransactionsTransfernotesQuery->gets($sql);
			
			$data['transfernotes'] = array();
			
			foreach($transfernotes as $cat)
			{
				$data['transfernotes'][] = array(
										'transfer_note_id' => $cat['transfer_note_id'],
										'transfer_no' => $defCls->docNo('TRN-',$cat['transfer_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'location_from' => $SystemMasterLocationsQuery->data($cat['location_from_id'],'name'),
										'location_to' => $SystemMasterLocationsQuery->data($cat['location_to_id'],'name'),
										'items' => $defCls->num($cat['no_of_items']).'/'.$defCls->num($cat['no_of_qty']),
										'updateURL' => $defCls->genURL('inventory/transaction_transfernotes/edit/'.$cat['transfer_note_id']),
										'printURL' => $defCls->genURL('inventory/transaction_transfernotes/printView/'.$cat['transfer_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($transfernotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/transaction_transfernotes_table.php';
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
		global $InventoryTransactionsTransfernotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/transaction_transfernotes/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				
			$data['transfer_no'] = 'New';
			
			if(isset($_REQUEST['location_from_id'])){ $data['location_from_id'] = $db->request('location_from_id'); }
			else{ $data['location_from_id'] = ''; }
			
			if(isset($_REQUEST['location_to_id'])){ $data['location_to_id'] = $db->request('location_to_id'); }
			else{ $data['location_to_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
			else{ $data['remarks'] = ''; }
			
			$data['user_id'] = $userInfo['user_id'];

			$data['items'] = [];
			$data['no_of_items'] = 0;
			$data['no_of_qty'] = 0;
			
			$data['item_lists'] = [];
			
			$data['item_no'] = '01';
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_from_id'])){ $error_msg[]="You must choose a location from"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_to_id'])){ $error_msg[]="You must choose a location to"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if($data['location_from_id']==$data['location_to_id']){ $error_msg[]="You cant transfer inside location!"; $error_no++; }
				
				$noofItems = 0;
				
				if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'])) {
					
					$added_item_id = $_REQUEST['added_item_id'];
					$added_qty = $_REQUEST['added_qty'];
					
			
					foreach ($added_item_id as $index => $q) {
						
						$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
						$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
						
						if(!$InventoryMasterItemsQuery->has($aitemId))
						{
							$error_msg[]="Invalid item found!"; $error_no++;
						}
						elseif(!$aqty)
						{
							$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty"; $error_no++;
						}
						else
						{
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
														'qty' => $aqty
							
												);
												
							$noofItems+=1;				
						}
			
						
					}
				} else {
					$error_msg[]="No items found."; $error_no++;
				}
				
				if(!$error_no)
				{
					
					$createdId = $InventoryTransactionsTransfernotesQuery->create($data);
					$InventoryTransactionsTransfernotesQuery->updateTotals($createdId);
					$transaction_no = $defCls->docNo('TRN-',$createdId);
					$firewallCls->addLog("Transfer Note Created: ".$transaction_no);
					
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
	
				$this_required_file = _HTML.'inventory/transaction_transfernotes_form.php';
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
		global $InventoryTransactionsTransfernotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getTransfernoteInfo = $InventoryTransactionsTransfernotesQuery->get($id);
			
			if($getTransfernoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/transaction_transfernotes/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['transfer_note_id'] = $getTransfernoteInfo['transfer_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
					
				$data['transfer_no'] = $defCls->docNo('TRN-',$getTransfernoteInfo['transfer_note_id']);;
				
				if(isset($_REQUEST['location_from_id'])){ $data['location_from_id'] = $db->request('location_from_id'); }
				else{ $data['location_from_id'] = $getTransfernoteInfo['location_from_id']; }
				
				if(isset($_REQUEST['location_to_id'])){ $data['location_to_id'] = $db->request('location_to_id'); }
				else{ $data['location_to_id'] = $getTransfernoteInfo['location_to_id']; }
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getTransfernoteInfo['added_date']); }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getTransfernoteInfo['remarks']; }
				
				
				$data['item_lists'] = $InventoryTransactionsTransfernotesQuery->getItems("WHERE transfer_note_id='".$id."' ORDER BY transfer_note_item_id ASC");
				
				
				$data['items'] = [];
				$data['no_of_items'] = count($data['item_lists']);
				$data['no_of_qty'] = $getTransfernoteInfo['no_of_qty'];

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_from_id'])){ $error_msg[]="You must choose a location from"; $error_no++; }
					if(!$SystemMasterLocationsQuery->has($data['location_to_id'])){ $error_msg[]="You must choose a location to"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if($data['location_from_id']==$data['location_to_id']){ $error_msg[]="You cant transfer inside location!"; $error_no++; }
					
					$noofItems = 0;
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_qty = $_REQUEST['added_qty'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							
							if(!$InventoryMasterItemsQuery->has($aitemId))
							{
								$error_msg[]="Invalid item found!"; $error_no++;
							}
							elseif(!$aqty)
							{
								$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty"; $error_no++;
							}
							else
							{
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'qty' => $aqty
								
													);
								$noofItems+=1;
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['transfer_note_item_id']]) ? $_REQUEST['eqty'.$i['transfer_note_item_id']] : 0;
						
						
						if($eqty)
						{
						
							$data['eitems'][] = array(
							
														'transfer_note_item_id' => $i['transfer_note_item_id'],
														'itemId' => $i['item_id'],
														'qty' => $eqty
							
												);
							$noofItems+=1;
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'transfer_note_item_id' => $i['transfer_note_item_id'],
														'qty' => 0
							
												);
							
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$InventoryTransactionsTransfernotesQuery->edit($data);
						$InventoryTransactionsTransfernotesQuery->updateTotals($id);
						$transaction_no = $defCls->docNo('TRN-',$id);
						$firewallCls->addLog("Transfer Notes Updated: ".$transaction_no);
						
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
		
					$this_required_file = _HTML.'inventory/transaction_transfernotes_form.php';
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
				$error_msg[]="Invalid transfer note Id"; $error_no++;
					
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
		global $InventoryTransactionsTransfernotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$transferNoteInfo = $InventoryTransactionsTransfernotesQuery->get($id);
			
			if($transferNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Inventory Transfer Note Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['transfer_note_id'] = $transferNoteInfo['transfer_note_id'];
					
				$data['transfer_note_no'] = $defCls->docNo('TRN-',$transferNoteInfo['transfer_note_id']);
				
				$data['added_date'] = $dateCls->showDate($transferNoteInfo['added_date']);
				
				$data['location_from'] = $SystemMasterLocationsQuery->data($transferNoteInfo['location_from_id'],'name');
				
				$data['location_to'] = $SystemMasterLocationsQuery->data($transferNoteInfo['location_to_id'],'name');
				
				$data['remarks'] = $defCls->showText($transferNoteInfo['remarks']);
				
				$data['user'] = $SystemMasterUsersQuery->data($transferNoteInfo['user_id'],'name');
				
				$data['totalQty'] = $transferNoteInfo['no_of_qty'];
				
				
				$data['item_lists'] = $InventoryTransactionsTransfernotesQuery->getItems("WHERE transfer_note_id='".$id."' ORDER BY transfer_note_item_id ASC");

				$this_required_file = _HTML.'inventory/transaction_transfernotes_print.php';
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
				$error_msg[]="Invalid transfer note Id"; $error_no++;
					
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
