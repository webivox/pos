<?php

class InventoryRTransferConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryTransactionsTransfernotesQuery;
		global $SystemMasterLocationsQuery;
		global $SuppliersMasterSuppliersQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Stock Transfer Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('inventory/r_transfer/view/');
			
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['category_list']	= $InventoryMasterCategoryQuery->gets("WHERE parent_Category_id=0 ORDER BY name ASC");
			$data['brand_list']	= $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list']	= $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/r_transfer.php";
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
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_location_from'])){ $search_location_from=$db->request('search_location_from'); }
			else{ $search_location_from=''; }
			
			if(isset($_REQUEST['search_location_to'])){ $search_location_to=$db->request('search_location_to'); }
			else{ $search_location_to=''; }
			
			if(isset($_REQUEST['search_user'])){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
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
			if($search_location_from){ $filter_heading .= ' | Location From : '.$SystemMasterLocationsQuery->data($search_location_from,'name'); }
			if($search_location_to){ $filter_heading .= ' | Location To : '.$SystemMasterLocationsQuery->data($search_location_to,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			if($search_category){ $filter_heading .= ' | Category : '.$InventoryMasterCategoryQuery->data($search_category,'name'); }
			if($search_brand){ $filter_heading .= ' | Brand : '.$InventoryMasterBrandsQuery->data($search_brand,'name'); }
			if($search_unit){ $filter_heading .= ' | Unit : '.$InventoryMasterUnitsQuery->data($search_unit,'name'); }
			if($search_barcode){ $filter_heading .= ' | Barcode : '.$search_barcode; }
			if($search_barcode_name){ $filter_heading .= ' | Barcode Name : '.$search_barcode_name; }
			if($search_item_name){ $filter_heading .= ' | Item Name : '.$search_item_name; }
			
			$data['title_tag'] = 'Inventory Transfer Note Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE inventory_transfer_notes.transfer_note_id!=0";
			
			if($search_date_from){ $sql.=" AND DATE(inventory_transfer_notes.added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(inventory_transfer_notes.added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location_from){ $sql.=" AND inventory_transfer_notes.location_from_id='".$search_location_from."'"; }
			if($search_location_to){ $sql.=" AND inventory_transfer_notes.location_to_id='".$search_location_to."'"; }
			if($search_user){ $sql.=" AND inventory_transfer_notes.user_id='".$search_user."'"; }
			
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.=" ORDER BY inventory_transfer_notes.transfer_note_id DESC";
			
			/////
			
			$getRN = $db->fetchAll("SELECT 
			
										inventory_transfer_note_items.transfer_note_item_id AS `irni_transfer_note_item_id`,
										inventory_transfer_note_items.transfer_note_id AS `irni_transfer_note_id`,
										inventory_transfer_note_items.item_id AS `irni_item_id`,
										inventory_transfer_note_items.qty AS `irni_qty`,

										
										inventory_transfer_notes.transfer_note_id AS `irn_transfer_note_id`,
										inventory_transfer_notes.location_from_id AS `irn_location_from_id`,
										inventory_transfer_notes.location_to_id AS `irn_location_to_id`,
										inventory_transfer_notes.user_id AS `irn_user_id`,
										inventory_transfer_notes.added_date AS `irn_added_date`,
										inventory_transfer_notes.no_of_items AS `irn_no_of_items`,
										inventory_transfer_notes.no_of_qty AS `irn_no_of_qty`,
										
										
										inventory_items.item_id AS `ii_item_id`,
										inventory_items.category_id AS `ii_category_id`,
										inventory_items.brand_id AS `ii_brand_id`,
										inventory_items.unit_id AS `ii_unit_id`,
										inventory_items.name AS `ii_name`,
										inventory_items.description AS `ii_description`,
										inventory_items.barcode AS `ii_barcode`,
										inventory_items.barcode_name AS `ii_barcode_name`
			
										FROM 
											inventory_transfer_note_items
										INNER JOIN 
											inventory_transfer_notes ON inventory_transfer_note_items.transfer_note_id = inventory_transfer_notes.transfer_note_id
										INNER JOIN 
											inventory_items ON inventory_transfer_note_items.item_id = inventory_items.item_id
										
										".$sql);
			
			$data['rows'] = array();
			
			
			
			$currentRNId = null;
			
			foreach($getRN as $cat)
			{
				
				if ($currentRNId !== $cat['irn_transfer_note_id']) {
					
					if(!$currentRNId)
					{
						$totalNoOfItem = 0;
						$totalQty = 0;
						
					}
					
					
					
					$data['rows'][] = array(
											'currentRNId' => $currentRNId,
											'rnh_row' => true,
											'rn_item_row' => true,
											'rn_no' => $defCls->docNo('TRN-',$cat['irn_transfer_note_id']),
											'added_date' => date('d-m-Y',strtotime($cat['irn_added_date'])),
											'location_from' => $SystemMasterLocationsQuery->data($cat['irn_location_from_id'],'name'),
											'location_to' => $SystemMasterLocationsQuery->data($cat['irn_location_to_id'],'name'),
											'user' => $SystemMasterUsersQuery->data($cat['irn_user_id'],'name'),
											
											'totalNoOfItem' => $defCls->num($totalNoOfItem),
											'totalQty' => $defCls->num($totalQty)
										);
										
					
					$currentRNId = $cat['irn_transfer_note_id'];
					
					
					$totalNoOfItem = 0;
					$totalQty = 0;
										
					
				}
				else{ $data['show_footer'] = false; }
				
				$totalNoOfItem += 1;
				$totalQty += $cat['irni_qty'];
				
				
				
				
			
				
				$data['rows'][] = array(
										'currentRNId' => false,
										'rnh_row' => false,
										'rn_item_row' => true,
										'item_id' => $defCls->docNo('',$cat['ii_item_id']),
										'item_name' => $cat['ii_name'],
										'qty' => $defCls->num($cat['irni_qty'])
									);
			}
			
			



	
			$this_required_file = _HTML.'inventory/r_transfer_view.php';
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
