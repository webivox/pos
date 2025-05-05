<?php

class InventoryRReceivingConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		global $SystemMasterLocationsQuery;
		global $SuppliersMasterSuppliersQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('inventory/r_receiving/view/');
			
			$data['supplier_list']	= $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			$data['category_list']	= $InventoryMasterCategoryQuery->gets("WHERE parent_Category_id=0 ORDER BY name ASC");
			$data['brand_list']	= $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list']	= $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/r_receiving.php";
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
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_location')!==''){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if($db->request('search_supplier')!==''){ $search_supplier=$db->request('search_supplier'); }
			else{ $search_supplier=''; }
			
			if($db->request('search_user')!==''){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			if($db->request('search_category')!==''){ $search_category=$db->request('search_category'); }
			else{ $search_category=''; }
			
			if($db->request('search_brand')!==''){ $search_brand=$db->request('search_brand'); }
			else{ $search_brand=''; }
			
			if($db->request('search_unit')!==''){ $search_unit=$db->request('search_unit'); }
			else{ $search_unit=''; }
			
			if($db->request('search_barcode')!==''){ $search_barcode=$db->request('search_barcode'); }
			else{ $search_barcode=''; }
			
			if($db->request('search_barcode_name')!==''){ $search_barcode_name=$db->request('search_barcode_name'); }
			else{ $search_barcode_name=''; }
			
			if($db->request('search_item_name')!==''){ $search_item_name=$db->request('search_item_name'); }
			else{ $search_item_name=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			if($search_category){ $filter_heading .= ' | Category : '.$InventoryMasterCategoryQuery->data($search_category,'name'); }
			if($search_brand){ $filter_heading .= ' | Brand : '.$InventoryMasterBrandsQuery->data($search_brand,'name'); }
			if($search_unit){ $filter_heading .= ' | Unit : '.$InventoryMasterUnitsQuery->data($search_unit,'name'); }
			if($search_barcode){ $filter_heading .= ' | Barcode : '.$search_barcode; }
			if($search_barcode_name){ $filter_heading .= ' | Barcode Name : '.$search_barcode_name; }
			if($search_item_name){ $filter_heading .= ' | Item Name : '.$search_item_name; }
			
			$data['title_tag'] = 'Inventory Receiving Note Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE inventory_receiving_notes.receiving_note_id!=0";
			
			if($search_date_from){ $sql.=" AND DATE(inventory_receiving_notes.added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(inventory_receiving_notes.added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND inventory_receiving_notes.location_id='".$search_location."'"; }
			if($search_supplier){ $sql.=" AND inventory_receiving_notes.supplier_id='".$search_supplier."'"; }
			if($search_user){ $sql.=" AND inventory_receiving_notes.user_id='".$search_user."'"; }
			
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.=" ORDER BY inventory_receiving_notes.receiving_note_id DESC";
			
			/////
			
			$getRN = $db->fetchAll("SELECT 
			
										inventory_receiving_note_items.item_id AS `irni_item_id`,
										inventory_receiving_note_items.receiving_note_id AS `irni_receiving_note_id`,
										inventory_receiving_note_items.item_id AS `irni_item_id`,
										inventory_receiving_note_items.qty AS `irni_qty`,
										inventory_receiving_note_items.price AS `irni_price`,
										inventory_receiving_note_items.buying_price AS `irni_buying_price`,
										inventory_receiving_note_items.discount AS `irni_discount`,
										inventory_receiving_note_items.final_price AS `irni_final_price`,
										inventory_receiving_note_items.total AS `irni_total`,
										inventory_receiving_note_items.expiriy_date AS `irni_expiriy_date`,

										
										inventory_receiving_notes.receiving_note_id AS `irn_receiving_note_id`,
										inventory_receiving_notes.po_id AS `irn_po_id`,
										inventory_receiving_notes.location_id AS `irn_location_id`,
										inventory_receiving_notes.supplier_id AS `irn_supplier_id`,
										inventory_receiving_notes.user_id AS `irn_user_id`,
										inventory_receiving_notes.added_date AS `irn_added_date`,
										inventory_receiving_notes.invoice_no AS `irn_invoice_no`,
										inventory_receiving_notes.due_date AS `irn_due_date`,
										inventory_receiving_notes.total_value AS `irn_total_value`,
										inventory_receiving_notes.total_saving AS `irn_total_saving`,
										inventory_receiving_notes.no_of_items AS `irn_no_of_items`,
										inventory_receiving_notes.no_of_qty AS `irn_no_of_qty`,
										
										
										inventory_items.item_id AS `ii_item_id`,
										inventory_items.category_id AS `ii_category_id`,
										inventory_items.brand_id AS `ii_brand_id`,
										inventory_items.unit_id AS `ii_unit_id`,
										inventory_items.name AS `ii_name`,
										inventory_items.description AS `ii_description`,
										inventory_items.barcode AS `ii_barcode`,
										inventory_items.barcode_name AS `ii_barcode_name`
			
										FROM 
											inventory_receiving_note_items
										INNER JOIN 
											inventory_receiving_notes ON inventory_receiving_note_items.receiving_note_id = inventory_receiving_notes.receiving_note_id
										INNER JOIN 
											inventory_items ON inventory_receiving_note_items.item_id = inventory_items.item_id
										
										".$sql);
			
			$data['rows'] = array();
			
			
			
			$currentRNId = null;
			
			foreach($getRN as $cat)
			{
				
				if ($currentRNId !== $cat['irn_receiving_note_id']) {
					
					if(!$currentRNId)
					{
						$totalNoOfItem = 0;
						$totalQty = 0;
						$totalPrice = 0;
						$totalBuyingPrice = 0;
						$totalFinalPrice = 0;
						$totalTotal = 0;
						
					}
					
					
					
					$data['rows'][] = array(
											'currentRNId' => $currentRNId,
											'rnh_row' => true,
											'rn_item_row' => true,
											'rn_no' => $defCls->docNo('RN-',$cat['irn_receiving_note_id']),
											'added_date' => date('d-m-Y',strtotime($cat['irn_added_date'])),
											'invoice_no' => $cat['irn_invoice_no'],
											'supplier' => $SuppliersMasterSuppliersQuery->data($cat['irn_supplier_id'],'name'),
											'location' => $SystemMasterLocationsQuery->data($cat['irn_location_id'],'name'),
											'user' => $SystemMasterUsersQuery->data($cat['irn_user_id'],'name'),
											'due_date' => $cat['irn_due_date'],
											
											'totalNoOfItem' => 1,
											'totalQty' => $defCls->num($totalQty),
											'totalPrice' => $defCls->money($totalPrice),
											'totalBuyingPrice' => $defCls->money($totalBuyingPrice),
											'totalFinalPrice' => $defCls->money($totalFinalPrice),
											'totalNoOfItem' => $defCls->docNo('',$totalNoOfItem),
											'totalTotal' => $defCls->money($totalTotal)
										);
										
					
					$currentRNId = $cat['irn_receiving_note_id'];
					
					
					$totalNoOfItem = 0;
					$totalQty = 0;
					$totalPrice = 0;
					$totalBuyingPrice = 0;
					$totalFinalPrice = 0;
					$totalTotal = 0;
										
					
				}
				else{ $data['show_footer'] = false; }
				
				$totalNoOfItem += 1;
				$totalQty += $cat['irni_qty'];
				$totalPrice += $cat['irni_price'];
				$totalBuyingPrice += $cat['irni_buying_price'];
				$totalFinalPrice += $cat['irni_final_price'];
				$totalTotal += $cat['irni_total'];
				
				
				
				
			
				
				$data['rows'][] = array(
										'currentRNId' => false,
										'rnh_row' => false,
										'rn_item_row' => true,
										'item_id' => $defCls->docNo('',$cat['ii_item_id']),
										'item_name' => $cat['ii_name'],
										'qty' => $defCls->num($cat['irni_qty']),
										'price' => $defCls->money($cat['irni_price']),
										'buying_price' => $defCls->money($cat['irni_buying_price']),
										'discount' => $defCls->money($cat['irni_discount']).'%',
										'final_price' => $defCls->money($cat['irni_final_price']),
										'total' => $defCls->money($cat['irni_total']),
									);
			}
			
			



	
			$this_required_file = _HTML.'inventory/r_receiving_view.php';
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
