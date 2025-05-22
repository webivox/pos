<?php

class InventoryRStockListingConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $SuppliersMasterSuppliersQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Stock Listing | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('inventory/r_stock_listing/view/');
			
			$data['supplier_list']	= $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			$data['category_list']	= $InventoryMasterCategoryQuery->gets("WHERE parent_Category_id=0 ORDER BY name ASC");
			$data['brand_list']	= $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list']	= $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/r_stock_listing.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function view()
	{
		global $db;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryMasterItemsQuery;
		global $SuppliersMasterSuppliersQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			
			
			
			if(isset($_REQUEST['search_customer'])){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			/////////////
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_location'])){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if(isset($_REQUEST['search_supplier'])){ $search_supplier=$db->request('search_supplier'); }
			else{ $search_supplier=''; }
			
			if(isset($_REQUEST['search_category'])){ $search_category=$db->request('search_category'); }
			else{ $search_category=''; }
			
			if(isset($_REQUEST['search_brand'])){ $search_brand=$db->request('search_brand'); }
			else{ $search_brand=''; }
			
			if(isset($_REQUEST['search_unit'])){ $search_unit=$db->request('search_unit'); }
			else{ $search_unit=''; }
			
			if(isset($_REQUEST['search_barcode'])){ $search_barcode=$db->request('search_barcode'); }
			else{ $search_barcode=''; }
			
			if(isset($_REQUEST['search_barcode_name'])){ $search_barcode_name=$db->request('search_barcode_name'); }
			else{ $search_barcode_name=''; }
			
			if(isset($_REQUEST['search_item_name'])){ $search_item_name=$db->request('search_item_name'); }
			else{ $search_item_name=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_supplier){ $filter_heading .= ' | Supplier : '.$SuppliersMasterSuppliersQuery->data($search_supplier,'name'); }
			if($search_category){ $filter_heading .= ' | Category : '.$InventoryMasterCategoryQuery->data($search_category,'name'); }
			if($search_brand){ $filter_heading .= ' | Brand : '.$InventoryMasterBrandsQuery->data($search_brand,'name'); }
			if($search_unit){ $filter_heading .= ' | Unit : '.$InventoryMasterUnitsQuery->data($search_unit,'name'); }
			if($search_barcode){ $filter_heading .= ' | Barcode : '.$search_barcode; }
			if($search_barcode_name){ $filter_heading .= ' | Barcode Name : '.$search_barcode_name; }
			if($search_item_name){ $filter_heading .= ' | Item Name : '.$search_item_name; }
			
			$data['title_tag'] = 'Stock Listing Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			///////////
			$sql=" WHERE item_id != 0";
			
			if($search_supplier){ $sql.=" AND inventory_items.supplier_id='".$search_supplier."'"; }
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.="  ORDER BY name ASC";
			
			$getRows = $InventoryMasterItemsQuery->gets($sql);
			
			$data['rows'] = array();
			
			$totalIn = 0;
			$totalOut = 0;
			$totalAvailable = 0;
			$totalValue = 0;
			
			foreach($getRows as $cat)
			{
				
				if($search_date_to){ $dateToTRN = $dateCls->dateToDB($search_date_to); }
				else{ $dateToTRN = $dateCls->todayDate('Y-m-d'); }
				
				 $rowStock = $db->fetch("SELECT 
											SUM(qty_in) AS total_qty_in, 
											SUM(qty_out) AS total_qty_out 
										FROM inventory_stock_transactions
										WHERE
										item_id = '".$cat['item_id']."'
										AND added_date<='".$dateCls->dateToDB($dateToTRN)."'
									
									");
		
				$in = $rowStock['total_qty_in'];
				$out = $rowStock['total_qty_out'];
				$available = $rowStock['total_qty_in']-$rowStock['total_qty_out'];
				
				$totalIn += $in;
				$totalOut += $out;
				$totalAvailable += $available;
				$totalValue += $cat['cost']*$available;
			
				$data['rows'][] = array(
				
										'no' => $defCls->docNo('',$cat['item_id']),
										'category_id' => $InventoryMasterCategoryQuery->data($cat['category_id'],'name'),
										'brand_id' => $InventoryMasterCategoryQuery->data($cat['brand_id'],'name'),
										'unit_id' => $InventoryMasterCategoryQuery->data($cat['unit_id'],'name'),
										'supplier_id' => $SuppliersMasterSuppliersQuery->data($cat['supplier_id'],'name'),
										'name' => $defCls->showText($cat['name']),
										'description' => $defCls->showText($cat['description']),
										'barcode' => $cat['barcode'],
										'barcode_name' => $defCls->showText($cat['barcode_name']),
										'selling_price' => $defCls->money($cat['selling_price']),
										'minimum_selling_price' => $defCls->money($cat['minimum_selling_price']),
										'cost' => $defCls->money($cat['cost']),
										're_order_qty' => $defCls->num($cat['re_order_qty']),
										'order_qty' => $defCls->num($cat['order_qty']),
										'minimum_qty' => $defCls->num($cat['minimum_qty']),
										'status' => $defCls->getMasterStatus($cat['status']),
										'in' => $defCls->money($in),
										'out' => $defCls->money($out),
										'available' => $defCls->money($available),
										'value' => $defCls->money($cat['closing_stocks']*$cat['cost'])
										
									);
									
			}
			
			$data['totalIn'] = $defCls->money($totalIn);
			$data['totalOut'] = $defCls->money($totalOut);
			$data['totalAvailable'] = $defCls->money($totalAvailable);
			$data['totalValue'] = $defCls->money($totalValue);
			
	
			$this_required_file = _HTML.'inventory/r_stock_listing_view.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	
}
