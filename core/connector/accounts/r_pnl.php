<?php

class AccountsRPnlConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'PNL | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('accounts/r_pnl/view/');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."accounts/r_pnl.php";
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
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $AccountsMasterExpencestypesQuery;
		
		
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
			else{ $search_date_from=$dateCls->todayDate('Y-m-d'); }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=$dateCls->todayDate('Y-m-d'); }
			
			$filter_heading = '';
			if($search_date_from){ $filter_heading .= ' | From : '.$search_date_from; }
			if($search_date_to){ $filter_heading .= ' | To : '.$search_date_to; }
			
			$data['title_tag'] = 'Profit &amp; Loss Statement | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql="";
			
			if($search_date_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_date_to)."'"; }
			
			///////////
			
			$sql.="  ORDER BY added_date ASC";
			
			$rowsi = $db->fetch("SELECT 
										SUM(total_sale) AS total_sale,
										SUM(total_sale_cost) AS total_sale_cost 
										FROM sales_invoices 
										
										 WHERE invoice_id != 0
										".$sql);
										
			$salesCost = $rowsi['total_sale_cost'];
			$totalSales = $rowsi['total_sale'];
			
			$data['totalSaleCost'] = $defCls->money($salesCost);
			$data['totalSales'] = $defCls->money($totalSales);
			
			///
			$rowaa = $db->fetch("SELECT 
										SUM(amount) AS amount 
										FROM accounts_adjustments 
										
										 WHERE is_other_income = 1 AND type='Credit'
										 
										".$sql);
										
										
			$otherIncome = $rowaa['amount'];
			$totalIncome = $totalSales+$otherIncome;
			
			$data['otherIncome'] = $defCls->money($otherIncome);
			
			$data['totalIncome'] = $defCls->money($rowsi['total_sale']+$rowaa['amount']);
			
			///
			
			$rowADJPlus = $db->fetch("
			SELECT 
				SUM(ai.qty) AS total_qty,
				SUM(ai.total) AS total_amount
			FROM 
				inventory_adjustment_note_items ai
			JOIN 
				inventory_adjustment_notes an ON ai.adjustment_note_id = an.adjustment_note_id
			WHERE 
				ai.type = '+' 
				AND DATE(an.added_date) BETWEEN '".$dateCls->dateToDB($search_date_from)."' AND '".$dateCls->dateToDB($search_date_to)."'
			");
			
			$stockADJPlus = $rowADJPlus['total_amount'];
			
			$data['stockAdjustPlus'] = $defCls->money($stockADJPlus);
			
			///
			
			$rowADJMinus = $db->fetch("
			SELECT 
				SUM(ai.qty) AS total_qty,
				SUM(ai.total) AS total_amount
			FROM 
				inventory_adjustment_note_items ai
			JOIN 
				inventory_adjustment_notes an ON ai.adjustment_note_id = an.adjustment_note_id
			WHERE 
				ai.type = '-' 
				AND DATE(an.added_date) BETWEEN '".$dateCls->dateToDB($search_date_from)."' AND '".$dateCls->dateToDB($search_date_to)."'
			");
			
			
			
			$stockADJMinus = $rowADJMinus['total_amount'];
			
			$data['stockAdjustMinus'] = $defCls->money($stockADJMinus);
			
			
			$totalInventory = ($salesCost+$stockADJPlus)-$stockADJMinus;
			
			$data['totalInventory'] = $defCls->money($totalInventory);
			
			$grossProfit = ($totalSales)-$totalInventory;
			
			$data['grossProfit'] = $defCls->money($grossProfit);
			
			
			
			$expencestypes = $AccountsMasterExpencestypesQuery->gets(" ORDER BY name ASC");
			
			$totalExpenses = 0;
			
			$data['expencestypes'] = array();
			
			foreach($expencestypes as $cat)
			{
				
				$rowExpenses = $db->fetch("SELECT 
												SUM(amount) AS amount 
												
												FROM accounts_expences 
												
												 WHERE expences_type_id = '".$cat['expences_type_id']."'
												 
												".$sql);
				
				$data['expencestypes'][] = array(
										'expences_type_id' => $cat['expences_type_id'],
										'name' => $defCls->showText($cat['name']),
										'amount' => $defCls->money($rowExpenses['amount'])
											);
											
				$totalExpenses+=$rowExpenses['amount'];
			}
			
			
			$data['totalExpenses'] = $defCls->money($totalExpenses);
			
			$data['netProfit'] = $defCls->money($grossProfit-$totalExpenses);
			
			
			
				/*
			$salesCost = $rowsi['total_sale_cost'];
			$totalSales = $rowsi['total_sale'];
			$otherIncome = $rowaa['amount'];
			$totalIncome = $totalSales+$otherIncome;
			
			$stockADJPlus = $rowADJPlus['total_amount'];
			
			$stockADJMinus = $rowADJMinus['total_amount'];

			$totalInventory
			*/
	
			$this_required_file = _HTML.'accounts/r_pnl_view.php';
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
