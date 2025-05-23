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
			
			$data['titleTag'] 	= 'Gift Cards | '.$defCls->master('companyName');
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
			
			if(isset($_REQUEST['search_no'])){ $search_no=$db->request('search_no'); }
			else{ $search_no=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
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
										'balance_amount' => $defCls->num($gc['balance_amount']),
										'updateURL' => $defCls->genURL('sales/transaction_giftcards/edit/'.$gc['gift_card_id']),
										'deleteURL' => $defCls->genURL('sales/transaction_giftcards/delete/'.$gc['gift_card_id'])
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
				
			if(isset($_REQUEST['no'])){ $data['no'] = $db->request('no');}
			else{ $data['no'] = ''; }
				
			if(isset($_REQUEST['expiry_date'])){ $data['expiry_date'] = $db->request('expiry_date');}
			else{ $data['expiry_date'] = ''; }
			
			if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount');}
			else{ $data['amount'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				$masterGiftCardEnabled = $defCls->master('gift_cards');
				
				$countGCsByNO = $SalesTransactionGiftcardsQuery->gets("WHERE no='".$data['no']."'");
				$countGCsByNO = count($countGCsByNO);
				
				if(!$masterGiftCardEnabled){ $error_msg[]="Your account doesn't have permission to use the gift card feature"; $error_no++; }
				
				if(strlen($data['no'])<6){ $error_msg[]="No must be minimum 6 letters"; $error_no++; }
				if($countGCsByNO){ $error_msg[]="The no already exists"; $error_no++; }
				if($data['amount']<1){  $error_msg[]="You must enter gift voucher"; $error_no++; }
				
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
	
	

    public function edit() {
		
		
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getGCInfo = $SalesTransactionGiftcardsQuery->get($id);
			
			if($getGCInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."sales/transaction_giftcards/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['gift_card_id'] = $getGCInfo['gift_card_id'];
				
				if(isset($_REQUEST['no'])){ $data['no'] = $db->request('no');}
				else{ $data['no'] = $getGCInfo['no']; }
					
				if(isset($_REQUEST['expiry_date'])){ $data['expiry_date'] = $db->request('expiry_date');}
				else{ $data['expiry_date'] = $dateCls->showDate($getGCInfo['expiry_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount');}
				else{ $data['amount'] = $getGCInfo['amount']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['no'])<6){ $error_msg[]="No must be minimum 6 letters"; $error_no++; }
					
					if($db->request('no')!==$getGCInfo['no'])
					{
						$countGCsByNO = $SalesTransactionGiftcardsQuery->gets("WHERE no='".$data['no']."'");
						$countGCsByNO = count($countGCsByNO);
						
						if($countGCsByNO){ $error_msg[]="The no already exists"; $error_no++; }
					}
					if($data['amount']<1){  $error_msg[]="You must enter gift voucher"; $error_no++; }
					if($getGCInfo['used_amount']>0){  $error_msg[]="Gift voucher already used!"; $error_no++; }
					
					if(!$error_no)
					{
						
						$SalesTransactionGiftcardsQuery->edit($data);
						$firewallCls->addLog("Gift Voucher Updated: ".$data['no']);
						
						$json['success']=true;
						$json['success_msg']="Sucessfully Updated";
						
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
				$error_msg[]="Invalid gift voucher Id"; $error_no++;
					
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
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	
	
	
	
	

    public function delete() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $SalesTransactionGiftcardsQuery->get($id);
			
			if($getInfo)
			{
				$no = $getInfo['no'];
				
				$deleteValue = $SalesTransactionGiftcardsQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("Gift Card Deleted: ".$no);
				
					$json['success']=true;
					$json['success_msg']="Sucessfully Updated";
				
				}
				elseif(is_array($deleteValue))
				{
					foreach($deleteValue as $v)
					{
						$error_msg[]=$v; $error_no++;
					}
					
				}
				else
				{
					$error_msg[]="An error occurred while attempting to delete the gift card!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid gift card Id"; $error_no++;
				
				
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
			header("location:"._SERVER);
		}
		
	}
}
