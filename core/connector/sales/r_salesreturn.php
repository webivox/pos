<?php

class SalesRSalesreturnConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionsReturnQuery;
		global $SalesMasterRepQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Sales Return Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('sales/r_salesreturn/view/');
			
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['sales_rep_list']	= $SalesMasterRepQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			$data['category_list']	= $InventoryMasterCategoryQuery->gets("WHERE parent_Category_id=0 ORDER BY name ASC");
			$data['brand_list']	= $InventoryMasterBrandsQuery->gets("ORDER BY name ASC");
			$data['unit_list']	= $InventoryMasterUnitsQuery->gets("ORDER BY name ASC");
			$data['supplier_list']	= $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/r_salesreturn.php";
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
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SalesTransactionsReturnQuery;
		global $SystemMasterLocationsQuery;
		global $SalesMasterRepQuery;
		global $InventoryMasterCategoryQuery;
		global $InventoryMasterBrandsQuery;
		global $InventoryMasterUnitsQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
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
			
			if(isset($_REQUEST['search_customer'])){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			if(isset($_REQUEST['search_location'])){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if(isset($_REQUEST['search_sales_rep'])){ $search_sales_rep=$db->request('search_sales_rep'); }
			else{ $search_sales_rep=''; }
			
			if(isset($_REQUEST['search_user'])){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			if(isset($_REQUEST['search_category'])){ $search_category=$db->request('search_category'); }
			else{ $search_category=''; }
			
			if(isset($_REQUEST['search_brand'])){ $search_brand=$db->request('search_brand'); }
			else{ $search_brand=''; }
			
			if(isset($_REQUEST['search_unit'])){ $search_unit=$db->request('search_unit'); }
			else{ $search_unit=''; }
			
			if(isset($_REQUEST['search_supplier'])){ $search_supplier=$db->request('search_supplier'); }
			else{ $search_supplier=''; }
			
			if(isset($_REQUEST['search_barcode'])){ $search_barcode=$db->request('search_barcode'); }
			else{ $search_barcode=''; }
			
			if(isset($_REQUEST['search_barcode_name'])){ $search_barcode_name=$db->request('search_barcode_name'); }
			else{ $search_barcode_name=''; }
			
			if(isset($_REQUEST['search_item_name'])){ $search_item_name=$db->request('search_item_name'); }
			else{ $search_item_name=''; }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_customer){ $filter_heading .= ' | Customer : '.$CustomersMasterCustomersQuery->data($search_customer,'name'); }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_sales_rep){ $filter_heading .= ' | Sales Rep : '.$SalesMasterRepQuery->data($search_sales_rep,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			if($search_category){ $filter_heading .= ' | Category : '.$InventoryMasterCategoryQuery->data($search_category,'name'); }
			if($search_brand){ $filter_heading .= ' | Brand : '.$InventoryMasterBrandsQuery->data($search_brand,'name'); }
			if($search_unit){ $filter_heading .= ' | Unit : '.$InventoryMasterUnitsQuery->data($search_unit,'name'); }
			if($search_supplier){ $filter_heading .= ' | Supplier : '.$SuppliersMasterSuppliersQuery->data($search_supplier,'name'); }
			if($search_barcode){ $filter_heading .= ' | Barcode : '.$search_barcode; }
			if($search_barcode_name){ $filter_heading .= ' | Barcode Name : '.$search_barcode_name; }
			if($search_item_name){ $filter_heading .= ' | Item Name : '.$search_item_name; }
			
			$data['title_tag'] = 'Sales Return Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE sales_return_items.sales_return_item_id!=0";
			
			if($search_date_from){ $sql.=" AND DATE(sales_return.added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(sales_return.added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND sales_return.location_id='".$search_location."'"; }
			if($search_customer){ $sql.=" AND sales_return.customer_id='".$search_customer."'"; }
			if($search_user){ $sql.=" AND sales_return.user_id='".$search_user."'"; }
			
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_supplier){ $sql.=" AND inventory_items.supplier_id='".$search_supplier."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.=" ORDER BY sales_return.sales_return_id DESC";
			
			/////
			
			$getSales = $db->fetchAll("SELECT 
			
										sales_return_items.sales_return_item_id AS `rni_item_id`,
										sales_return_items.sales_return_id AS `rni_sales_return_id`,
										sales_return_items.item_id AS `rni_item_id`,
										sales_return_items.cost AS `rni_cost`,
										sales_return_items.price AS `rni_price`,
										sales_return_items.qty AS `rni_qty`,
										sales_return_items.total AS `rni_total`,

										
										sales_return.sales_return_id AS `rn_sales_return_id`,
										sales_return.location_id AS `rn_location_id`,
										sales_return.user_id AS `rn_user_id`,
										sales_return.customer_id AS `rn_customer_id`,
										sales_return.added_date AS `rn_added_date`,
										sales_return.invoice_no AS `rn_invoice_no`,
										sales_return.total_value AS `rn_total_value`,
										sales_return.used_value AS `rn_used_value`,
										sales_return.balance_value AS `rn_balance_value`,
										sales_return.total_value AS `rn_total_value`,
										sales_return.no_of_items AS `rn_no_of_items`,
										sales_return.no_of_qty AS `rn_no_of_qty`,
										sales_return.remarks AS `rn_remarks`,

										
										inventory_items.item_id AS `ii_item_id`,
										inventory_items.category_id AS `ii_category_id`,
										inventory_items.brand_id AS `ii_brand_id`,
										inventory_items.unit_id AS `ii_unit_id`,
										inventory_items.supplier_id AS `ii_supplier_id`,
										inventory_items.name AS `ii_name`,
										inventory_items.description AS `ii_description`,
										inventory_items.barcode AS `ii_barcode`,
										inventory_items.barcode_name AS `ii_barcode_name`
			
										FROM 
											sales_return_items
										INNER JOIN 
											sales_return ON sales_return_items.sales_return_id = sales_return.sales_return_id
										INNER JOIN 
											inventory_items ON sales_return_items.item_id = inventory_items.item_id
										
										".$sql);
			
			$data['rows'] = array();
			
			
			
			$currentInvoiceId = null;
			
			foreach($getSales as $cat)
			{
				
				if ($currentInvoiceId !== $cat['rn_sales_return_id']) {
					
					if(!$currentInvoiceId)
					{
						$totalNoOfItem = 0;
						$totalPrice = 0;
						$totalQty = 0;
						$totalCost = 0;
						$totalProfit = 0;
					}
					
					
					$totalProfit = $totalPrice-$totalCost;
					
					if($totalProfit){ $totalProfitPercentage = ($totalProfit / $totalCost) * 100; }
					else{ $totalProfitPercentage = 0; }
					
					$data['rows'][] = array(
											'currentInvoiceId' => $currentInvoiceId,
											'return_row' => true,
											'return_item_row' => true,
											'added_date' => date('d-m-Y',strtotime($cat['rn_added_date'])),
											'return_no' => $defCls->docNo('SRET-',$cat['rn_invoice_no']),
											'invoice_no' => $cat['rn_invoice_no'],
											'customer' => $CustomersMasterCustomersQuery->data($cat['rn_customer_id'],'name'),
											'location' => $SystemMasterLocationsQuery->data($cat['rn_location_id'],'name'),
											'user' => $SystemMasterUsersQuery->data($cat['rn_user_id'],'name'),
											'remarks' => $defCls->showText($cat['rn_remarks']),
											
											'totalQty' => $defCls->num($totalQty),
											'totalPrice' => $defCls->money($totalPrice),
											'totalCost' => $defCls->money($totalCost),
											'totalNoOfItem' => $defCls->docNo('',$totalNoOfItem),
											'totalProfit' => $defCls->money($totalProfit),
											'totalProfitPercentage' => $defCls->num($totalProfitPercentage)
										);
										
					
					$currentInvoiceId = $cat['rn_sales_return_id'];
					
					$totalNoOfItem = 0;
					$totalQty = 0;
					$totalPrice = 0;
					$totalCost = 0;
					$totalProfit = 0;
										
					
				}
				else{ $data['show_footer'] = false; }
				
				
				$returnQtys = $cat['rn_no_of_qty'];
				
				
				$profit = $cat['rni_price']-$cat['rni_cost'];
				$profitPercentage = ($profit / $cat['rni_cost']) * 100;
				
				$totalNoOfItem += 1;
				$totalQty += $cat['rni_qty'];
				$totalPrice += $cat['rni_price'];
				$totalCost += $cat['rni_cost'];
				$totalProfit += $profit;
				
				
				
				$lineTotalProfit = $totalPrice-$totalCost;
				$lineTotalProfitPercentage = ($lineTotalProfit / $totalCost) * 100;
			
				
				$data['rows'][] = array(
										'currentInvoiceId' => false,
										'return_row' => false,
										'return_item_row' => true,
										'item_id' => $defCls->docNo('',$cat['ii_item_id']),
										'item_name' => $cat['ii_name'],
										'qty' => $defCls->num($cat['rni_qty']),
										'price' => $defCls->money($cat['rni_price']),
										'cost' => $defCls->money($cat['rni_cost']),
										'profit' => $defCls->money($profit),
										'profit_percentage' => $defCls->money($profitPercentage),
											
										'totalQty' => $defCls->num($totalQty),
										'totalPrice' => $defCls->money($totalPrice),
										'totalCost' => $defCls->money($totalCost),
										'totalNoOfItem' => $defCls->docNo('',$totalNoOfItem),
										'totalProfit' => $defCls->money($totalProfit),
										'totalProfitPercentage' => $defCls->num($lineTotalProfitPercentage)
									);
			}
			
			



	
			$this_required_file = _HTML.'sales/r_salesreturn_view.php';
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
