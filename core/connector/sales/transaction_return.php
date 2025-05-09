<?php

class SalesTransactionReturnConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionsReturnQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Sales Returns | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("sales/transaction_return/create");
			$data['load_table_url'] = $defCls->genURL('sales/transaction_return/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/transaction_return.php";
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
		global $SalesTransactionsReturnQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('SRN-','',$search_no);
				$search_no=ltrim($search_no,'SRN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_customer_id'])){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if(isset($_REQUEST['search_location_id'])){ $search_location_id=$db->request('search_location_id'); }
			else{ $search_location_id=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_id!=0";
			
			if($search_no){ $sql.=" AND sales_return_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer_id){ $sql.=" AND customer_id='".$search_customer_id."'"; }
			if($search_location_id){ $sql.=" AND location_id='".$search_location_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SalesTransactionsReturnQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY sales_return_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$return = $SalesTransactionsReturnQuery->gets($sql);
			
			$data['return'] = array();
			
			foreach($return as $cat)
			{
				$data['return'][] = array(
										'sales_return_id' => $cat['sales_return_id'],
										'srn_no' => $defCls->docNo('SRN-',$cat['sales_return_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'items' => $defCls->num($cat['no_of_items']).'/'.$defCls->num($cat['no_of_qty']),
										'total_value' => $defCls->money($cat['total_value']),
										'updateURL' => $defCls->genURL('sales/transaction_return/edit/'.$cat['sales_return_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($return).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'sales/transaction_return_table.php';
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SalesTransactionsReturnQuery;
		global $InventoryMasterItemsQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."sales/transaction_return/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
				
			$data['srn_no'] = 'New';
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['customer_id'])){ $data['customer_id'] = $db->request('customer_id'); }
			else{ $data['customer_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['invoice_no'])){ $data['invoice_no'] = $db->request('invoice_no'); }
			else{ $data['invoice_no'] = ''; }
			
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
				if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
				if(!$data['remarks']){ $error_msg[]="You must enter remarks"; $error_no++; }
				
				$noofItems = 0;
				
				if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount'])) {
					
					$added_item_id = $_REQUEST['added_item_id'];
					$added_cost = $_REQUEST['added_cost'];
					$added_qty = $_REQUEST['added_qty'];
					$added_amount = $_REQUEST['added_amount'];
					
			
					foreach ($added_item_id as $index => $q) {
						
						$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
						$acost = isset($added_cost[$index]) ? $added_cost[$index] : 0;
						$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
						$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
						
						if(!$InventoryMasterItemsQuery->has($aitemId))
						{
							$error_msg[]="Invalid item found!"; $error_no++;
						}
						elseif(!$acost || !$aqty || !$aamount)
						{
							$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." cost, qty or amount"; $error_no++;
						}
						else
						{
							$totalLine = $aamount*$aqty;
							
							$data['items'][] = array(
							
														'itemId' => $aitemId,
														'cost' => $acost,
														'qty' => $aqty,
														'amount' => $aamount,
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
					
					$createdId = $SalesTransactionsReturnQuery->create($data);
					$SalesTransactionsReturnQuery->updateTotals($createdId);
					
					$transaction_no = $defCls->docNo('SRN-',$createdId);
					$firewallCls->addLog("Sales Return Created: ".$transaction_no);
					
					
					
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
	
				$this_required_file = _HTML.'sales/transaction_return_form.php';
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SalesTransactionsReturnQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getReturnnoteInfo = $SalesTransactionsReturnQuery->get($id);
			
			if($getReturnnoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."sales/transaction_return/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['sales_return_id'] = $getReturnnoteInfo['sales_return_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
					
				$data['srn_no'] = $defCls->docNo('SRN-',$getReturnnoteInfo['sales_return_id']);;
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getReturnnoteInfo['location_id']; }
				
				if(isset($_REQUEST['customer_id'])){ $data['customer_id'] = $db->request('customer_id'); }
				else{ $data['customer_id'] = $getReturnnoteInfo['customer_id']; }
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getReturnnoteInfo['added_date']); }
				
				if(isset($_REQUEST['invoice_no'])){ $data['invoice_no'] = $db->request('invoice_no'); }
				else{ $data['invoice_no'] = $getReturnnoteInfo['invoice_no']; }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getReturnnoteInfo['remarks']; }
				
				
				$data['item_lists'] = $SalesTransactionsReturnQuery->getItems("WHERE sales_return_id='".$id."' ORDER BY sales_return_item_id ASC");
				
				
				$data['items'] = [];
				$data['total_value'] = $getReturnnoteInfo['total_value'];
				$data['no_of_items'] = count($data['item_lists']);
				$data['no_of_qty'] = $getReturnnoteInfo['no_of_qty'];

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
						
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					
					$noofItems = 0;
					
					if (isset($_REQUEST['added_item_id'], $_REQUEST['added_qty'], $_REQUEST['added_amount']))
					{
						
						$added_item_id = $_REQUEST['added_item_id'];
						$added_cost = $_REQUEST['added_cost'];
						$added_qty = $_REQUEST['added_qty'];
						$added_amount = $_REQUEST['added_amount'];
						
				
						foreach ($added_item_id as $index => $q) {
							
							$aitemId = isset($added_item_id[$index]) ? $added_item_id[$index] : 0;
							$acost = isset($added_cost[$index]) ? $added_cost[$index] : 0;
							$aqty = isset($added_qty[$index]) ? $added_qty[$index] : 0;
							$aamount = isset($added_amount[$index]) ? $added_amount[$index] : 0;
							
							if(!$InventoryMasterItemsQuery->has($aitemId))
							{
								$error_msg[]="Invalid item found!"; $error_no++;
							}
							elseif(!$acost || !$aqty || !$aamount)
							{
								$error_msg[]="Please check ".$InventoryMasterItemsQuery->data($aitemId,'name')." cost, qty or amount"; $error_no++;
							}
							else
							{
								
								$totalLine = $aamount*$aqty;
								
								$data['items'][] = array(
								
															'itemId' => $aitemId,
															'qty' => $aqty,
															'cost' => $acost,
															'amount' => $aamount,
															'total' => $totalLine
								
													);
								$noofItems+=1;	
							}
				
							
						}
					} 
					
					$data['eitems'] = [];
					
					foreach($data['item_lists'] as $i){
						
						$ecost = isset($_REQUEST['ecost'.$i['sales_return_item_id']]) ? $_REQUEST['ecost'.$i['sales_return_item_id']] : 0;
						$eqty = isset($_REQUEST['eqty'.$i['sales_return_item_id']]) ? $_REQUEST['eqty'.$i['sales_return_item_id']] : 0;
						$eamount = isset($_REQUEST['eamount'.$i['sales_return_item_id']]) ? $_REQUEST['eamount'.$i['sales_return_item_id']] : 0;
						
						$totalLine = $eamount*$eqty;
						
						if($ecost && $eqty && $eamount)
						{
						
							$data['eitems'][] = array(
							
														'sales_return_item_id' => $i['sales_return_item_id'],
														'itemId' => $i['item_id'],
														'cost' => $ecost,
														'qty' => $eqty,
														'amount' => $eamount,
														'total' => $totalLine
							
												);
							$noofItems+=1;	
									
						}
						else
						{
							$data['eitems'][] = array(
							
														'sales_return_item_id' => $i['sales_return_item_id'],
														'qty' => 0
							
												);
						}
						
						
					}
					
					if(!$noofItems) {
						$error_msg[]="No items found."; $error_no++;
					}
					
						
					if(!$error_no)
					{
						
						$SalesTransactionsReturnQuery->edit($data);
						$SalesTransactionsReturnQuery->updateTotals($id);
						$transaction_no = $defCls->docNo('SRN-',$id);
						$firewallCls->addLog("Return Updated: ".$transaction_no);
						
						
						
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
		
					$this_required_file = _HTML.'sales/transaction_return_form.php';
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
				$error_msg[]="Invalid returnnote Id"; $error_no++;
					
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
