<?php

class InventoryMasterItemsConnector {

    public function index() {
		
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/master_items/create");
			$data['load_table_url'] = $defCls->genURL('inventory/master_items/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0 ORDER BY name ASC");
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/master_items.php";
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
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_category_id')){ $search_category_id=$db->request('search_category_id'); }
			else{ $search_category_id=''; }
			
			if($db->request('search_name')){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if($db->request('search_barcode')!==''){ $search_barcode=$db->request('search_barcode'); }
			else{ $search_barcode=''; }
			
			if($db->request('search_status')!==''){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE item_id!=0";
			
			if($search_category_id!==''){ $sql.=" AND category_id='".$search_category_id."'"; }
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_barcode!==''){ $sql.=" AND barcode='".$search_barcode."'"; }
			if($search_status!==''){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryMasterItemsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY item_id DESC";
			
			$items = $InventoryMasterItemsQuery->gets($sql);
			
			$data['items'] = array();
			
			foreach($items as $cat)
			{
				$parent_category_id = $InventoryMasterCategoryQuery->data($cat['category_id'],'parent_category_id');
				
				$data['items'][] = array(
										'item_id' => $cat['item_id'],
										'name' => $cat['name'],
										'barcode' => $cat['barcode'],
										'category' => $InventoryMasterCategoryQuery->data($parent_category_id,'name'). ' > '.$InventoryMasterCategoryQuery->data($cat['category_id'],'name'),
										'stock' => $cat['closing_stocks'],
										'selling_price' => $cat['selling_price'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('inventory/master_items/edit/'.$cat['item_id']),
										'updatePriceURL' => $defCls->genURL('inventory/master_items/editprice/'.$cat['item_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($items).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/master_items_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	public function autoComplete()
	{	
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$json = [];
		
		if($firewallCls->verifyUser())
		{
			
			$customerId = $db->request('customerId');
			$term = $db->request('term');
			
			$sql = "WHERE name LIKE \"%".$term."%\"";
	
			$itemInfo = $InventoryMasterItemsQuery->gets($sql);
		
			foreach($itemInfo as $itm)
			{
				
				if($customerId){ $customerGroupId = $CustomersMasterCustomersQuery->data($customerId,'customer_group_id'); }
				else{ $customerGroupId = 0; }
				
				$price =  $InventoryMasterItemsQuery->getCustomerGroupPrice($customerGroupId,$itm['item_id']);
				
				if($SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'cost_show')){ $cost = $itm['cost']; }
				else{ $cost = 0; }
				
				$json[]=array(
						'value'=> $itm['item_id'],
						'label'=> $defCls->docNo('',$itm['item_id']).' - '.$itm['name'],
						'price'=> $price,
						'cost'=> $cost
							);
			}
		}
		
		echo json_encode($json);
	
	}
    public function create() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		global $InventoryMasterWarrantyQuery;	
		global $SuppliersMasterSuppliersQuery;	
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/master_items/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0 ORDER BY name ASC");
			$data['brand_list'] = $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list'] = $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
				
			if($db->request('category_id')){ $data['category_id'] = $db->request('category_id'); }
			else{ $data['category_id'] = 0; }
			
			if($db->request('brand_id')){ $data['brand_id'] = $db->request('brand_id'); }
			else{ $data['brand_id'] = 0; }
			
			if($db->request('unit_id')){ $data['unit_id'] = $db->request('unit_id'); }
			else{ $data['unit_id'] = 0; }
			
			if($db->request('supplier_id'))
			{
				$data['supplier_id'] = $db->request('supplier_id');
				$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'name');
			}
			else
			{
				$data['supplier_id'] = '';
				$data['supplier_id_txt'] = '';
			}
			
			if($db->request('name')){ $data['name'] = $db->request('name'); }
			else{ $data['name'] = ''; }
			
			if($db->request('description')){ $data['description'] = $db->request('description'); }
			else{ $data['description'] = ''; }
			
			if($db->request('barcode')){ $data['barcode'] = $db->request('barcode'); }
			else{ $data['barcode'] = ''; }
			
			if($db->request('barcode_name')){ $data['barcode_name'] = $db->request('barcode_name'); }
			else{ $data['barcode_name'] = ''; }
			
			if($db->request('selling_price')){ $data['selling_price'] = $db->request('selling_price'); }
			else{ $data['selling_price'] = 0; }
			
			if($db->request('minimum_selling_price')){ $data['minimum_selling_price'] = $db->request('minimum_selling_price'); }
			else{ $data['minimum_selling_price'] = ''; }
			
			if($db->request('cost')){ $data['cost'] = $db->request('cost'); }
			else{ $data['cost'] = 0; }
			
			if($db->request('re_order_qty')){ $data['re_order_qty'] = $db->request('re_order_qty'); }
			else{ $data['re_order_qty'] = 0; }
			
			if($db->request('order_qty')){ $data['order_qty'] = $db->request('order_qty'); }
			else{ $data['order_qty'] = 0; }
			
			if($db->request('minimum_qty')){ $data['minimum_qty'] = $db->request('minimum_qty'); }
			else{ $data['minimum_qty'] = 1; }
			
			if($db->request('status')){ $data['status'] = $db->request('status');}
			elseif($db->request('status')==0){ $data['status'] = 0; }
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countItemsByName = $InventoryMasterItemsQuery->gets("WHERE name='".$data['name']."'");
				$countItemsByName = count($countItemsByName);
				
				$countItemsByBarcode = $InventoryMasterItemsQuery->gets("WHERE barcode='".$data['barcode']."'");
				$countItemsByBarcode = count($countItemsByBarcode);
				
				
				if(!$InventoryMasterCategoryQuery->has($data['category_id'])){ $error_msg[]="You must choose a valid category."; $error_no++; }
				if(!$InventoryMasterUnitsQuery->has($data['unit_id'])){ $error_msg[]="You must choose a valid unit."; $error_no++; }
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countItemsByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(strlen($data['description'])<3){ $error_msg[]="Description must be minimum 3 letters"; $error_no++; }
				
				if($data['barcode'] && $countItemsByBarcode){ $error_msg[]="The barcode already exists"; $error_no++; }
				
				if($data['selling_price']<1){ $error_msg[]="Selling price must be greater than 1."; $error_no++; }
				if($data['minimum_selling_price']<1){ $error_msg[]="Minimum selling price must be greater than 1."; $error_no++; }
				
				if(!$error_no)
				{
					
					$InventoryMasterItemsQuery->create($data);
					$firewallCls->addLog("Item Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'inventory/master_items_form.php';
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
		global $InventoryMasterItemsQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		global $InventoryMasterWarrantyQuery;	
		global $SuppliersMasterSuppliersQuery;
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getItemInfo = $InventoryMasterItemsQuery->get($id);
			
			if($getItemInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/master_items/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
				$data['category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0 ORDER BY name ASC");
				$data['brand_list'] = $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
				$data['unit_list'] = $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
				
				$data['item_id'] = $getItemInfo['item_id'];
					
				if($db->request('category_id')){ $data['category_id'] = $db->request('category_id'); }
				else{ $data['category_id'] = $getItemInfo['category_id']; }
				
				if($db->request('brand_id')){ $data['brand_id'] = $db->request('brand_id'); }
				else{ $data['brand_id'] = $getItemInfo['brand_id']; }
				
				if($db->request('unit_id')){ $data['unit_id'] = $db->request('unit_id'); }
				else{ $data['unit_id'] = $getItemInfo['unit_id']; }
				
				if($db->request('supplier_id'))
				{
					$data['supplier_id'] = $db->request('supplier_id');
					$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'name');
				}
				else
				{
					$data['supplier_id'] = $getItemInfo['supplier_id'];
					$data['supplier_id_txt'] = $SuppliersMasterSuppliersQuery->data($getItemInfo['supplier_id'],'name');
				}
				
				if($db->request('name')){ $data['name'] = $db->request('name'); }
				else{ $data['name'] = $getItemInfo['name']; }
				
				if($db->request('description')){ $data['description'] = $db->request('description'); }
				else{ $data['description'] = $getItemInfo['description']; }
			
				if($db->request('barcode')){ $data['barcode'] = $db->request('barcode'); }
				else{ $data['barcode'] = $getItemInfo['barcode']; }
				
				if($db->request('barcode_name')){ $data['barcode_name'] = $db->request('barcode_name'); }
				else{ $data['barcode_name'] = $getItemInfo['barcode_name']; }
				
				if($db->request('selling_price')){ $data['selling_price'] = $db->request('selling_price'); }
				else{ $data['selling_price'] = $getItemInfo['selling_price']; }
				
				if($db->request('minimum_selling_price')){ $data['minimum_selling_price'] = $db->request('minimum_selling_price'); }
				else{ $data['minimum_selling_price'] = $getItemInfo['minimum_selling_price']; }
				
				if($db->request('cost')){ $data['cost'] = $db->request('cost'); }
				else{ $data['cost'] = $getItemInfo['cost']; }
				
				if($db->request('re_order_qty')){ $data['re_order_qty'] = $db->request('re_order_qty'); }
				else{ $data['re_order_qty'] = $getItemInfo['re_order_qty']; }
				
				if($db->request('order_qty')){ $data['order_qty'] = $db->request('order_qty'); }
				else{ $data['order_qty'] = $getItemInfo['order_qty']; }
			
				if($db->request('minimum_qty')){ $data['minimum_qty'] = $db->request('minimum_qty'); }
				else{ $data['minimum_qty'] = $getItemInfo['minimum_qty']; }
				
				if($db->request('status')){ $data['status'] = $db->request('status'); }
				elseif($db->request('status')==0){ $data['status'] = 0; }
				else{ $data['status'] = $getItemInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getItemInfo['name'])
					{
						$countItemsByName = $InventoryMasterItemsQuery->gets("WHERE name='".$data['name']."'");
						$countItemsByName = count($countItemsByName);
						
						if($countItemsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$InventoryMasterItemsQuery->edit($data);
						$firewallCls->addLog("Items Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'inventory/master_items_form.php';
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
				$error_msg[]="Invalid item Id"; $error_no++;
					
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
	
	
	
	

    public function editprice() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$customer_group_list = $CustomersMasterCustomergroupsQuery->gets("ORDER BY name ASC");
			
			$getItemInfo = $InventoryMasterItemsQuery->get($id);
			
			if($getItemInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/master_items/editprice/".$id."/";
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
				
				
				$data['item_id'] = $getItemInfo['item_id'];
					
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					$data['customer_group_list'] = [];
					
					foreach($customer_group_list as $cgp)
					{
						
						if($db->request('price'.$cgp['customer_group_id'])<$InventoryMasterItemsQuery->data($id,'minimum_selling_price'))
						{
							$error_msg[]=$CustomersMasterCustomergroupsQuery->data($cgp['customer_group_id'],'name')." price is less then minimum selling price."; $error_no++;
						}
						else
						{
						
							if($db->request('price'.$cgp['customer_group_id'])){ $price = $db->request('price'.$cgp['customer_group_id']);}
							else{ $price = $InventoryMasterItemsQuery->data($id,'selling_price'); }
							
							$data['customer_group_list'][] = array(
																'item_id' => $id,
																'customer_group_id' => $cgp['customer_group_id'],
																'price' => $price
															);
						}
					}
						
					if(!$error_no)
					{
						
						$InventoryMasterItemsQuery->editCustomerGroupPrice($data);
						$firewallCls->addLog("Items Group Price Updated: ".$InventoryMasterItemsQuery->data($id,'name'));
						
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
					
					$data['sellingPrice'] = $defCls->num($InventoryMasterItemsQuery->data($id,'selling_price'));
					$data['minimumSellingPrice'] = $defCls->num($InventoryMasterItemsQuery->data($id,'minimum_selling_price'));
					
					$data['customer_group_list'] = [];
					
					foreach($customer_group_list as $cgp)
					{
						
						$getCustomerGroupPrice = $InventoryMasterItemsQuery->getCustomerGroupPrice($cgp['customer_group_id'],$id);
						
						$data['customer_group_list'][] = array(
																'customer_group_id' => $cgp['customer_group_id'],
																'name' => $cgp['name'],
																'price' => $defCls->num($getCustomerGroupPrice)
															);
															
					}
		
					$this_required_file = _HTML.'inventory/master_items_price_form.php';
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
				$error_msg[]="Invalid item Id"; $error_no++;
					
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
