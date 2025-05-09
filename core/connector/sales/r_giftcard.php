<?php

class SalesRGiftcardConnector {

    public function index() {
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Gift Card Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['view_url'] = $defCls->genURL('sales/r_giftcard/view/');
			
			$data['customer_list']	= $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/r_giftcard.php";
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
		global $CustomersMasterCustomersQuery;
		global $SalesTransactionGiftcardsQuery;
		global $SalesTransactionInvoicesQuery;
		
		
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
			
			if(isset($_REQUEST['search_idate_from'])){ $search_idate_from=$db->request('search_idate_from'); }
			else{ $search_idate_from=''; }
			
			if(isset($_REQUEST['search_idate_to'])){ $search_idate_to=$db->request('search_idate_to'); }
			else{ $search_idate_to=''; }
			
			if(isset($_REQUEST['search_rdate_from'])){ $search_rdate_from=$db->request('search_rdate_from'); }
			else{ $search_rdate_from=''; }
			
			if(isset($_REQUEST['search_rdate_to'])){ $search_rdate_to=$db->request('search_rdate_to'); }
			else{ $search_rdate_to=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($search_status == 'R'){ $search_statustxt = 'Redeemed'; }
			elseif($search_status == 'P'){ $search_statustxt = 'Pending'; }
			elseif($search_status == 'B'){ $search_statustxt = 'Balance'; }
			else{ $search_statustxt = 'All'; }
			
			$filter_heading = '';
			if($search_idate_from){ $filter_heading .= ' | From : '.$search_idate_from; }
			if($search_idate_to){ $filter_heading .= ' | To : '.$search_idate_to; }
			if($search_rdate_from){ $filter_heading .= ' | From : '.$search_rdate_from; }
			if($search_idate_to){ $filter_heading .= ' | To : '.$search_idate_to; }
			if($search_status){ $filter_heading .= ' | Status : '.$search_statustxt; }
			
			$data['title_tag'] = 'Gift Card Report | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
			$data['filter_heading'] = trim($filter_heading,',');
			$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');;
			
			/////////////
			
			$sql=" WHERE gift_card_id != 0";
			
			if($search_idate_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_idate_from)."'"; }
			if($search_idate_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_idate_to)."'"; }
			if($search_rdate_from){ $sql.=" AND DATE(added_date)>='".$dateCls->dateToDB($search_rdate_from)."'"; }
			if($search_rdate_to){ $sql.=" AND DATE(added_date)<='".$dateCls->dateToDB($search_rdate_to)."'"; }
			if($search_status == 'R'){ $sql.=" AND balance_amount<1"; }
			elseif($search_status == 'P'){ $sql.=" AND used_amount<1"; }
			elseif($search_status == 'B'){ $sql.=" AND used_amount>0 AND balance_amount>0"; }
			
			$sql.="  ORDER BY gift_card_id DESC";
			
			$getRows = $db->fetchAll("SELECT * FROM sales_gift_cards".$sql);
				
			$data['rows'] = array();
				
			$tAmount = 0;
			$tRedeemed = 0;
			$tBalance = 0;
			
			if(count($getRows))
			{
				
				foreach($getRows as $cat)
				{
					
					$tAmount += $cat['amount'];
					$tRedeemed += $cat['used_amount'];
					$tBalance += $cat['balance_amount'];
					
					$usedDetails = '';
					
					if($cat['used_amount']>0)
					{
						$getINVPRows = $db->fetchAll("SELECT * FROM sales_invoice_payments 
												
												WHERE
												
												type = 'GIFT CARD'
												AND gift_card_id = ".$cat['gift_card_id']."
						
												ORDER BY
												gift_card_id DESC
						
						");
						foreach($getINVPRows as $invp)
						{
							$usedDetails .= $SalesTransactionInvoicesQuery->data($invp['invoice_id'],'invoice_no');
							$usedDetails .= ' - ';
							$usedDetails .= $dateCls->showDate($SalesTransactionInvoicesQuery->data($invp['invoice_id'],'added_date'));
							$usedDetails .= '<br>';
						}
					}
					
					
					$data['rows'][] = array(
											'no' => $defCls->showText($cat['no']),
											'added_date' => date('d-m-Y',strtotime($cat['added_date'])),
											'expiry_date' => date('d-m-Y',strtotime($cat['expiry_date'])),
											'amount' => $defCls->money($cat['amount']),
											'used_amount' => $defCls->money($cat['used_amount']),
											'balance_amount' => $defCls->money($cat['balance_amount']),
											'usedInfo' => $usedDetails
										);
												
				}
			}
				
			$data['tAmount'] = $defCls->money($tAmount);
			$data['tRedeemed'] = $defCls->money($tRedeemed);
			$data['tBalance'] = $defCls->money($tBalance);
				
			$this_required_file = _HTML.'sales/r_giftcard_view.php';
			
			
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
