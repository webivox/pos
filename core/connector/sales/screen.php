<?php

class SalesScreenConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $CustomersMasterCustomersQuery;
		global $SalesMasterRepQuery;
		global $InventoryMasterCategoryQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			$data = [];
			
			$data['titleTag'] 	= 'Sales | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['customer_create_url'] 	= $defCls->genURL("customers/master_customers/create");
			$data['sales_return_url'] 	= $defCls->genURL("sales/transaction_return/create");
			$data['cashout_url'] 	= $defCls->genURL("sales/screen/cashout");
			$data['report_url'] 	= $defCls->genURL("sales/r_pos");
			$data['load_table_url'] = $defCls->genURL('sales/master_rep/load');
			
			if($sessionCls->load('lastPrintedInvoiceNo'))
			{
				$data['reprint_url'] = 'href="'.$defCls->genURL("sales/screen/print/".$sessionCls->load('lastPrintedInvoiceNo')."/").'" target="_blank"';
			}
			else{ $data['reprint_url'] = 'id="noreprintinvfound"'; }
			
			$data['shift_status'] 	= count($SalesScreenQuery->getShift($userId));
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['cashierPoints'] = $SystemMasterCashierpointsQuery->gets("WHERE location_id='".$userInfo['location_id']."' ORDER BY name ASC");
			
			$shiftInfo = $SalesScreenQuery->getShift($userId);
			
			if($shiftInfo)
			{
				
			
				if($sessionCls->load('invoiceId'))
				{
					
					$data['invoiceId'] = $sessionCls->load('invoiceId');
					$salesInfo = $SalesScreenQuery->get($data['invoiceId']);
					
					if($salesInfo)
					{
						
						$salesInfo = $SalesScreenQuery->get($data['invoiceId']);
						$cashierPointInfo = $SystemMasterCashierpointsQuery->get($salesInfo['cashier_point_id']);
						
						$data['loggedUserName'] = $defCls->showText($userInfo['name']);
						$data['logOut'] = $defCls->genURL('secure/signout');
						
						$data['customer_name'] = $defCls->showText($CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'name').' ('.$CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'phone_number').')');
						
						$data['salesRepDD'] = $SalesMasterRepQuery->gets("ORDER BY name ASC");
						$data['sales_rep_id'] = $salesInfo['sales_rep_id'];
						$data['pendingINVDD'] = $SalesScreenQuery->getsSql("WHERE user_id='".$userId."' AND cashier_point_id='".$shiftInfo['cashier_point_id']."' ORDER BY invoice_id ASC");
						
						
						
						$data['cartItems'] = [];
						
						$cartItems = $SalesScreenQuery->getItems($data['invoiceId']);
						
						foreach($cartItems as $ci)
						{
							$data['cartItems'][] = $ci['invoice_item_id'];
						}
						
						$data['customerOutstanding'] = $defCls->money($CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'closing_balance'));
						$data['loyaltyPoints'] = $defCls->money($CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'loyalty_points'));
						
						$data['total_sale'] = $defCls->money($salesInfo['total_sale']+$salesInfo['discount_amount']);
						$data['discount_type'] = $salesInfo['discount_type'];
						$data['discount_value'] = $defCls->num($salesInfo['discount_value']);
						$data['discount_amount'] = $defCls->money($salesInfo['discount_amount']);
						$data['total_paid'] = $defCls->money($salesInfo['total_paid']);
						$data['net_total'] = $defCls->money($salesInfo['total_sale']);
						
						
						$data['comments'] = $defCls->showText($salesInfo['comments']);
						
						
						
						$data['categoryList'] = [];
						
						$categories = $InventoryMasterCategoryQuery->gets("WHERE status=1 AND parent_category_id=0 ORDER BY name ASC");
						
						foreach($categories as $c)
						{
							$data['categoryList'][] = array(
							
													'catId' => $c['category_id'],
													'name' => $defCls->showText($c['name']),
													'url' => $defCls->genURL('sales/screen/loadinventorycategory/'.$c['category_id'].'/')
							
													);
						}
						
						
						$data['cardPayments'] = [];
						
						if($cashierPointInfo['card_account_1_name'] && $cashierPointInfo['card_account_1_id'])
						{
							$data['cardPayments'][] = array(
							
														'accountId' => $cashierPointInfo['card_account_1_id'],
														'name' => $defCls->showText($cashierPointInfo['card_account_1_name'])
							
														);
						}
						
						if($cashierPointInfo['card_account_2_name'] && $cashierPointInfo['card_account_2_id'])
						{
							$data['cardPayments'][] = array(
							
														'accountId' => $cashierPointInfo['card_account_2_id'],
														'name' => $defCls->showText($cashierPointInfo['card_account_2_name'])
							
														);
						}
						
						if($cashierPointInfo['card_account_3_name'] && $cashierPointInfo['card_account_3_id'])
						{
							$data['cardPayments'][] = array(
							
														'accountId' => $cashierPointInfo['card_account_3_id'],
														'name' => $defCls->showText($cashierPointInfo['card_account_3_name'])
							
														);
						}
						
						if($cashierPointInfo['card_account_4_name'] && $cashierPointInfo['card_account_4_id'])
						{
							$data['cardPayments'][] = array(
							
														'accountId' => $cashierPointInfo['card_account_4_id'],
														'name' => $defCls->showText($cashierPointInfo['card_account_4_name'])
							
														);
						}
						
						if($cashierPointInfo['card_account_5_name'] && $cashierPointInfo['card_account_5_id'])
						{
							$data['cardPayments'][] = array(
							
														'accountId' => $cashierPointInfo['card_account_5_id'],
														'name' => $defCls->showText($cashierPointInfo['card_account_5_name'])
							
														);
						}
						
						$data['cartPayments'] = [];
						
						$cartPayments = $SalesScreenQuery->getPayments($data['invoiceId']);
						
						foreach($cartPayments as $cp)
						{
							$data['cartPayments'][] = array(
							
														'paymentId' => $cp['invoice_payment_id'],
														'type' => $cp['type'],
														'amount' => $defCls->num($cp['amount']+$cp['amount_balance'])
							
														);
							
						}
						
						
						
						require_once _HTML."sales/screen.php";
					}
					else
					{
						$getInvoiceByUser = $SalesScreenQuery->getSql("WHERE user_id='".$userId."' AND cashier_point_id='".$shiftInfo['cashier_point_id']."' ORDER BY invoice_id ASC");
					
						if($getInvoiceByUser)
						{
							
							$sessionCls->set('invoiceId',$getInvoiceByUser['invoice_id']);
							
							
							header("location:"._SERVER."sales/screen");
						
						}
						else
						{
							
							$createdInvId = $SalesScreenQuery->create('');
							$sessionCls->set('invoiceId',$createdInvId);
								
							header("location:"._SERVER."sales/screen");
							
						}
					}
				}
				else
				{
					
					$getInvoiceByUser = $SalesScreenQuery->getSql("WHERE user_id='".$userId."' AND cashier_point_id='".$shiftInfo['cashier_point_id']."' ORDER BY invoice_id ASC");
					
					if($getInvoiceByUser)
					{
						
						$sessionCls->set('invoiceId',$getInvoiceByUser['invoice_id']);
						
						
						header("location:"._SERVER."sales/screen");
					
					}
					else
					{
						
						$createdInvId = $SalesScreenQuery->create('');
						$sessionCls->set('invoiceId',$createdInvId);
							
						header("location:"._SERVER."sales/screen");
						
					}
				}
			
			}
			else
			{
				
				require_once _HTML."sales/screen.php";
				
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}

    public function shiftStart() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			$cashierPointId = $id;
			
			
			if($SalesScreenQuery->getShift($userId))
			{
				
				$error_msg[]="Already shift started!"; $error_no++;	
				
			}
			elseif($SystemMasterCashierpointsQuery->has($cashierPointId))
			{
				if($SalesScreenQuery->getShift($userId))
				{
					
					$error_msg[]="Already shift started!"; $error_no++;
					
				}
				else
				{
					
					if($SalesScreenQuery->shiftStart($userId , $cashierPointId))
					{
						$firewallCls->addLog("Shift Started");
						
						$json['success']=true;
						$json['success_msg']="Shift successfully started.";
						$json['reload']=_SERVER."sales/screen";
						
					}
					else
					{
						$error_msg[]="An error was found during shift start!"; $error_no++;
					}
				}
			}
			else
			{
				
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
				
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

    public function shiftEnd() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			$cashierPointId = $id;
			
			
			if($SalesScreenQuery->getShift($userId))
			{
				
				if($SalesScreenQuery->endShift($userId))
				{
					$firewallCls->addLog("Shift Ended");
					
					$json['success']=true;
					$json['success_msg']="Shift successfully started.";
					$json['reload']=_SERVER."sales/screen";
					
				}
				else
				{
					$error_msg[]="An error was found during shift end!"; $error_no++;
				}
			
			}
			else
			{
				$error_msg[]="Shift not started started!"; $error_no++;
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

    public function suspend() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				
				$createdInvId = $SalesScreenQuery->create('');
				$sessionCls->set('invoiceId',$createdInvId);
					
				$json['success']=true;
				$json['success_msg']="Please wait.";
				$json['reload']=true;
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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

    public function recall() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				
				
				$sessionCls->set('invoiceId',$id);
					
				$json['success']=true;
				$json['reload']=true;
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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

    public function additem() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId)
				{
					$addedItemId = $SalesScreenQuery->addItem($invoiceId,$id);
					
					$json['success']=true;
					$json['invoiceItemId']=$addedItemId;
					$json['html']=$SalesScreenQuery->itemHTML($addedItemId);
					$SalesScreenQuery->runTotal($invoiceId);
					
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	

    public function addcustomer() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId && $id)
				{
					$addedItemId = $SalesScreenQuery->addCustomer($invoiceId,$id);
					
					$json['success']=true;
					$json['customername']=$defCls->showText($CustomersMasterCustomersQuery->data($id,'name'). ' ('.$CustomersMasterCustomersQuery->data($id,'phone_number').')');
					
					
					$json['loyaltyPoints'] = $defCls->money($CustomersMasterCustomersQuery->data($id, 'loyalty_points'));
					$json['outstanding'] = $defCls->money($CustomersMasterCustomersQuery->data($id, 'closing_balance'));
					
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	

    public function addsalesrep() {
			
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId && $id)
				{
					$addedItemId = $SalesScreenQuery->addSalesRep($invoiceId,$id);
					
					$json['success']=true;
					
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	

    public function updatePrice() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
				$itemInfo = $InventoryMasterItemsQuery->get($salesItemInfo['item_id']);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$val = $db->request('val');
				
				if($invoiceId && $id && $val)
				{
					
					if($itemInfo['minimum_selling_price']>$val)
					{
						$json['oldValue']=$defCls->num($salesItemInfo['price']);
						$error_msg[]="Minimum selling price reached!"; $error_no++;
					}
					else
					{
						$addedItemId = $SalesScreenQuery->updateItemPrice($invoiceId,$id,$val);
						$SalesScreenQuery->runTotal($invoiceId);
						
						$json['success']=true;
					}
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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


	
	

    public function updateDiscount() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
				$itemInfo = $InventoryMasterItemsQuery->get($salesItemInfo['item_id']);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$val = $db->request('val');
				
				if($invoiceId && $id && $val)
				{
					$price = $salesItemInfo['price'];
					$discount = $price*$val/100;
					$unit_price = $price-$discount;
					
					if($itemInfo['minimum_selling_price']>$unit_price)
					{
						$json['oldValue']=$defCls->num($salesItemInfo['discount']);
						$error_msg[]="Minimum selling price reached!"; $error_no++;
					}
					else
					{
						$addedItemId = $SalesScreenQuery->updateItemDiscount($invoiceId,$id,$val);
						$SalesScreenQuery->runTotal($invoiceId);
						
						$json['success']=true;
					}
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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


	
	

    public function updateQty() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
				$itemInfo = $InventoryMasterItemsQuery->get($salesItemInfo['item_id']);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$val = $db->request('val');
				
				if($invoiceId && $id && $val)
				{
					
					if($itemInfo['minimum_qty']>$val)
					{
						$json['oldValue']=$defCls->num($salesItemInfo['qty']);
						$error_msg[]="Less then minimum qty!"; $error_no++;
					}
					else
					{
						$addedItemId = $SalesScreenQuery->updateItemQTY($invoiceId,$id,$val);
						$SalesScreenQuery->runTotal($invoiceId);
						
						$json['success']=true;
					}
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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


	
	

    public function removeItem() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId && $id)
				{
					
					$SalesScreenQuery->removeItem($invoiceId,$id);
					$SalesScreenQuery->runTotal($invoiceId);
						
					$json['success']=true;
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	


	
	

    public function updateSalesDiscount() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $InventoryMasterItemsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$type = $db->request('type');
				$val = $db->request('val');
				
				if($type && $invoiceId && $val>=0)
				{
					$addedItemId = $SalesScreenQuery->updateSalesDiscount($type,$invoiceId,$val);
					$SalesScreenQuery->runTotal($invoiceId);
					
					$json['success']=true;
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	


	
	

    public function comments() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$val = $db->request('val');
				
				if($invoiceId)
				{
					$SalesScreenQuery->updateComment($invoiceId,$val);
					$SalesScreenQuery->runTotal($invoiceId);
					
					$json['success']=true;
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	
	

    public function loadinventorycategory() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesScreenQuery;
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				
				$categories = $InventoryMasterCategoryQuery->gets("WHERE status=1 AND parent_category_id='".$id."' ORDER BY name ASC");
				
				$data['categoryList'] = [];
				
				foreach($categories as $c)
				{
					
					$data['categoryList'][] = array(
					
											'catId' => $c['category_id'],
											'name' => $defCls->showText($c['name']),
											'url' => $defCls->genURL('sales/screen/loadinventorycategory/'.$c['category_id'].'/')
										);
				}
					
				
			}
			else
			{
		
				echo "Invalid Cashier Point!";	
			
			}
			
			require_once _HTML."sales/screen_quick_menu.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	

    public function loadinventoryitems() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesScreenQuery;
		global $InventoryMasterItemsQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				if($sessionCls->load('invoiceId'))
				{
					$invoiceId = $sessionCls->load('invoiceId');
					$salesInfo = $SalesScreenQuery->get($invoiceId);
					$customerGroupId = $CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'customer_group_id');
					
					
				
					$items = $InventoryMasterItemsQuery->gets("WHERE status=1 AND category_id='".$id."' ORDER BY name ASC");
					
					$data['itemList'] = [];
					
					foreach($items as $c)
					{
						$price =  $InventoryMasterItemsQuery->getCustomerGroupPrice($customerGroupId,$c['item_id']);
						
						$data['itemList'][] = array(
						
												'itemId' => $c['item_id'],
												'name' => $defCls->showText($c['name']),
												'image' => $defCls->showText($c['name']),
												'price' => $defCls->money($price),
												'url' => $defCls->genURL('sales/screen/loadinventorycategory/'.$c['category_id'].'/')
											);
					}
				}
				else
				{
			
					echo "Invalid Invoice Id!";	
				
				}	
				
			}
			else
			{
		
				echo "Invalid Cashier Point!";	
			
			}
			
			require_once _HTML."sales/screen_quick_menu_items.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	
	

    public function loadreturnBalance() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesScreenQuery;
		global $SalesTransactionsReturnQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				if($sessionCls->load('invoiceId'))
				{
					$salesReturnNo = str_replace('SRN-','',$id);
					$salesReturnNo = ltrim($salesReturnNo, '0');
					
					$salesReturnInfo = $SalesTransactionsReturnQuery->get($salesReturnNo);
					
					if($salesReturnInfo)
					{
					
						$json['success']=true;
						$json['balance_value']=$defCls->num($salesReturnInfo['balance_value']);
					
					}
					else
					{
				
						$error_msg[]="Invalid return no!!"; $error_no++;	
					
					}
				}
				else
				{
			
					$error_msg[]="Invalid return no!"; $error_no++;	
				
				}	
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	
	
	

    public function loadGiftCardBalance() {
			
		global $db;
		global $id;
		global $defCls;
		global $dateCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesScreenQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				if($sessionCls->load('invoiceId'))
				{
						
					$currentDate = $dateCls->todayDate('Y-m-d');
					$validateNoInfo = $SalesTransactionGiftcardsQuery->validate($id,$currentDate);
						
					if($validateNoInfo)
					{
						$json['success']=true;
						$json['balance_value']=$defCls->num($validateNoInfo['balance_amount']);
						
						
						
					}
					else
					{
				
						$error_msg[]="Invalid gift card no!!"; $error_no++;	
					
					}
				}
				else
				{
			
					$error_msg[]="Invalid gift card no!"; $error_no++;	
				
				}	
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	


	
	

    public function addPayment() {
			
		global $db;
		global $id;
		global $defCls;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $dateCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesTransactionGiftcardsQuery;
		global $SalesTransactionsReturnQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				$salesItemInfo = $SalesScreenQuery->getItem($id);
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				$type = $db->request('type');
				$val = $db->request('val');
				$cardOption = $db->request('cardOption');
				$dueDate = $db->request('dueDate');
				$chequeBank = $db->request('chequeBank');
				$chequeDate = $db->request('chequeDate');
				$chequeNo = $db->request('chequeNo');
				$returnNo = $db->request('returnNo');
				$giftCardNo = $db->request('giftCardNo');
				
				if($invoiceId)
				{
					$invoiceInfo = $SalesScreenQuery->get($invoiceId);
					$balanceDueAmt = $invoiceInfo['total_sale']-$invoiceInfo['total_paid'];
					
					if($val<1)
					{
						
						$error_msg[]="Amount Not Found!"; $error_no++;	
						
					}
					elseif($type=='CASH')
					{
						if($balanceDueAmt<$val)
						{
							$getforInv = $balanceDueAmt;
							$getforInvBl = $val-$balanceDueAmt;
						}
						else{ $getforInv = $val; $getforInvBl = 0; }
						
						
					
						$data['paymentDetails'] = array(
						
															'invoiceId' => $invoiceId,
															'type' => 'CASH',
															'amount' => $getforInv,
															'cardoption' => '',
															'dueDate' => '',
															'chequeBank' => '',
															'chequeDate' => '',
															'chequeNo' => '',
															'returnNo' => '',
															'giftCardNo' => '',
															'cashBalance' => $getforInvBl
						
														);
						$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
						
						if($addPaymentLastid)
						{
						
							$SalesScreenQuery->runTotal($invoiceId);
						
							$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
						
							$json['balance']=$getforInvBl;
							$json['success']=true;
						
						}
					}
					elseif($type=='CARD')
					{
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!"; $error_no++;	
						}
						elseif($cardOption)
						{
						
							$data['paymentDetails'] = array(
							
															'invoiceId' => $invoiceId,
															'type' => 'CARD',
															'amount' => $val,
															'cardoption' => $cardOption,
															'dueDate' => '',
															'chequeBank' => '',
															'chequeDate' => '',
															'chequeNo' => '',
															'returnNo' => '',
															'giftCardNo' => '',
															'cashBalance' => 0
							
															);
							$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
							
							if($addPaymentLastid)
							{
							
								$SalesScreenQuery->runTotal($invoiceId);
							
								$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
							
								$json['success']=true;
							
							}
							
						}
						else
						{
							$error_msg[]="Card Option Not Found!"; $error_no++;	
							
						}
					}
					elseif($type=='CREDIT')
					{
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!".$balanceDueAmt; $error_no++;	
						}
						else if($dueDate)
						{
							$currentDate = $dateCls->todayDate('Y-m-d');
							$dueDate = $dateCls->dateToDB($dueDate);
							
							if(strtotime($currentDate)<=strtotime($dueDate))
							{
						
								$data['paymentDetails'] = array(
								
																'invoiceId' => $invoiceId,
																'type' => 'CREDIT',
																'amount' => $val,
																'cardoption' => '',
																'dueDate' => $dueDate,
																'chequeBank' => '',
																'chequeDate' => '',
																'chequeNo' => '',
																'returnNo' => '',
																'giftCardNo' => '',
																'cashBalance' => 0
								
																);
								$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
								
								if($addPaymentLastid)
								{
								
									$SalesScreenQuery->runTotal($invoiceId);
								
									$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
								
									$json['success']=true;
								
								}
							}
							else
							{
								$error_msg[]="Invalid Due Date Not Found!"; $error_no++;	
								
							}
						}
						else
						{
							$error_msg[]="Due Date Not Found!"; $error_no++;	
							
						}
					}
					elseif($type=='CHEQUE')
					{
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!"; $error_no++;	
						}
						else if($chequeBank && $chequeDate && $chequeNo)
						{
							
							$chequeDate = $dateCls->dateToDB($chequeDate);
						
							$data['paymentDetails'] = array(
							
															'invoiceId' => $invoiceId,
															'type' => 'CHEQUE',
															'amount' => $val,
															'cardoption' => '',
															'dueDate' => '',
															'chequeBank' => $chequeBank,
															'chequeDate' => $chequeDate,
															'chequeNo' => $chequeNo,
															'returnNo' => '',
															'giftCardNo' => '',
															'cashBalance' => 0
							
															);
							$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
							
							if($addPaymentLastid)
							{
							
								$SalesScreenQuery->runTotal($invoiceId);
							
								$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
							
								$json['success']=true;
							
							}
							
						}
						else
						{
							$error_msg[]="Cheque Details Not Found!"; $error_no++;	
							
						}
					}
					elseif($type=='RETURN')
					{
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!".$balanceDueAmt; $error_no++;	
						}
						else if($returnNo)
						{
							
							$salesReturnNo = str_replace('SRN-','',$returnNo);
							$salesReturnNo = ltrim($salesReturnNo, '0');
							
							$salesReturnInfo = $SalesTransactionsReturnQuery->get($salesReturnNo);
							
							if($salesReturnInfo)
							{
								if($salesReturnInfo['balance_value']>=$val)
								{
									$chequeDate = $dateCls->dateToDB($chequeDate);
								
									$data['paymentDetails'] = array(
									
																	'invoiceId' => $invoiceId,
																	'type' => 'RETURN',
																	'amount' => $val,
																	'cardoption' => '',
																	'dueDate' => '',
																	'chequeBank' => '',
																	'chequeDate' => '',
																	'chequeNo' => '',
																	'returnNo' => $salesReturnNo,
																	'giftCardNo' => '',
																	'cashBalance' => 0
									
																	);
									$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
									
									if($addPaymentLastid)
									{
									
										$SalesScreenQuery->runTotal($invoiceId);
									
										$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
									
										$json['success']=true;
									
									}
								}
								else
								{
									$error_msg[]="Return amount not matched!"; $error_no++;	
									
								}
							}
							else
							{
								$error_msg[]="Return no Found!"; $error_no++;	
								
							}
						}
						else
						{
							$error_msg[]="Return no Found!"; $error_no++;	
							
						}
					}
					elseif($type=='GIFT CARD')
					{
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!"; $error_no++;	
						}
						else if($giftCardNo)
						{
							
							$currentDate = $dateCls->todayDate('Y-m-d');
							$validateNoInfo = $SalesTransactionGiftcardsQuery->validate($giftCardNo,$currentDate);
							
							if($validateNoInfo)
							{
								if($validateNoInfo['balance_amount']>=$val)
								{
									$chequeDate = $dateCls->dateToDB($chequeDate);
								
									$data['paymentDetails'] = array(
									
																	'invoiceId' => $invoiceId,
																	'type' => 'GIFT CARD',
																	'amount' => $val,
																	'cardoption' => '',
																	'dueDate' => '',
																	'chequeBank' => '',
																	'chequeDate' => '',
																	'chequeNo' => '',
																	'returnNo' => '',
																	'giftCardNo' => $validateNoInfo['gift_card_id'],
																	'cashBalance' => 0
									
																	);
									$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
									
									if($addPaymentLastid)
									{
									
										$SalesScreenQuery->runTotal($invoiceId);
									
										$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
									
										$json['success']=true;
									
									}
								}
								else
								{
									$error_msg[]="Gift Card Amount Not Matched!"; $error_no++;	
									
								}
							
							}
							else
							{
								$error_msg[]="Gift No Not Found!"; $error_no++;	
								
							}
							
							
						}
						else
						{
							$error_msg[]="Gift No Not Found!"; $error_no++;	
							
						}
					}
					elseif($type=='LOYALTY')
					{
							
						if($balanceDueAmt<$val)
						{
							$error_msg[]="You can't add a value greater than the invoice amount!"; $error_no++;	
						}
						else 
						{
							$loyaltyPointsBl = $CustomersMasterCustomersQuery->data($invoiceInfo['customer_id'],'loyalty_points');
							
							if($loyaltyPointsBl>=$val)
							{
								$data['paymentDetails'] = array(
								
																	'invoiceId' => $invoiceId,
																	'type' => 'LOYALTY',
																	'amount' => $val,
																	'cardoption' => '',
																	'dueDate' => '',
																	'chequeBank' => '',
																	'chequeDate' => '',
																	'chequeNo' => '',
																	'returnNo' => '',
																	'giftCardNo' => '',
																	'cashBalance' => 0
								
																);
								$addPaymentLastid = $SalesScreenQuery->addPayment($invoiceId,$data);
								
								if($addPaymentLastid)
								{
								
									$SalesScreenQuery->runTotal($invoiceId);
								
									$json['html'] = '<tr id="paymentRow'.$addPaymentLastid.'"><td class="center_cart_r_payments_added_l"><input type="text" disabled value="'.$type.'"></td><td><input type="text" class="paidamt" disabled value="'.$defCls->num($val).'"></td><td><a class="btn btn-danger pmremove" data-id="'.$addPaymentLastid.'">X</a></td></tr>';
								
									$json['success']=true;
								
								}
							}
							else
							{
						
								$error_msg[]="Loyalty Points Not Matched!"; $error_no++;	
							
							}
						}
					}
					else
					{
				
						$error_msg[]="Error Found!"; $error_no++;	
					
					}
					
					
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	
	


	
	

    public function removePayment() {
			
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
				
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId && $id)
				{
					
					$SalesScreenQuery->removePayment($invoiceId,$id);
					$SalesScreenQuery->runTotal($invoiceId);
						
					$json['success']=true;
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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

    public function cashout() {
			
		
		global $db;
		global $id;
		global $defCls;
		global $sessionCls;
		global $dateCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterCashierpointsQuery;
		global $SalesScreenQuery;
		global $AccountsTransactionsTransfersQuery;
		global $AccountsMasterAccountsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['form_url'] = $defCls->genURL("sales/screen/cashout");
			
			$userId = $sessionCls->load('signedUserId');
			$shiftInfo = $SalesScreenQuery->getShift($userId);
			if($shiftInfo)
			{
			
				if($db->request('amount')){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = 0; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					
					if(!$error_no)
					{
						$cashierPointInfo = $SystemMasterCashierpointsQuery->get($shiftInfo['cashier_point_id']);
						
						$currentBalance = $AccountsMasterAccountsQuery->data($cashierPointInfo['cash_account_id'],'closing_balance');
						
						if($currentBalance<$data['amount'])
						{
							$error_msg[]="Account balance is lower than the given amount!"; $error_no++;
						}
						else
						{
						
							$data['account_from_id'] = $cashierPointInfo['cash_account_id'];
							$data['account_to_id'] = $cashierPointInfo['transfer_account_id'];
							$data['location_id'] = $cashierPointInfo['location_id'];
							$data['added_date'] = $dateCls->todayDate('Y-m-d');
							$data['details'] = 'CASH OUT';
							$data['user_id'] = $sessionCls->load('signedUserId');
							$data['shift_id'] = $shiftInfo['shift_id'];
							
							
							$createdId = $AccountsTransactionsTransfersQuery->create($data);
							
							$transaction_no = $defCls->docNo('ATRN-',$createdId);
							$firewallCls->addLog("Account Transfer Created: ".$transaction_no);
							
							$transferInfo = $AccountsTransactionsTransfersQuery->get($createdId);
							
							
							////Account transactipn update
							$accountData = [];
							$accountData['added_date'] = $transferInfo['added_date'];
							$accountData['account_id'] = $data['account_from_id'];
							$accountData['reference_id'] = $createdId;
							$accountData['transaction_type'] = 'ATRNOUT';
							$accountData['debit'] = 0;
							$accountData['credit'] = $transferInfo['amount'];
							$accountData['remarks'] = $transaction_no;
							$accountData['user_id'] = $sessionCls->load('signedUserId');
							
							$AccountsMasterAccountsQuery->transactionAdd($accountData);
							
							
							////Account transactipn update
							$accountData = [];
							$accountData['added_date'] = $transferInfo['added_date'];
							$accountData['account_id'] = $data['account_to_id'];
							$accountData['reference_id'] = $createdId;
							$accountData['transaction_type'] = 'ATRNIN';
							$accountData['debit'] = $transferInfo['amount'];
							$accountData['credit'] = 0;
							$accountData['remarks'] = $transaction_no;
							$accountData['user_id'] = $sessionCls->load('signedUserId');
							
							$AccountsMasterAccountsQuery->transactionAdd($accountData);
							
							$json['success']=true;
							$json['success_msg']="Sucessfully Created";
	
						}
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
					
			
					require_once _HTML."sales/screen_cashout.php";
			
				}
				
			}
			else
			{
		
				echo "Invalid Cashier Point!";	
			
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
	
	

    public function complete() {
			
		global $id;
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $SystemMasterCashierpointsQuery;
		
		global $CustomersMasterCustomersQuery;
		global $AccountsMasterAccountsQuery;
		global $SalesTransactionsReturnQuery;
		global $SalesTransactionGiftcardsQuery;
		
		
		$data = [];
		$json = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$userId = $sessionCls->load('signedUserId');
			
			if($SalesScreenQuery->getShift($userId))
			{
			
				$invoiceId = $sessionCls->load('invoiceId');
				
				if($invoiceId)
				{
					$invoiceInfo = $SalesScreenQuery->get($invoiceId);
					$invoicePaymentsInfo = $SalesScreenQuery->getPayments($invoiceId);
					$customerInfo = $CustomersMasterCustomersQuery->get($invoiceInfo['customer_id']);
					
					$totalPaid = 0;
					
					$totalCredits = 0;
					
					$cashPaymentCounts = 0;
					$cardPaymentCounts = 0;
					$returnPaymentCounts = 0;
					$giftCardPaymentCounts = 0;
					$loyaltyPaymentCounts = 0;
					$chequePaymentCounts = 0;
					$creditPaymentCounts = 0;
					foreach($invoicePaymentsInfo as $ipi)
					{
						
						if($ipi['type']=='CASH')
						{
							$cashPaymentCounts+=1;
							$totalPaid+=$ipi['amount'];
						}
						
						if($ipi['type']=='CREDIT')
						{
							$totalCredits+=$ipi['amount'];
							$creditPaymentCounts+=1;
							$totalPaid+=$ipi['amount'];
						}
						
						if($ipi['type']=='CARD')
						{
							$cardOption = $AccountsMasterAccountsQuery->get($ipi['cardoption_id']);
							
							if($cardOption)
							{
								$cardPaymentCounts+=1;
								$totalPaid+=$ipi['amount'];
								
							}
							else
							{
								$error_msg[]="Invalid Card Payment Option!"; $error_no++;
							}
						}
						
						if($ipi['type']=='RETURN')
						{
							$returnNotesLines = $db->fetchAll("SELECT * FROM sales_pending_invoice_payments WHERE invoice_id='".$invoiceInfo['invoice_id']."' AND return_id='".$ipi['return_id']."'");
							
							$countReturnNotePayment = count($returnNotesLines);
						
							if($countReturnNotePayment>1)
							{
								$error_msg[]="Same return note number found multiple times!"; $error_no++;
							}
							else
							{
								$returnInfo = $SalesTransactionsReturnQuery->get($ipi['return_id']);
								
								if($returnInfo)
								{
									if($ipi['amount']<=$returnInfo['balance_value'])
									{
										$returnPaymentCounts+=1;
										$totalPaid+=$ipi['amount'];
										
									}
									else
									{
										$error_msg[]="Invalid Return Note Amount!"; $error_no++;
									}
								}
								else
								{
									$error_msg[]="Invalid Return Note No!"; $error_no++;
								}
							}
						}
						
						if($ipi['type']=='GIFT CARD')
						{
							
							$GiftCardNotesLines = $db->fetchAll("SELECT * FROM sales_pending_invoice_payments WHERE invoice_id='".$invoiceInfo['invoice_id']."' AND gift_card_id='".$ipi['gift_card_id']."'");
							
							$countGiftCard = count($GiftCardNotesLines);
						
							if($countGiftCard>1)
							{
								$error_msg[]="Same gift card number found multiple times!"; $error_no++;
							}
							else
							{
								$gcInfo = $SalesTransactionGiftcardsQuery->get($ipi['gift_card_id']);
								
								if($gcInfo)
								{
									if($ipi['amount']<=$gcInfo['balance_amount'])
									{
										$giftCardPaymentCounts+=1;
										$totalPaid+=$ipi['amount'];
										
									}
									else
									{
										$error_msg[]="Invalid Gift Card Amount!"; $error_no++;
									}
								}
								else
								{
									$error_msg[]="Invalid Gift Card No!"; $error_no++;
								}
							}
						}
						
						if($ipi['type']=='LOYALTY')
						{
							 if($customerInfo['loyalty_points']>=$ipi['amount'])
							 {
									$loyaltyPaymentCounts+=1;
									$totalPaid+=$ipi['amount'];
								 
							 }
							 else
							 {
								 $error_msg[]="Invalid Loyalty Points!"; $error_no++;
							 }
						}
						
						if($ipi['type']=='CHEQUE')
						{
							$chequePaymentCounts+=1;
							$totalPaid+=$ipi['amount'];
						}
						
					}
					
					$customerOutstanding = $customerInfo['closing_balance']+$totalCredits;
					
					if($cashPaymentCounts>1)
					{
						$error_msg[]="Multiple cash payments found. Cash payment can be added only once!"; $error_no++;
					}
					elseif($loyaltyPaymentCounts>1)
					{
						$error_msg[]="Multiple loyalty payments found. Loyalty payment can be added only once!"; $error_no++;
					}
					elseif($customerOutstanding>$customerInfo['credit_limit'] && $totalCredits)
					{
						$error_msg[]="Credit Limit Reached. Credit limit is Rs.".$defCls->money($customerInfo['credit_limit'])." and the total outstanding amount with the current invoice is Rs.".$defCls->money($customerOutstanding)."!"; $error_no++;
					}
					elseif($totalPaid<$invoiceInfo['total_sale'])
					{
						$error_msg[]="Payment does not match the total sales!"; $error_no++;
					}
					
					
					if(!$error_no)
					{
						
						$lastInvoiceId = $SalesScreenQuery->complete($invoiceInfo['invoice_id']);
						$sessionCls->set('lastPrintedInvoiceNo',$lastInvoiceId);
						
						$sessionCls->destroy('invoiceId');
						$createdInvId = $SalesScreenQuery->create('');
						$sessionCls->set('invoiceId',$createdInvId);
						
						$json['redirect']=$defCls->genURL("sales/screen/print/".$lastInvoiceId."/");
						
						$json['success']=true;
						
					}
					
					
					
				}
				else
				{
			
					$error_msg[]="Invalid Invoice number!"; $error_no++;	
				
				}
					
				
				
				
			}
			else
			{
		
				$error_msg[]="Invalid Cashier Point!"; $error_no++;	
			
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
	