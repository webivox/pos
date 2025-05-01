<?php

class SalesRCalcelledConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SalesMasterRepQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('sales/r_calcelled/view/');
			
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			$data['location_list']	= $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['sales_rep_list']	= $SalesMasterRepQuery->gets("ORDER BY name ASC");
			$data['user_list']	= $SystemMasterUsersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/r_calcelled.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function view()
	{
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SystemMasterLocationsQuery;
		global $SalesMasterRepQuery;
		
		
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
			
			/////////////
			
			$sql=" WHERE status=0";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer){ $sql.=" AND customer_id='".$search_customer."'"; }
			if($search_location){ $sql.=" AND location_id='".$search_location."'"; }
			if($search_sales_rep){ $sql.=" AND sales_rep_id='".$search_sales_rep."'"; }
			if($search_user){ $sql.=" AND user_id='".$search_user."'"; }
			
			///////////
			
			$sql.="  ORDER BY invoice_id DESC";
			
			$getSales = $SalesTransactionInvoicesQuery->gets($sql);
			
			$data['rows'] = array();
			
			$totalTotalSale = 0;
			$totalCost = 0;
			$totalProfit = 0;
			$totalCash = 0;
			$totalCard = 0;
			$totalReturn = 0;
			$totalGiftCard = 0;
			$totalLoyalty = 0;
			$totalCredit = 0;
			$totalCheque = 0;
			
			foreach($getSales as $cat)
			{
				$profit = $cat['total_sale']-$cat['total_sale_cost'];
				$profitPercentage = ($profit / $cat['total_sale_cost']) * 100;
			
				$totalTotalSale += $cat['total_sale'];
				$totalCost += $cat['total_sale_cost'];
				$totalProfit += $profit;
				$totalCash += $cat['cash_sales'];
				$totalCard += $cat['card_sales'];
				$totalReturn += $cat['return_sales'];
				$totalGiftCard += $cat['gift_card_sales'];
				$totalLoyalty += $cat['loyalty_sales'];
				$totalCredit += $cat['credit_sales'];
				$totalCheque += $cat['cheque_sales'];
				
				$data['rows'][] = array(
										'invoice_id' => $cat['invoice_id'],
										'added_date' => date('d-m-Y H:i:s',strtotime($cat['added_date'])),
										'invoice_no' => $cat['invoice_no'],
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'total_sale' => $defCls->money($cat['total_sale']),
										'cost' => $defCls->money($cat['total_sale_cost']),
										'profit' => $defCls->money($profit),
										'profit_percentage' => $defCls->num($profitPercentage),
										'sales_Rep' => $SalesMasterRepQuery->data($cat['sales_rep_id'],'name'),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name'),
										'cash' => $defCls->money($cat['cash_sales']),
										'card' => $defCls->money($cat['card_sales']),
										'return' => $defCls->money($cat['return_sales']),
										'gift_card' => $defCls->money($cat['gift_card_sales']),
										'loyalty' => $defCls->money($cat['loyalty_sales']),
										'credit' => $defCls->money($cat['credit_sales']),
										'cheque' => $defCls->money($cat['cheque_sales'])
									);
											
			}
			
			
			if($totalProfit){ $profitPercentage = ($totalProfit / $totalCost) * 100; }
			else{ $profitPercentage = 0; }
			
			$data['totalTotalSale'] = $defCls->money($totalTotalSale);
			$data['totalCost'] = $defCls->money($totalCost);
			$data['totalProfit'] = $defCls->money($totalProfit);
			$data['totalprofitPercentage'] = $defCls->num($profitPercentage);
			$data['totalCash'] = $defCls->money($totalCash);
			$data['totalCard'] = $defCls->money($totalCard);
			$data['totalReturn'] = $defCls->money($totalReturn);
			$data['totalGiftCard'] = $defCls->money($totalGiftCard);
			$data['totalLoyalty'] = $defCls->money($totalLoyalty);
			$data['totalCredit'] = $defCls->money($totalCredit);
			$data['totalCheque'] = $defCls->money($totalCheque);

	
			$this_required_file = _HTML.'sales/r_dailysales_view.php';
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
