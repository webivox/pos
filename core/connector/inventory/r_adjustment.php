<?php

class InventoryRAdjustmentConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsAdjustmentnotesQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('inventory/r_adjustment/view/');
			
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			$data['category_list']	= $InventoryMasterCategoryQuery->gets("WHERE parent_Category_id=0 ORDER BY name ASC");
			$data['brand_list']	= $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list']	= $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/r_adjustment.php";
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
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsAdjustmentnotesQuery;
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
			
			/////////////
			
			$sql=" WHERE inventory_adjustment_notes.adjustment_note_id!=0";
			
			if($search_date_from){ $sql.=" AND DATE(inventory_adjustment_notes.added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(inventory_adjustment_notes.added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND inventory_adjustment_notes.location_id='".$search_location."'"; }
			if($search_user){ $sql.=" AND inventory_adjustment_notes.user_id='".$search_user."'"; }
			
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.=" ORDER BY inventory_adjustment_notes.adjustment_note_id DESC";
			
			/////
			
			$getRN = $db->fetchAll("SELECT 
			
										inventory_adjustment_note_items.adjustment_note_item_id AS `irni_adjustment_note_item_id`,
										inventory_adjustment_note_items.adjustment_note_id AS `irni_adjustment_note_id`,
										inventory_adjustment_note_items.item_id AS `irni_item_id`,
										inventory_adjustment_note_items.type AS `irni_type`,
										inventory_adjustment_note_items.qty AS `irni_qty`,
										inventory_adjustment_note_items.amount AS `irni_amount`,
										inventory_adjustment_note_items.total AS `irni_total`,

										
										inventory_adjustment_notes.adjustment_note_id AS `irn_adjustment_note_id`,
										inventory_adjustment_notes.location_id AS `irn_location_id`,
										inventory_adjustment_notes.user_id AS `irn_user_id`,
										inventory_adjustment_notes.added_date AS `irn_added_date`,
										inventory_adjustment_notes.no_of_items AS `irn_no_of_items`,
										inventory_adjustment_notes.no_of_qty AS `irn_no_of_qty`,
										
										
										inventory_items.item_id AS `ii_item_id`,
										inventory_items.category_id AS `ii_category_id`,
										inventory_items.brand_id AS `ii_brand_id`,
										inventory_items.unit_id AS `ii_unit_id`,
										inventory_items.name AS `ii_name`,
										inventory_items.description AS `ii_description`,
										inventory_items.barcode AS `ii_barcode`,
										inventory_items.barcode_name AS `ii_barcode_name`
			
										FROM 
											inventory_adjustment_note_items
										INNER JOIN 
											inventory_adjustment_notes ON inventory_adjustment_note_items.adjustment_note_id = inventory_adjustment_notes.adjustment_note_id
										INNER JOIN 
											inventory_items ON inventory_adjustment_note_items.item_id = inventory_items.item_id
										
										".$sql);
			
			$data['rows'] = array();
			
			
			
			$currentRNId = null;
			
			foreach($getRN as $cat)
			{
				
				if ($currentRNId !== $cat['irn_adjustment_note_id']) {
					
					$data['rows'][] = array(
											'currentRNId' => $currentRNId,
											'rnh_row' => true,
											'rn_item_row' => true,
											'rn_no' => $defCls->docNo('RETN-',$cat['irn_adjustment_note_id']),
											'added_date' => date('d-m-Y',strtotime($cat['irn_added_date'])),
											'location' => $SystemMasterLocationsQuery->data($cat['irn_location_id'],'name'),
											'user' => $SystemMasterUsersQuery->data($cat['irn_user_id'],'name')
										);
										
					
					$currentRNId = $cat['irn_adjustment_note_id'];
					
				}
				
				
				$data['rows'][] = array(
										'currentRNId' => false,
										'rnh_row' => false,
										'rn_item_row' => true,
										'item_id' => $defCls->docNo('',$cat['ii_item_id']),
										'item_name' => $cat['ii_name'],
										'type' => $cat['irni_type'],
										'qty' => $defCls->num($cat['irni_qty']),
										'amount' => $defCls->money($cat['irni_amount']),
										'total' => $defCls->money($cat['irni_total']),
									);
			}
			
			



	
			$this_required_file = _HTML.'inventory/r_adjustment_view.php';
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
