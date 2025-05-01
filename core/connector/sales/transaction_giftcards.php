<?php

class SalesTransactionGiftcardsConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("sales/transaction_giftcards/create");
			$data['load_table_url'] = $defCls->genURL('sales/transaction_giftcards/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."sales/transaction_giftcards.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $dateCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if($db->request('search_no')){ $search_no=$db->request('search_no'); }
			else{ $search_no=''; }
			
			if($db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE gift_card_id!=0";
			
			if($search_no){ $sql.=" AND no LIKE '%$search_no%'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SalesTransactionGiftcardsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY gift_card_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$gcs = $SalesTransactionGiftcardsQuery->gets($sql);
			
			$data['gc'] = array();
			
			foreach($gcs as $gc)
			{
				$data['gc'][] = array(
										'gc_id' => $gc['gift_card_id'],
										'no' => $gc['no'],
										'expiry_date' => $dateCls->showDate($gc['expiry_date']),
										'amount' => $defCls->num($gc['amount']),
										'used_amount' => $defCls->num($gc['used_amount']),
										'balance_amount' => $defCls->num($gc['balance_amount'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($gcs).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'sales/transaction_giftcards_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}

    public function create() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."sales/transaction_giftcards/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if($db->request('no')){ $data['no'] = $db->request('no');}
			else{ $data['no'] = ''; }
				
			if($db->request('expiry_date')){ $data['expiry_date'] = $db->request('expiry_date');}
			else{ $data['expiry_date'] = ''; }
			
			if($db->request('amount')){ $data['amount'] = $db->request('amount');}
			else{ $data['amount'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countGCsByNO = $SalesTransactionGiftcardsQuery->gets("WHERE no='".$data['no']."'");
				$countGCsByNO = count($countGCsByNO);
				
				if(strlen($data['no'])<6){ $error_msg[]="No must be minimum 6 letters"; $error_no++; }
				if($countGCsByNO){ $error_msg[]="The no already exists"; $error_no++; }
				if($data['amount']<1){  $error_msg[]="You must enter gift boucher"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SalesTransactionGiftcardsQuery->create($data);

					$firewallCls->addLog("Gift Card Created: ".$data['no']);
					
					$json['success']=true;
					$json['success_msg']="Sucessfully Created";
					
				}
				
				if($error_no)
				{
					
					$error_msg_list='';
					foreach($error_msg as $e)
					{
						if($e)
						{
							$error_msg_list.='<li>'.$e.'</li>';
						}
					}
					$json['error']=true;
					$json['error_msg']=$error_msg_list;
				}
				echo json_encode($json);
				
			}
			else
			{
	
				$this_required_file = _HTML.'sales/transaction_giftcards_form.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}			
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
}
