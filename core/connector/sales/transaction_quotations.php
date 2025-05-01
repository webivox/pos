<?php

class SalesTransactionQuotationsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionsQuotationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("sales/transaction_quotations/create");
			$data['load_table_url'] = $defCls->genURL('sales/transaction_quotations/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/transaction_quotations.php";
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
		global $SalesTransactionsQuotationsQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
				$search_no=str_replace('QTE-','',$search_no);
				$search_no=ltrim($search_no,'QTE-');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_customer_id')){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($db->request('search_location_id')){ $search_location_id=$db->request('search_location_id'); }
			else{ $search_location_id=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE quotation_id !=0";
			
			if($search_no){ $sql.=" AND quotation_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer_id){ $sql.=" AND customer_id='".$search_customer_id."'"; }
			if($search_location_id){ $sql.=" AND location_id='".$search_location_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SalesTransactionsQuotationsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY quotation_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$quotations = $SalesTransactionsQuotationsQuery->gets($sql);
			
			$data['quotations'] = array();
			
			foreach($quotations as $cat)
			{
				$data['quotations'][] = array(
										'quotation_id' => $cat['quotation_id'],
										'quotation_no' => $defCls->docNo('QTE-',$cat['quotation_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'updateURL' => $defCls->genURL('sales/transaction_quotations/edit/'.$cat['quotation_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($quotations).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'sales/transaction_quotations_table.php';
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
		global $SalesTransactionsQuotationsQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."sales/transaction_quotations/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				
			$data['quotation_no'] = 'New';
			
			if($db->request('customer_id')){ $data['customer_id'] = $db->request('customer_id'); }
			else{ $data['customer_id'] = ''; }
			
			if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if($db->request('remarks')){ $data['remarks'] = $db->request('remarks'); }
			else{ $data['remarks'] = ''; }

			
			$data['user_id'] = $userInfo['user_id'];
			
			
			$data['no_of_items'] = 0;
			
			$data['item_lists'] = [];
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
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
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
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
					
					$createdId = $SalesTransactionsQuotationsQuery->create($data);
					$transaction_no = $defCls->docNo('QTE-',$createdId);
					$firewallCls->addLog("Quotation Created: ".$transaction_no);
					
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
	
				$this_required_file = _HTML.'sales/transaction_quotations_form.php';
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
		global $SalesTransactionsQuotationsQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getQuotationInfo = $SalesTransactionsQuotationsQuery->get($id);
			
			if($getQuotationInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."sales/transaction_quotations/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['quotation_id'] = $getQuotationInfo['quotation_id'];
			
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
					
				$data['quotation_no'] = $defCls->docNo('QTE-',$getQuotationInfo['quotation_id']);;
				
				if($db->request('customer_id')){ $data['customer_id'] = $db->request('customer_id'); }
				else{ $data['customer_id'] = $getQuotationInfo['customer_id']; }
				
				if($db->request('location_id')){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getQuotationInfo['location_id']; }
				
				if($db->request('added_date')){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getQuotationInfo['added_date']); }
				
				if($db->request('remarks')){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getQuotationInfo['remarks']; }
				
				
				$data['item_lists'] = $SalesTransactionsQuotationsQuery->getItems("WHERE quotation_id='".$id."' ORDER BY quotation_item_id ASC");
				
				$data['no_of_items'] = count($data['item_lists']);

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
															'total' => $aamount*$aqty
								
													);
								$noofItems+=1;
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$eqty = isset($_REQUEST['eqty'.$i['quotation_item_id']]) ? $_REQUEST['eqty'.$i['quotation_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['quotation_item_id']]) ? $_REQUEST['eamount'.$i['quotation_item_id']] : 0;
						
						
						if($eqty)
						{
						
							$data['eitems'][] = array(
							
														'quotation_item_id' => $i['quotation_item_id'],
														'itemId' => $i['item_id'],
														'qty' => $eqty,
														'amount' => $eamount,
														'total' => $eamount*$eqty
							
												);
							$noofItems+=1;
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'quotation_item_id' => $i['quotation_item_id'],
														'qty' => 0
							
												);
							
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$SalesTransactionsQuotationsQuery->edit($data);
						$transaction_no = $defCls->docNo('QTE-',$id);
						$firewallCls->addLog("Quotations Updated: ".$transaction_no);
						
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
		
					$this_required_file = _HTML.'sales/transaction_quotations_form.php';
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
				$error_msg[]="Invalid quotation Id"; $error_no++;
					
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
