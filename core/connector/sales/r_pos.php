<?php

class SalesRPosConnector {

    public function index() {
		
		
		global $db;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionInvoicesQuery;
		global $SalesScreenQuery;
		global $AccountsMasterAccountsQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'POS Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['title_tag'] = 'Daily Sales Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
			
			
			$shiftInfo = $SalesScreenQuery->getShift($userInfo['user_id']);
			$shiftStart = $shiftInfo['start_on'];
			$shiftEnd = $shiftInfo['end_on'];
			
			$cashierPointInfo = $SystemMasterCashierpointsQuery->get($shiftInfo['cashier_point_id']);
			
			$rows = $db->fetch("SELECT  
				SUM(si.qty) AS total_qty,
				SUM((si.master_price - si.unit_price) * si.qty) AS total_item_discount,
			
				COUNT(DISTINCT inv.total_sale) AS total_invoices,
				SUM(inv.total_sale) AS total_sale, 
				SUM(inv.discount_amount) AS total_discount_amount, 
				SUM(inv.cash_sales) AS total_cash_sales, 
				SUM(inv.card_sales) AS total_card_sales, 
				SUM(inv.gift_card_sales) AS total_gift_card_sales,
				SUM(inv.return_sales) AS total_return_sales,
				SUM(inv.loyalty_sales) AS total_loyalty_sales, 
				SUM(inv.credit_sales) AS total_credit_sales,  
				SUM(inv.cheque_sales) AS total_cheque_sales
			
			FROM sales_invoice_items si
			JOIN sales_invoices inv ON si.invoice_id = inv.invoice_id
			");
			
			
			
			$rowCashOut = $db->fetch("SELECT SUM(amount) AS total_cashout FROM accounts_transfers WHERE shift_id = '".$shiftInfo['shift_id']."'");
			
			$data['itemDiscount'] = $defCls->money($rows['total_item_discount']);
			$data['totalSalesDiscount'] = $defCls->money($rows['total_discount_amount']);
			$data['totalDiscount'] = $defCls->money($rows['total_item_discount']+$rows['total_discount_amount']);
			$data['totalSales'] = $defCls->money($rows['total_sale']+($rows['total_item_discount']+$rows['total_discount_amount']));
			$data['totalGrossSales'] = $defCls->money($rows['total_sale']);
			
			$data['totalCashSales'] = $defCls->money($rows['total_cash_sales']);
			$data['totalCardSales'] = $defCls->money($rows['total_card_sales']);
			$data['totalReturnSales'] = $defCls->money($rows['total_return_sales']);
			$data['totalGiftCardSales'] = $defCls->money($rows['total_gift_card_sales']);
			$data['totalLoyaltySales'] = $defCls->money($rows['total_loyalty_sales']);
			$data['totalCreditSales'] = $defCls->money($rows['total_credit_sales']);
			$data['totalChequeSales'] = $defCls->money($rows['total_cheque_sales']);
			
			$data['totalInvoices'] = $defCls->money($rows['total_invoices']);
			$data['totalQty'] = $defCls->money($rows['total_qty']);
			
			$data['cashBalance'] = $defCls->money($AccountsMasterAccountsQuery->data($cashierPointInfo['cash_account_id'],'closing_balance'));
			$data['totalCashOut'] = $defCls->money($rowCashOut['total_cashout']);

			
			
			require_once _HTML."sales/r_pos.php";
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
		global $SalesTransactionsReturnQuery;
		
		
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
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			if($search_customer){ $filter_heading .= ' | Customer : '.$CustomersMasterCustomersQuery->data($search_customer,'name'); }
			if($search_location){ $filter_heading .= ' | Location : '.$SystemMasterLocationsQuery->data($search_location,'name'); }
			if($search_sales_rep){ $filter_heading .= ' | Sales Rep : '.$SalesMasterRepQuery->data($search_sales_rep,'name'); }
			if($search_user){ $filter_heading .= ' | User : '.$SystemMasterUsersQuery->data($search_user,'name'); }
			
			$data['title_tag'] = 'Daily Sales Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE status=1";
			
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
			
			
			///Returns
			/////////////
			
			$sql=" WHERE sales_return_id!=0";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer){ $sql.=" AND customer_id='".$search_customer."'"; }
			if($search_location){ $sql.=" AND location_id='".$search_location."'"; }
			if($search_user){ $sql.=" AND user_id='".$search_user."'"; }
			
			///////////
			
			$sql.="  ORDER BY sales_return_id DESC";
			
			$getSalesReturn = $SalesTransactionsReturnQuery->gets($sql);
			
			$data['returnRows'] = array();
			
			$totalReturnTotal = 0;
			$totalReturnCost = 0;
			$totalReturnProfit = 0;
			$totalReturnQty = 0;
			
			foreach($getSalesReturn as $cat)
			{
				$profit = $cat['total_value']-$cat['total_cost'];
				$profitPercentage = ($profit / $cat['total_cost']) * 100;
			
				$totalReturnTotal += $cat['total_value'];
				$totalReturnCost += $cat['total_cost'];
				$totalReturnProfit += $profit;
				$totalReturnQty += $cat['no_of_qty'];
				
				$data['returnRows'][] = array(
										'return_id' => $cat['sales_return_id'],
										'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
										'return_no' => $defCls->docNo('SRN',$cat['sales_return_id']),
										'invoice_no' => $defCls->showText($cat['invoice_no']),
										'customer' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'total_value' => $defCls->money($cat['total_value']),
										'cost' => $defCls->money($cat['total_cost']),
										'profit' => $defCls->money($profit),
										'profit_percentage' => $defCls->num($profitPercentage),
										'location' => $SystemMasterLocationsQuery->data($cat['location_id'],'name'),
										'user' => $SystemMasterUsersQuery->data($cat['user_id'],'name')
									);
											
			}
			
			if($totalReturnProfit){ $profitReturnPercentage = ($totalReturnProfit / $totalReturnCost) * 100; }
			else{ $profitReturnPercentage = 0; }
			
			$data['totalReturnTotal'] = $defCls->money($totalReturnTotal);
			$data['totalReturnCost'] = $defCls->money($totalReturnCost);
			$data['totalReturnProfit'] = $defCls->money($totalReturnProfit);
			$data['totalReturnQty'] = $defCls->num($totalReturnQty);
			$data['profitReturnPercentage'] = $defCls->money($profitReturnPercentage);
			
			
			$totalAfterReturn = $totalTotalSale-$totalReturnTotal;
			$totalAfterReturnCost = $totalCost-$totalReturnCost;
			$totalAfterReturnProfit = $totalProfit-$totalReturnProfit;
			
			if($totalReturnProfit){ $profitAfterReturnPercentage = ($totalAfterReturnProfit / $totalAfterReturnCost) * 100; }
			else{ $profitAfterReturnPercentage = 0; }
			
			$data['totalAfterReturn'] = $defCls->money($totalAfterReturn);
			$data['totalReturnCost'] = $defCls->money($totalReturnCost);
			$data['totalReturnProfit'] = $defCls->money($totalReturnProfit);
			$data['profitAfterReturnPercentage'] = $defCls->num($profitAfterReturnPercentage);
			
			

	
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
