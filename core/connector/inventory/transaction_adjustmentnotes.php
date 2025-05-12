<?php

class InventoryTransactionAdjustmentnotesConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsAdjustmentnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Stock Adjustments | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/transaction_adjustmentnotes/create");
			$data['load_table_url'] = $defCls->genURL('inventory/transaction_adjustmentnotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/transaction_adjustmentnotes.php";
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
		global $InventoryTransactionsAdjustmentnotesQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('ADJ-','',$search_no);
				$search_no=ltrim($search_no,'ADJ-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE adjustment_note_id !=0";
			
			if($search_no){ $sql.=" AND adjustment_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryTransactionsAdjustmentnotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY adjustment_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$adjustmentnotes = $InventoryTransactionsAdjustmentnotesQuery->gets($sql);
			
			$data['adjustmentnotes'] = array();
			
			foreach($adjustmentnotes as $cat)
			{
				$data['adjustmentnotes'][] = array(
										'adjustment_note_id' => $cat['adjustment_note_id'],
										'adjustment_no' => $defCls->docNo('ADJ-',$cat['adjustment_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'updateURL' => $defCls->genURL('inventory/transaction_adjustmentnotes/edit/'.$cat['adjustment_note_id']),
										'printURL' => $defCls->genURL('inventory/transaction_adjustmentnotes/printView/'.$cat['adjustment_note_id']),
										'deleteURL' => $defCls->genURL('inventory/transaction_adjustmentnotes/delete/'.$cat['adjustment_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($adjustmentnotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/transaction_adjustmentnotes_table.php';
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
		global $InventoryTransactionsAdjustmentnotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/transaction_adjustmentnotes/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				
			$data['adjustment_no'] = 'New';
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
			else{ $data['remarks'] = ''; }

			
			$data['user_id'] = $userInfo['user_id'];
			
			
			$data['no_of_items'] = 0;
			
			$data['item_lists'] = [];
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				
				
				$noofItems = 0;
				
				if (isset($_REQUEST['added_item_id'], $_REQUEST['added_type'], $_REQUEST['added_qty'], $_REQUEST['added_amount'])) {
					
					$added_item_id = $_REQUEST['added_item_id'];
					$added_type = $_REQUEST['added_type'];
					$added_qty = $_REQUEST['added_qty'];
					$added_amount = $_REQUEST['added_amount'];
					
			
					foreach ($added_item_id as $index => $q) {
						
						$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
						$atype = isset($added_type[$index]) ? $added_type[$index] : '';
						$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
						$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
						
						if(!$InventoryMasterItemsQuery->has($aitemId))
						{
							$error_msg[]="Invalid item found!"; $error_no++;
						}
						elseif(!$aqty || !$atype || !$aamount)
						{
							$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty, amount or type"; $error_no++;
						}
						else
						{
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
														'type' => $atype,
														'qty' => $aqty,
														'amount' => $aamount,
														'total' => $aamount*$aqty
							
												);
												
							$noofItems+=1;				
						}
			
						
					}
				} else {
					$error_msg[]="No items found."; $error_no++;
				}
				
				if(!$error_no)
				{
					
					$createdId = $InventoryTransactionsAdjustmentnotesQuery->create($data);
					$transaction_no = $defCls->docNo('ADJ-',$createdId);
					$firewallCls->addLog("Adjustment Note Created: ".$transaction_no);
					
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
	
				$this_required_file = _HTML.'inventory/transaction_adjustmentnotes_form.php';
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
		global $InventoryTransactionsAdjustmentnotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getAdjustmentnoteInfo = $InventoryTransactionsAdjustmentnotesQuery->get($id);
			
			if($getAdjustmentnoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/transaction_adjustmentnotes/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['adjustment_note_id'] = $getAdjustmentnoteInfo['adjustment_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
					
				$data['adjustment_no'] = $defCls->docNo('ADJ-',$getAdjustmentnoteInfo['adjustment_note_id']);;
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getAdjustmentnoteInfo['location_id']; }
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getAdjustmentnoteInfo['added_date']); }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getAdjustmentnoteInfo['remarks']; }
				
				
				$data['item_lists'] = $InventoryTransactionsAdjustmentnotesQuery->getItems("WHERE adjustment_note_id='".$id."' ORDER BY adjustment_note_item_id ASC");
				
				$data['no_of_items'] = count($data['item_lists']);

				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location from"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
					$noofItems = 0;
					$data['items'] = [];
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_type'], $_REQUEST['added_qty'], $_REQUEST['added_amount']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_type = $_REQUEST['added_type'];
						$added_qty = $_REQUEST['added_qty'];
						$added_amount = $_REQUEST['added_amount'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$atype = isset($added_type[$index]) ? $added_type[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
							
							if(!$InventoryMasterItemsQuery->has($aitemId))
							{
								$error_msg[]="Invalid item found!"; $error_no++;
							}
							elseif(!$aqty || !$atype || !$aamount)
							{
								$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." qty, amount or type"; $error_no++;
							}
							else
							{
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'type' => $atype,
															'qty' => $aqty,
															'amount' => $aamount,
															'total' => $aamount*$aqty
								
													);
								$noofItems+=1;
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['adjustment_note_item_id']]) ? $_REQUEST['eqty'.$i['adjustment_note_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['adjustment_note_item_id']]) ? $_REQUEST['eamount'.$i['adjustment_note_item_id']] : 0;
						
						
						if($eqty)
						{
						
							$data['eitems'][] = array(
							
														'adjustment_note_item_id' => $i['adjustment_note_item_id'],
														'itemId' => $i['item_id'],
														'type' => $i['type'],
														'qty' => $eqty,
														'amount' => $eamount,
														'total' => $eamount*$eqty
							
												);
							$noofItems+=1;
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'adjustment_note_item_id' => $i['adjustment_note_item_id'],
														'qty' => 0
							
												);
							
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$InventoryTransactionsAdjustmentnotesQuery->edit($data);
						$transaction_no = $defCls->docNo('ADJ-',$id);
						$firewallCls->addLog("Adjustment Notes Updated: ".$transaction_no);
						
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
		
					$this_required_file = _HTML.'inventory/transaction_adjustmentnotes_form.php';
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
				$error_msg[]="Invalid adjustment note Id"; $error_no++;
					
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
		global $InventoryTransactionsAdjustmentnotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$adjustmentNoteInfo = $InventoryTransactionsAdjustmentnotesQuery->get($id);
			
			if($adjustmentNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Inventory Adjustment Note Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['adjustment_note_id'] = $adjustmentNoteInfo['adjustment_note_id'];
					
				$data['adjustment_note_no'] = $defCls->docNo('ADJ-',$adjustmentNoteInfo['adjustment_note_id']);
				
				$data['added_date'] = $dateCls->showDate($adjustmentNoteInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($adjustmentNoteInfo['location_id'],'name');
				
				$data['remarks'] = $defCls->showText($adjustmentNoteInfo['remarks']);
				
				$data['user'] = $SystemMasterUsersQuery->data($adjustmentNoteInfo['user_id'],'name');
				
				$data['item_lists'] = $InventoryTransactionsAdjustmentnotesQuery->getItems("WHERE adjustment_note_id='".$id."' ORDER BY adjustment_note_item_id ASC");

				$this_required_file = _HTML.'inventory/transaction_adjustmentnotes_print.php';
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
				$error_msg[]="Invalid adjustment note Id"; $error_no++;
					
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
	
	
	
	
	
	
	
	
	

    public function delete() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsAdjustmentnotesQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $InventoryTransactionsAdjustmentnotesQuery->get($id);
			
			if($getInfo)
			{
				$doNo = $defCls->docNo('ADJ-',$getInfo['adjustment_note_id']);;
				
				$deleteValue = $InventoryTransactionsAdjustmentnotesQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("Adjustment Note Deleted: ".$doNo);
				
					$json['success']=true;
					$json['success_msg']="Sucessfully Updated";
				
				}
				elseif(is_array($deleteValue))
				{
					foreach($deleteValue as $v)
					{
						$error_msg[]=$v; $error_no++;
					}
					
				}
				else
				{
					$error_msg[]="An error occurred while attempting to delete the adjustment note!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid adjustment note Id"; $error_no++;
				
				
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
			header("location:"._SERVER);
		}
		
	}
}
