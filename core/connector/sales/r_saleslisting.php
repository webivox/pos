<?php

class SalesRSaleslistingConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
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
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('sales/r_saleslisting/view/');
			
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
			
			if($db->request('search_no')){
				$search_no=$db->request('search_no');
			}
			else{ $search_no=''; }
			
			if($db->request('search_date_from')){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if($db->request('search_date_to')){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if($db->request('search_customer')!==''){ $search_customer=$db->request('search_customer'); }
			else{ $search_customer=''; }
			
			if($db->request('search_location')!==''){ $search_location=$db->request('search_location'); }
			else{ $search_location=''; }
			
			if($db->request('search_sales_rep')!==''){ $search_sales_rep=$db->request('search_sales_rep'); }
			else{ $search_sales_rep=''; }
			
			if($db->request('search_user')!==''){ $search_user=$db->request('search_user'); }
			else{ $search_user=''; }
			
			if($db->request('search_category')!==''){ $search_category=$db->request('search_category'); }
			else{ $search_category=''; }
			
			if($db->request('search_brand')!==''){ $search_brand=$db->request('search_brand'); }
			else{ $search_brand=''; }
			
			if($db->request('search_unit')!==''){ $search_unit=$db->request('search_unit'); }
			else{ $search_unit=''; }
			
			if($db->request('search_supplier')!==''){ $search_supplier=$db->request('search_supplier'); }
			else{ $search_supplier=''; }
			
			if($db->request('search_barcode')!==''){ $search_barcode=$db->request('search_barcode'); }
			else{ $search_barcode=''; }
			
			if($db->request('search_barcode_name')!==''){ $search_barcode_name=$db->request('search_barcode_name'); }
			else{ $search_barcode_name=''; }
			
			if($db->request('search_item_name')!==''){ $search_item_name=$db->request('search_item_name'); }
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
			
			$data['title_tag'] = 'Sales Listing Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE sales_invoices.status=1";
			
			if($search_date_from){ $sql.=" AND DATE(sales_invoices.added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(sales_invoices.added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_location){ $sql.=" AND sales_invoices.location_id='".$search_location."'"; }
			if($search_customer){ $sql.=" AND sales_invoices.customer_id='".$search_customer."'"; }
			if($search_sales_rep){ $sql.=" AND sales_invoices.sales_rep_id='".$search_sales_rep."'"; }
			if($search_user){ $sql.=" AND sales_invoices.user_id='".$search_user."'"; }
			
			if($search_category){ $sql.=" AND inventory_items.category_id='".$search_category."'"; }
			if($search_brand){ $sql.=" AND inventory_items.brand_id='".$search_brand."'"; }
			if($search_unit){ $sql.=" AND inventory_items.unit_id='".$search_unit."'"; }
			if($search_supplier){ $sql.=" AND inventory_items.supplier_id='".$search_supplier."'"; }
			if($search_barcode){ $sql.=" AND inventory_items.barcode='".$search_barcode."'"; }
			if($search_barcode_name){ $sql.=" AND inventory_items.barcode_name LIKE '%$search_barcode_name%'"; }
			if($search_item_name){ $sql.=" AND inventory_items.name LIKE '%$search_item_name%'";  }
			
			$sql.=" ORDER BY sales_invoices.invoice_id DESC";
			
			/////
			
			$getSales = $db->fetchAll("SELECT 
			
										sales_invoice_items.invoice_item_id AS `sii_item_id`,
										sales_invoice_items.invoice_id AS `sii_invoice_id`,
										sales_invoice_items.item_id AS `sii_item_id_fk`,
										sales_invoice_items.cost AS `sii_cost`,
										sales_invoice_items.master_price AS `sii_master_price`,
										sales_invoice_items.price AS `sii_price`,
										sales_invoice_items.discount AS `sii_discount`,
										sales_invoice_items.unit_price AS `sii_unit_price`,
										sales_invoice_items.qty AS `sii_qty`,
										sales_invoice_items.total AS `sii_total`,

										
										sales_invoices.invoice_id AS `si_invoice_id`,
										sales_invoices.location_id AS `si_location_id`,
										sales_invoices.user_id AS `si_user_id`,
										sales_invoices.cashier_point_id AS `si_cashier_point_id`,
										sales_invoices.customer_id AS `si_customer_id`,
										sales_invoices.sales_rep_id AS `si_sales_rep_id`,
										sales_invoices.invoice_no AS `si_invoice_no`,
										sales_invoices.added_date AS `si_added_date`,
										sales_invoices.total_sale AS `si_total_sale`,
										sales_invoices.discount_type AS `si_discount_type`,
										sales_invoices.discount_value AS `si_discount_value`,
										sales_invoices.discount_amount AS `si_discount_amount`,
										sales_invoices.total_sale_cost AS `si_total_sale_cost`,
										sales_invoices.total_paid AS `si_total_paid`,
										sales_invoices.cash_sales AS `si_cash_sales`,
										sales_invoices.card_sales AS `si_card_sales`,
										sales_invoices.return_sales AS `si_return_sales`,
										sales_invoices.gift_card_sales AS `si_gift_card_sales`,
										sales_invoices.loyalty_sales AS `si_loyalty_sales`,
										sales_invoices.credit_sales AS `si_credit_sales`,
										sales_invoices.cheque_sales AS `si_cheque_sales`,
										sales_invoices.comments AS `si_comments`,
										sales_invoices.status AS `si_status`,

										
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
											sales_invoice_items
										INNER JOIN 
											sales_invoices ON sales_invoice_items.invoice_id = sales_invoices.invoice_id
										INNER JOIN 
											inventory_items ON sales_invoice_items.item_id = inventory_items.item_id
										
										".$sql);
			
			$data['rows'] = array();
			
			
			
			$currentInvoiceId = null;
			
			foreach($getSales as $cat)
			{
				
				if ($currentInvoiceId !== $cat['si_invoice_id']) {
					
					if(!$currentInvoiceId)
					{
						$totalNoOfItem = 0;
						$totalQty = 0;
						$totalMasterPrice = 0;
						$totalSellingPrice	 = 0;
						$totalUnitPrice = 0;
						$totalGSD = 0;
						$totalFinalPrice = 0;
						$totalCost = 0;
						$totalProfit = 0;
						
					}
					
					
					$totalProfit = $totalFinalPrice-$totalCost;
					
					if($totalProfit){ $totalProfitPercentage = ($totalProfit / $totalCost) * 100; }
					else{ $totalProfitPercentage = 0; }
					
					$data['rows'][] = array(
											'currentInvoiceId' => $currentInvoiceId,
											'invoice_row' => true,
											'invoice_item_row' => true,
											'added_date' => date('d-m-Y H:i:s',strtotime($cat['si_added_date'])),
											'invoice_no' => $cat['si_invoice_no'],
											'customer' => $CustomersMasterCustomersQuery->data($cat['si_customer_id'],'name'),
											'sales_rep' => $SalesMasterRepQuery->data($cat['si_sales_rep_id'],'name'),
											'location' => $SystemMasterLocationsQuery->data($cat['si_location_id'],'name'),
											'user' => $SystemMasterUsersQuery->data($cat['si_user_id'],'name'),
											
											'totalQty' => $defCls->num($totalQty),
											'totalMasterPrice' => $defCls->money($totalMasterPrice),
											'totalSellingPrice' => $defCls->money($totalSellingPrice),
											'totalUnitPrice' => $defCls->money($totalUnitPrice),
											'totalGSD' => $defCls->money($totalGSD),
											'totalFinalPrice' => $defCls->money($totalFinalPrice),
											'totalCost' => $defCls->money($totalCost),
											'totalNoOfItem' => $defCls->docNo('',$totalNoOfItem),
											'totalProfit' => $defCls->money($totalProfit),
											'totalProfitPercentage' => $defCls->num($totalProfitPercentage)
										);
										
					
					$currentInvoiceId = $cat['si_invoice_id'];
					
					$totalNoOfItem = 0;
					$totalQty = 0;
					$totalMasterPrice = 0;
					$totalSellingPrice	 = 0;
					$totalUnitPrice = 0;
					$totalGSD = 0;
					$totalFinalPrice = 0;
					$totalCost = 0;
					$totalProfit = 0;
										
					
				}
				else{ $data['show_footer'] = false; }
				
				
				$invoiceQtys = $db->fetch("
					SELECT SUM(qty) AS total_qty
					FROM sales_invoice_items
					WHERE invoice_id = '".$cat['si_invoice_id']."'
				");


				if($cat['si_discount_amount']>0){ $gsdAmt = $cat['si_discount_amount']/$invoiceQtys['total_qty']; }
				else{ $gsdAmt = 0; }
				
				$finalPrice = $cat['sii_unit_price']-$gsdAmt;
				
				$profit = $finalPrice-$cat['sii_cost'];
				$profitPercentage = ($profit / $cat['sii_cost']) * 100;
				
				$totalNoOfItem += 1;
				$totalQty += $cat['sii_qty'];
				$totalMasterPrice += $cat['sii_master_price'];
				$totalSellingPrice	+= $cat['sii_price'];
				$totalUnitPrice += $cat['sii_unit_price'];
				$totalGSD += $gsdAmt;
				$totalFinalPrice += $finalPrice;
				$totalCost += $cat['sii_cost'];
				$totalProfit += $profit;
				
				
			
				
				$data['rows'][] = array(
										'currentInvoiceId' => false,
										'invoice_row' => false,
										'invoice_item_row' => true,
										'item_id' => $defCls->docNo('',$cat['ii_item_id']),
										'item_name' => $cat['ii_name'],
										'qty' => $defCls->num($cat['sii_qty']),
										'master_price' => $defCls->money($cat['sii_master_price']),
										'selling_price' => $defCls->money($cat['sii_price']),
										'discount' => $defCls->money($cat['sii_discount']).'%',
										'unit_price' => $defCls->money($cat['sii_unit_price']),
										'gsd' => $defCls->money($gsdAmt),
										'final_price' => $defCls->money($finalPrice),
										'cost' => $defCls->money($cat['sii_cost']),
										'profit' => $defCls->money($profit),
										'profit_percentage' => $defCls->money($profitPercentage)
									);
			}
			
			



	
			$this_required_file = _HTML.'sales/r_saleslisting_view.php';
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
