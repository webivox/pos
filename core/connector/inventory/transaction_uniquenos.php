<?php

class InventoryTransactionUniquenosConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionUniquenosQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Inventory Unique NOs | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/transaction_uniquenos/create");
			$data['load_table_url'] = $defCls->genURL('inventory/transaction_uniquenos/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/transaction_uniquenos.php";
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
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionUniquenosQuery;
		global $SalesTransactionInvoicesQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_item_id'])){ $search_item_id=$db->request('search_item_id'); }
			else{ $search_item_id=''; }
			
			if(isset($_REQUEST['search_unique_no'])){ $search_unique_no=$db->request('search_unique_no'); }
			else{ $search_unique_no=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			
			/////////////
			
			$sql=" WHERE unique_id!=0";
		
			if($search_item_id){ $sql.=" AND item_id='".$search_item_id."'"; }
			if($search_unique_no){ $sql.=" AND unique_no='".$search_unique_no."'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			
			$pagination = $InventoryTransactionUniquenosQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY unique_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
		
			$uniquenos = $InventoryTransactionUniquenosQuery->gets($sql);
			
			$data['uniquenos'] = array();
			
			foreach($uniquenos as $cat)
			{
				if($cat['status']==0){ $status = 'Pending'; }
				elseif($cat['status']==1){ $status = 'Used'; }
				else{ $status = 'N/A'; }
				
				if($cat['used_invoice_id']){ $invoiceNo = $SalesTransactionInvoicesQuery->data($cat['used_invoice_id'],'invoice_no'); }
				else{ $invoiceNo = 'N/A'; }
				
				$data['uniquenos'][] = array(
										'unique_id' => $cat['unique_id'],
										'date' => $dateCls->showDate($cat['added_date']),
										'usedDate' => $dateCls->showDate($cat['used_date']),
										'InvoiceNo' => $invoiceNo,
										'item' => $InventoryMasterItemsQuery->data($cat['item_id'],'name'),
										'unique_no' => $defCls->showText($cat['unique_no']),
										'status' => $status,
										'updateURL' => $defCls->genURL('inventory/transaction_uniquenos/edit/'.$cat['unique_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($uniquenos).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/transaction_uniquenos_table.php';
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
		global $SystemMasterUsersQuery;
		global $InventoryTransactionUniquenosQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/transaction_uniquenos/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if(isset($_REQUEST['item_id']))
			{
				$data['item_id'] = $db->request('item_id');
				$data['item_name'] = $InventoryMasterItemsQuery->data($cat['item_id'],'name');
			}
			else{ $data['item_id'] = ''; $data['item_name'] = ''; }
				
			if(isset($_REQUEST['unique_no'])){ $data['unique_no'] = $db->request('unique_no');}
			else{ $data['unique_no'] = ''; }
				
			if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks');}
			else{ $data['remarks'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countUniquenosByUniqueno = $InventoryTransactionUniquenosQuery->gets("WHERE unique_no='".$data['unique_no']."'");
				$countUniquenosByUniqueno = count($countUniquenosByUniqueno);
				
				if(strlen($data['unique_no'])<4){ $error_msg[]="Unique no must be minimum 4 letters"; $error_no++; }
				if($countUniquenosByUniqueno){ $error_msg[]="The unique no already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$InventoryTransactionUniquenosQuery->create($data);
					$firewallCls->addLog("Unique NO Created: ".$data['unique_no']);
					
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
	
				$this_required_file = _HTML.'inventory/transaction_uniquenos_form.php';
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
		global $SystemMasterUsersQuery;
		global $InventoryTransactionUniquenosQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getUniquenoInfo = $InventoryTransactionUniquenosQuery->get($id);
			
			if($getUniquenoInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/transaction_uniquenos/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['unique_id'] = $getUniquenoInfo['unique_id'];
				
				if(isset($_REQUEST['item_id']))
				{
					$data['item_id'] = $db->request('item_id');
					$data['item_name'] = $InventoryMasterItemsQuery->data($data['item_id'],'name');
				}
				else
				{
					$data['item_id'] = $getUniquenoInfo['item_id'];
					$data['item_name'] = $InventoryMasterItemsQuery->data($data['item_id'],'name');
				}
					
				if(isset($_REQUEST['unique_no'])){ $data['unique_no'] = $db->request('unique_no');}
				else{ $data['unique_no'] = $getUniquenoInfo['unique_no']; }
					
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks');}
				else{ $data['remarks'] = $getUniquenoInfo['remarks']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = $getUniquenoInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['unique_no'])<4){ $error_msg[]="Unique no must be minimum 4 letters"; $error_no++; }
					
					if($db->request('unique_no')!==$getUniquenoInfo['unique_no'])
					{
						$countUniquenosByUniqueno = $InventoryTransactionUniquenosQuery->gets("WHERE unique_no='".$data['unique_no']."'");
						$countUniquenosByUniqueno = count($countUniquenosByUniqueno);
						
						if($countUniquenosByUniqueno){ $error_msg[]="The unique no already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$InventoryTransactionUniquenosQuery->edit($data);
						$firewallCls->addLog("Unique no Updated: ".$data['unique_no']);
						
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
		
					$this_required_file = _HTML.'inventory/transaction_uniquenos_form.php';
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
				$error_msg[]="Invalid unique Id"; $error_no++;
					
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
