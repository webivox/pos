<?php

class SalesScreenQuery {
	
	private $tableName="sales_pending_invoices";
	private $itemTableName="sales_pending_invoice_items";
    
    public function get($invoiceId) {
		
        global $db;

        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE invoice_id='".$invoiceId."'");
		
		return !empty($row) ? $row : [];
    }
	
    
    public function getSql($sql) {
		
        global $db;

        $row = $db->fetch("SELECT * FROM ".$this->tableName." ".$sql."");
		
		return !empty($row) ? $row : [];
    }
	
    
    public function getsSql($sql) {
		
        global $db;

        $row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql."");
		
		return !empty($row) ? $row : [];
    }
	
    
    public function create($sql) {
		
        global $db;
		global $sessionCls;
		global $SystemMasterUsersQuery;
		
		$userid = $sessionCls->load('signedUserId');
		
		$userInfo = $SystemMasterUsersQuery->get($userid);

        $row = $db->fetch("SELECT * FROM sales_shifts WHERE user_id='".$userid."' AND end_on='0000-00-00 00:00:00' ORDER BY shift_id DESC");
		
		if($row)
		{
				
			if($db->query("INSERT INTO ".$this->tableName." SET
			
								`location_id`='".$userInfo['location_id']."',
								`user_id`='".$userInfo['user_id']."',
								`cashier_point_id`='".$row['cashier_point_id']."',
								`customer_id`=1,
								`sales_rep_id`=1,
								`invoice_no`='',
								`added_date`=NOW(),
								`total_sale`=0,
								`discount_type`='',
								`discount_value`=0,
								`discount_amount`=0,
								`total_sale_cost`=0,
								`total_paid`=0,
								`status`=0
								
						"))
			{
				return $db->last_id();
			}
			else{ return false; }
		}
		else{ return false; }
    }
	
	
	
    
    public function getShift($userId) {
		
        global $db;

        $row = $db->fetch("SELECT * FROM sales_shifts WHERE user_id='".$userId."' AND end_on='0000-00-00 00:00:00' ORDER BY shift_id DESC");
		
		return !empty($row) ? $row : [];
    }
	
    public function shiftStart($userId,$cashierPointId) {
		
        global $db;
		global $dateCls;
		global $sessionCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		
		$nowDateTime = $dateCls->todayDate('Y-m-d').' '.$dateCls->nowTimeDb();

        if($db->query("INSERT INTO sales_shifts SET user_id='".$userId."', cashier_point_id='".$cashierPointId."', start_on='".$nowDateTime."'"))
		{
			
			$getInvoiceByUser = $SalesScreenQuery->getSql("WHERE user_id='".$userId."' AND status=0 ORDER BY invoice_id DESC");
			
			if($getInvoiceByUser)
			{
				
				$sessionCls->set('invoiceId',$getInvoiceByUser['invoice_id']);
			
				return true;
			}
			else
			{
				
				$userInfo = $SystemMasterUsersQuery->get($userId);
				
				if($db->query("INSERT INTO ".$this->tableName." SET
		
							`location_id`='".$userInfo['location_id']."',
							`user_id`='".$userInfo['user_id']."',
							`cashier_point_id`='".$cashierPointId."',
							`customer_id`=1,
							`sales_rep_id`=1,
							`invoice_no`='',
							`added_date`=NOW(),
							`total_sale`=0,
							`discount_type`='',
							`discount_value`=0,
							`discount_amount`=0,
							`total_sale_cost`=0,
							`total_paid`=0,
							`status`=0
							
					"))
				{
					$lastId = $db->last_id();
					
					$sessionCls->set('invoiceId',$lastId);
					
					return $lastId;
					
					
				}
				else{ return false; }
				
			}
			
			
			return true;
		}
		else{ return false; }
    }
	
	
    
    public function endShift($userId) {
		
        global $db;
        global $dateCls;
		
		$nowDateTime = $dateCls->todayDate('Y-m-d').' '.$dateCls->nowTimeDb();

        if($db->query("UPDATE sales_shifts SET end_on='".$nowDateTime."' WHERE user_id='".$userId."' AND end_on='0000-00-00 00:00:00' ORDER BY shift_id DESC"))
		{
			return true;
		}
		else{ return false; }
    }
    
    public function getItems($invoiceId) {
		
        global $db;

        $row = $db->fetchAll("SELECT * FROM ".$this->itemTableName." WHERE invoice_id='".$invoiceId."' ORDER BY invoice_item_id ASC");
		
		return !empty($row) ? $row : [];
    }
    
    public function getItem($itemId) {
		
        global $db;

        $row = $db->fetch("SELECT * FROM ".$this->itemTableName." WHERE invoice_item_id='".$itemId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function addItem($invoiceId, $itemId) {
		
        global $db;
		global $InventoryMasterItemsQuery;
		global $SalesScreenQuery;
		global $CustomersMasterCustomersQuery;
		global $dateCls;
		
		$addedDateTime = $dateCls->todayDate('Y-m-d').' '.$dateCls->nowTimeDb();
		
		$itemInfo = $InventoryMasterItemsQuery->get($itemId);
		$salesInfo = $SalesScreenQuery->get($invoiceId);
		$customerGroupId = $CustomersMasterCustomersQuery->data($salesInfo['customer_id'],'customer_group_id');
		
		$masterPrice = $InventoryMasterItemsQuery->data($itemId,'selling_price');
		$price =  $InventoryMasterItemsQuery->getCustomerGroupPrice($customerGroupId,$itemId);
		
		$total=$price*$itemInfo['minimum_qty'];

        if($db->query("INSERT INTO ".$this->itemTableName." SET
		
		
					`invoice_id`='".$invoiceId."',
					`item_id`='".$itemInfo['item_id']."',
					`cost`='".$itemInfo['cost']."',
					`master_price`='".$masterPrice."',
					`price`='".$price."',
					`discount`=0,
					`unit_price`='".$price."',
					`qty`='".$itemInfo['minimum_qty']."',
					`total`='".$total."',
					`created_on`='".$addedDateTime."'
					
			"))
		{
			$lastId = $db->last_id();
			
			return $lastId;
			
			
		}
		else{ return false; }
    }
	
	
    public function itemHTML($itemId) {
		
		global $db;
		global $dateCls;
		global $sessionCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $InventoryMasterItemsQuery;
		global $defCls;
		
		
		$salesItemInfo = $SalesScreenQuery->getItem($itemId);
		
		$itemName = $defCls->showText($InventoryMasterItemsQuery->data($salesItemInfo['item_id'],'name'));
		
		$masterPrice = $defCls->num($salesItemInfo['master_price']);
		$amount = $defCls->num($salesItemInfo['price']);
		$discount = $defCls->num($salesItemInfo['discount']);
		$unitPrice = $defCls->num($salesItemInfo['unit_price']);
		$qty = $defCls->num($salesItemInfo['qty']);
		$total = $defCls->num($salesItemInfo['total']);
		
		
		return '<tr class="cart_item_row" id="cart_item_row'.$itemId.'">
                
                	<td class="cart_item_no"><input type="text" disabled value="0"></td>
                	<td class="cart_item_itemname"><input type="text" disabled value="'.$itemName.'"></td>
                	<td class="cart_item_price"><input type="text" class="ciMasterPrice" disabled value="'.$masterPrice.'"></td>
                	<td class="cart_item_amount"><input type="text" class="ciAmount okeyboard" data-id="'.$salesItemInfo['invoice_item_id'].'" id="itemAmount'.$itemId.'" value="'.$amount.'"></td>
                	<td class="cart_item_dsc"><input type="text" class="ciDiscount okeyboard" data-id="'.$salesItemInfo['invoice_item_id'].'" id="itemDiscount'.$itemId.'" value="'.$discount.'"></td>
                	<td class="cart_item_uprice"><input type="text" class="ciPrice" disabled value="'.$unitPrice.'"></td>
                	<td class="cart_item_qty"><input type="text" class="ciQty okeyboard" data-id="'.$salesItemInfo['invoice_item_id'].'" id="itemQty'.$itemId.'" value="'.$qty.'"></td>
                	<td class="cart_item_total"><input type="text" class="ciTotal" id="itemTotal'.$itemId.'"  value="'.$total.'"></td>
                	<td class="cart_item_action">
                    
                    	<a class="btn btn-success increase" data-id="'.$itemId.'">+</a>
                    	<a class="btn btn-black reduce" data-id="'.$itemId.'">-</a>
                    	<a class="btn btn-danger remove" data-id="'.$itemId.'">X</a>
                    
                    </td>
                
                </tr>';
		
	}
	
	
	
	/*
    public function itemsHTML($invoiceId) {
		
		global $db;
		global $dateCls;
		global $sessionCls;
		global $SystemMasterUsersQuery;
		global $SalesScreenQuery;
		global $InventoryMasterItemsQuery;
		global $defCls;
		
		$cartItems = $SalesScreenQuery->getItems($invoiceId);
		$html = '';
		$i = 0;
		foreach($cartItems as $ci) {
		
			$i+=1;
			$lineNo = str_pad($i, 3, '0', STR_PAD_LEFT);
			
			$itemName = $defCls->showText($InventoryMasterItemsQuery->data($ci['item_id'],'name'));
			
			$masterPrice = $defCls->num($ci['master_price']);
			$amount = $defCls->num($ci['price']);
			$discount = $defCls->num($ci['discount']);
			$unitPrice = $defCls->num($ci['unit_price']);
			$qty = $defCls->num($ci['qty']);
			$total = $defCls->num($ci['total']);
			
			
			$html .= '<tr class="cart_item_row" id="cart_item_row'.$ci['invoice_item_id'].'">
                
                	<td class="cart_item_no"><input type="text" disabled value="'.$lineNo.'"></td>
                	<td class="cart_item_itemname"><input type="text" disabled value="'.$itemName.'"></td>
                	<td class="cart_item_price"><input type="text" class="ciPrice" disabled value="'.$masterPrice.'"></td>
                	<td class="cart_item_amount"><input type="text" class="ciAmount" data-id="'.$ci['invoice_item_id'].'" id="itemAmount'.$ci['invoice_item_id'].'" value="'.$amount.'"></td>
                	<td class="cart_item_dsc"><input type="text" class="ciDiscount" data-id="'.$ci['invoice_item_id'].'" id="itemDiscount'.$ci['invoice_item_id'].'" value="'.$discount.'"></td>
                	<td class="cart_item_uprice"><input type="text" class="ciPrice" data-id="'.$ci['invoice_item_id'].'" disabled value="'.$unitPrice.'"></td>
                	<td class="cart_item_qty"><input type="text" class="ciQty" data-id="'.$ci['invoice_item_id'].'" id="itemQty'.$ci['invoice_item_id'].'" value="'.$qty.'"></td>
                	<td class="cart_item_total"><input type="text" class="ciTotal" id="itemTotal'.$ci['invoice_item_id'].'" disabled value="'.$total.'"></td>
                	<td class="cart_item_action">
                    
                    	<a class="btn btn-success increase" data-id="'.$ci['invoice_item_id'].'">+</a>
                    	<a class="btn btn-black reduce" data-id="'.$ci['invoice_item_id'].'">-</a>
                    	<a class="btn btn-danger remove" data-id="'.$ci['invoice_item_id'].'">X</a>
                    
                    </td>
                
                </tr>';
		}
		
		return $html;
	}
	
	*/
	
    
    public function addCustomer($invoiceId, $customerId) {
		
        global $db;
		global $dateCls;
		
        if($db->query("UPDATE ".$this->tableName." SET customer_id = '".$customerId."' WHERE invoice_id = '".$invoiceId."'"))
		{
			return true;
			
		}
		else{ return false; }
    }
	
	
    
    public function addSalesRep($invoiceId, $salesRepId) {
		
        global $db;
		global $dateCls;
		
        if($db->query("UPDATE ".$this->tableName." SET sales_rep_id = '".$salesRepId."' WHERE invoice_id = '".$invoiceId."'"))
		{
			return true;
			
		}
		else{ return false; }
    }
	
	
    
    public function updateItemPrice($invoiceId, $invoiceItemId, $val) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("UPDATE ".$this->itemTableName." SET price = '".$val."' WHERE invoice_item_id = '".$invoiceItemId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
    
    public function updateItemDiscount($invoiceId, $invoiceItemId, $val) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("UPDATE ".$this->itemTableName." SET discount = '".$val."' WHERE invoice_item_id = '".$invoiceItemId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
    
    public function updateItemQTY($invoiceId, $invoiceItemId, $val) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("UPDATE ".$this->itemTableName." SET qty = '".$val."' WHERE invoice_item_id = '".$invoiceItemId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
    
    public function removeItem($invoiceId, $invoiceItemId) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("DELETE FROM ".$this->itemTableName." WHERE invoice_item_id = '".$invoiceItemId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
	
    
    public function updateSalesDiscount($type, $invoiceId, $val) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("UPDATE ".$this->tableName." SET discount_type = '".$type."', discount_value = '".$val."' WHERE invoice_id = '".$invoiceId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
	
    
    public function updateComment($invoiceId, $val) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("UPDATE ".$this->tableName." SET comments = '".$val."' WHERE invoice_id = '".$invoiceId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
	
    public function getPayments($invoiceId) {
		
        global $db;

        $row = $db->fetchAll("SELECT * FROM sales_pending_invoice_payments WHERE invoice_id='".$invoiceId."' ORDER BY invoice_payment_id ASC");
		
		return !empty($row) ? $row : [];
    }
	
    
    public function addPayment($invoiceId, $data) {
		
        global $db;
		global $dateCls;
		
		$nowDateTime = $dateCls->todayDate('Y-m-d').' '.$dateCls->nowTimeDb();
		
        if($db->query("INSERT INTO sales_pending_invoice_payments SET invoice_id = '".$invoiceId."', type = '".$data['paymentDetails']['type']."', amount = '".$data['paymentDetails']['amount']."', amount_balance='".$data['paymentDetails']['cashBalance']."', cardoption_id = '".$data['paymentDetails']['cardoption']."', return_id = '".$data['paymentDetails']['returnNo']."', gift_card_id = '".$data['paymentDetails']['giftCardNo']."', credit_date = '".$data['paymentDetails']['dueDate']."', cheque_bank = '".$data['paymentDetails']['chequeBank']."', cheque_date = '".$data['paymentDetails']['chequeDate']."', cheque_no = '".$data['paymentDetails']['chequeNo']."', created_on = '".$nowDateTime."'"))
		{
			$lastId = $db->last_id();
			
			return $lastId;
			
		}
		else{ return false; }
    }
	
	
	
	
    
    public function removePayment($invoiceId, $paymentId) {
		
        global $db;
		global $dateCls;
		global $SalesScreenQuery;
		
        if($db->query("DELETE FROM sales_pending_invoice_payments WHERE invoice_payment_id = '".$paymentId."'"))
		{
			return true;
			
		}
		else{ return false; }
		
    }
	
    
    public function complete($invoiceId) {
		
        global $db;
		global $defCls;
		global $dateCls;
		global $SalesScreenQuery;
		global $SystemMasterLocationsQuery;
		global $CustomersMasterCustomersQuery;
		global $SalesTransactionsReturnQuery;
		global $stockTransactionsCls;
		global $SalesTransactionGiftcardsQuery;
		global $AccountsMasterAccountsQuery;
		global $SystemMasterCashierpointsQuery;
		global $AccountsTransactionChequeQuery;
		
		$invoiceInfo = $SalesScreenQuery->get($invoiceId);
		$invoiceItemsInfo = $SalesScreenQuery->getItems($invoiceId);
		$invoicePaymentsInfo = $SalesScreenQuery->getPayments($invoiceId);
		$locationInfo = $SystemMasterLocationsQuery->get($invoiceInfo['location_id']);
		$cashierPointInfo = $SystemMasterCashierpointsQuery->get($invoiceInfo['cashier_point_id']);
		
		$row = $db->fetch("SELECT * FROM sales_invoices WHERE location_id='".$invoiceInfo['location_id']."' ORDER BY invoice_id DESC");
		
		if($row)
		{
			$invoiceNo = $row['invoice_no'];
			// Check if the regular expression finds a match
			if (preg_match('/(\d+)$/', $invoiceNo, $matches)) {
				// Remove leading zeros from the matched number
				$invNumber = ltrim($matches[1], '0');
				// If no number is left (i.e., it's an empty string), set it to '0'
				$invNumber = $invNumber === '' ? '0' : $invNumber;
			} else {
				// If no match is found, set invNumber to '0'
				$invNumber = '0';
			}
		}
		else{ $invNumber = '0'; }
		
		$invoiceNo = $invNumber+1;
		$invoiceNo = $defCls->docNo($locationInfo['invoice_no_start'],$invoiceNo);
		$dateNow = $dateCls->todayDate('Y-m-d').' '.$dateCls->nowTimeDb();
		$dateToday = $dateCls->todayDate('Y-m-d');
		
		if($db->query("INSERT INTO sales_invoices SET
			
								`location_id`='".$invoiceInfo['location_id']."',
								`user_id`='".$invoiceInfo['user_id']."',
								`cashier_point_id`='".$invoiceInfo['cashier_point_id']."',
								`customer_id`='".$invoiceInfo['customer_id']."',
								`sales_rep_id`='".$invoiceInfo['sales_rep_id']."',
								`invoice_no`='".$invoiceNo."',
								`added_date`='".$dateNow."',
								`total_sale`='".$invoiceInfo['total_sale']."',
								`discount_type`='".$invoiceInfo['discount_type']."',
								`discount_value`='".$invoiceInfo['discount_value']."',
								`discount_amount`='".$invoiceInfo['discount_amount']."',
								`total_sale_cost`='".$invoiceInfo['total_sale_cost']."',
								`total_paid`='".$invoiceInfo['total_paid']."',
								`cash_sales`='".$invoiceInfo['cash_sales']."',
								`card_sales`='".$invoiceInfo['card_sales']."',
								`return_sales`='".$invoiceInfo['return_sales']."',
								`gift_card_sales`='".$invoiceInfo['gift_card_sales']."',
								`loyalty_sales`='".$invoiceInfo['loyalty_sales']."',
								`credit_sales`='".$invoiceInfo['credit_sales']."',
								`cheque_sales`='".$invoiceInfo['cheque_sales']."',
								`comments`='".$invoiceInfo['comments']."',
								`status`=1
								
		"))
		{
			
			
			$lastId = $db->last_id();
			
			///
			
			
			
			//
			
			////Customer transactipn update
			$customerData = [];
			$customerData['added_date'] = $dateToday;
			$customerData['customer_id'] = $invoiceInfo['customer_id'];
			$customerData['reference_id'] = $lastId;
			$customerData['transaction_type'] = 'INV';
			$customerData['debit'] = $invoiceInfo['total_sale'];
			$customerData['credit'] = 0;
			$customerData['remarks'] = $invoiceNo;
			
			$CustomersMasterCustomersQuery->transactionAdd($customerData);
			
			$no_of_items = 0;
			$no_of_qty = 0;
			foreach($invoiceItemsInfo as $item)
			{
				$db->query("INSERT INTO sales_invoice_items SET
											`invoice_id`='".$lastId."',
											`item_id`='".$item['item_id']."',
											`cost`='".$item['cost']."',
											`master_price`='".$item['master_price']."',
											`price`='".$item['price']."',
											`discount`='".$item['discount']."',
											`unit_price`='".$item['unit_price']."',
											`qty`='".$item['qty']."',
											`total`='".$item['total']."',
											`created_on`='".$item['created_on']."'
							");
							
				$itemastId = $db->last_id();
							
				//// UPDATE STOCKS
					 
				 $stockData = [];
				 
				 $stockData['item_id'] = $item['item_id'];
				 $stockData['location_id'] = $invoiceInfo['location_id'];
				 $stockData['reference_id'] = $lastId;
				 $stockData['reference_row_id'] = $itemastId;
				 $stockData['transaction_type'] = 'INV';
				 $stockData['added_date'] = $dateToday;
				 $stockData['amount'] = $item['cost'];
				 $stockData['qty_in'] = 0;
				 $stockData['qty_out'] = $item['qty'];
				 $stockData['remarks'] = $invoiceNo;
				 
				$stockTransactionsCls->add($stockData);
				
				$no_of_items += 1;
				$no_of_qty += $item['qty'];
			
			}
			
			foreach($invoicePaymentsInfo as $pmnt)
			{
				
				$db->query("INSERT INTO sales_invoice_payments SET
								`invoice_id`='".$lastId."',
								`type`='".$pmnt['type']."',
								`cardoption_id`='".$pmnt['cardoption_id']."',
								`return_id`='".$pmnt['return_id']."',
								`gift_card_id`='".$pmnt['gift_card_id']."',
								`credit_date`='".$pmnt['credit_date']."',
								`cheque_bank`='".$pmnt['cheque_bank']."',
								`cheque_date`='".$pmnt['cheque_date']."',
								`cheque_no`='".$pmnt['cheque_no']."',
								`amount`='".$pmnt['amount']."',
								`amount_balance`='".$pmnt['amount_balance']."',
								`created_on`='".$pmnt['created_on']."'
							");
				$lastInvoicePaymentId = $db->last_id();
				
				if($pmnt['type'] == 'CASH')
				{
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $dateToday;
					$accountData['account_id'] = $cashierPointInfo['cash_account_id'];
					$accountData['reference_id'] = $lastInvoicePaymentId;
					$accountData['transaction_type'] = 'INV';
					$accountData['debit'] = $pmnt['amount'];
					$accountData['credit'] = 0;
					$accountData['remarks'] = $invoiceNo;
					
					$AccountsMasterAccountsQuery->transactionAdd($accountData);
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_cash'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
				}
				
				
				if($pmnt['type'] == 'CARD')
				{
					
					////Account transactipn update
					$accountData = [];
					$accountData['added_date'] = $dateToday;
					$accountData['account_id'] = $pmnt['cardoption_id'];
					$accountData['reference_id'] = $lastInvoicePaymentId;
					$accountData['transaction_type'] = 'INV';
					$accountData['debit'] = $pmnt['amount'];
					$accountData['credit'] = 0;
					$accountData['remarks'] = $invoiceNo;
					
					$AccountsMasterAccountsQuery->transactionAdd($accountData);
					
					
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_card'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] == 'RETURN')
				{
					
					$SalesTransactionsReturnQuery->addUsedAmount($pmnt['return_id'],$pmnt['amount']);
					
					
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_return'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] == 'GIFT CARD')
				{
					
					$SalesTransactionGiftcardsQuery->addUsedAmount($pmnt['gift_card_id'],$pmnt['amount']);
					
					
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_gift_card'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] == 'CREDIT')
				{
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_credit'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] == 'CHEQUE')
				{
					
					$chequeData = [];
					$chequeData['reference_id'] = $lastInvoicePaymentId;
					$chequeData['added_date'] = $dateToday;
					$chequeData['transaction_type'] = 'INV';
					$chequeData['type'] = 'Received';
					$chequeData['bank_code'] = $pmnt['cheque_bank'];
					$chequeData['cheque_date'] = $pmnt['cheque_date'];
					$chequeData['cheque_no'] = $pmnt['cheque_no'];
					$chequeData['amount'] = $pmnt['amount'];
					$chequeData['remarks'] = $invoiceNo;
					$chequeData['deposited_account_id'] = 0;
					$chequeData['status'] = 0;
					
					$AccountsTransactionChequeQuery->create($chequeData);
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_cheque'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] == 'LOYALTY')
				{
					
					
					$loyaltyData = [];
					$loyaltyData['added_date'] = $dateToday;
					$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
					$loyaltyData['reference_id'] = $lastInvoicePaymentId;
					$loyaltyData['transaction_type'] = 'INV';
					$loyaltyData['debit'] = 0;
					$loyaltyData['credit'] = $pmnt['amount'];
					$loyaltyData['remarks'] = $invoiceNo;
					
					$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					
					
					////Loyalty transactipn update
					if($defCls->master('loyalty_points_loyalty'))
					{
						$pointsRules = $defCls->master('loyalty_points');
						$pointsRulesExp = explode('=',$pointsRules);
						$pointsRulesAmount = $pointsRulesExp[0];
						$pointsRulesforOne = $pointsRulesExp[1];
						
						$pointsCalc = floor($pmnt['amount']/$pointsRulesAmount);
						
						$loyaltyData = [];
						$loyaltyData['added_date'] = $dateToday;
						$loyaltyData['customer_id'] = $invoiceInfo['customer_id'];
						$loyaltyData['reference_id'] = $lastInvoicePaymentId;
						$loyaltyData['transaction_type'] = 'INV';
						$loyaltyData['debit'] = $pointsCalc;
						$loyaltyData['credit'] = 0;
						$loyaltyData['remarks'] = $invoiceNo;
						
						$CustomersMasterCustomersQuery->loyaltyTransactionAdd($loyaltyData);
					}
					
				}
				
				if($pmnt['type'] !== 'CREDIT')
				{
					////Customer transactipn update
					$customerData = [];
					$customerData['added_date'] = $dateToday;
					$customerData['customer_id'] = $invoiceInfo['customer_id'];
					$customerData['reference_id'] = $lastInvoicePaymentId;
					$customerData['transaction_type'] = 'INVPMNT';
					$customerData['debit'] = 0;
					$customerData['credit'] = $pmnt['amount'];
					$customerData['remarks'] = $invoiceNo;
					
					$CustomersMasterCustomersQuery->transactionAdd($customerData);
				}
			}
			
			$db->query("UPDATE sales_invoices SET no_of_items = '".$no_of_items."', no_of_qty='".$no_of_qty."' WHERE invoice_id = '".$lastId."'");
			
			
			$db->query("DELETE FROM sales_pending_invoices WHERE invoice_id = '".$invoiceId."'");
			$db->query("DELETE FROM sales_pending_invoice_items WHERE invoice_id = '".$invoiceId."'");
			$db->query("DELETE FROM sales_pending_invoice_payments WHERE invoice_id = '".$invoiceId."'");
			
			return $lastId;
        
		}
		else{ return false; }
		
    }
	
    
    public function runTotal($invoiceId) {
		
		
        global $db;
		global $SalesScreenQuery;
		
		$invoiceInfo = $SalesScreenQuery->get($invoiceId);
		$items = $SalesScreenQuery->getItems($invoiceId);
		$payments = $SalesScreenQuery->getPayments($invoiceId);
		
		$total_sale = 0;
		$total_sale_cost = 0;
		
		foreach($items as $item)
		{
			
			//`cost`, `master_price`, `price`, `discount`, `unit_price`, `qty`, `total`
			$price = $item['price'];
			$discount = $item['discount'];
			$qty = $item['qty'];
			
			if($discount){ $disc = $item['price']*$discount/100; }
			else{ $disc = 0; }
			
			$unit_price = $item['price']-$disc;
			$total = $unit_price*$qty;
			
			$db->query("UPDATE ".$this->itemTableName." SET unit_price = '".$unit_price."', total='".$total."' WHERE invoice_item_id = '".$item['invoice_item_id']."'");
			
			$total_sale_cost += $item['cost'];
			$total_sale += $total;
			
		}
		
		if($invoiceInfo['discount_type']=='P')
		{
			$discountCalc = $total_sale*$invoiceInfo['discount_value']/100;
		}
		elseif($invoiceInfo['discount_type']=='F')
		{
			$discountCalc = $invoiceInfo['discount_value'];
		}
		else
		{
			$discountCalc = 0;
		}
		
		$totalSalesAmount = $total_sale-$discountCalc;
		
		
		$totalPayments = 0;
		$totalCash = 0;
		$totalCard = 0;
		$totalReturn = 0;
		$totalGiftCard = 0;
		$totalLoyalty = 0;
		$totalCredit = 0;
		$totalCheque = 0;
		foreach($payments as $payment)
		{
			
			$totalPayments +=$payment['amount'];
			
			if($payment['type']=='CASH'){ $totalCash+=$payment['amount']; }
			if($payment['type']=='CARD'){ $totalCard+=$payment['amount']; }
			if($payment['type']=='CREDIT'){ $totalCredit+=$payment['amount']; }
			if($payment['type']=='RETURN'){ $totalReturn+=$payment['amount']; }
			if($payment['type']=='GIFT CARD'){ $totalGiftCard+=$payment['amount']; }
			if($payment['type']=='LOYALTY'){ $totalLoyalty+=$payment['amount']; }
			if($payment['type']=='CHEQUE'){ $totalCheque+=$payment['amount']; }
			
			
			
			
			
		}
		
		
		
		
		$db->query("UPDATE ".$this->tableName." SET total_sale = '".$totalSalesAmount."', discount_amount='".$discountCalc."', total_sale_cost='".$total_sale_cost."', total_paid='".$totalPayments."', `cash_sales`='".$totalCash."', `card_sales`='".$totalCard."', `return_sales`='".$totalReturn."', `gift_card_sales`='".$totalGiftCard."', `loyalty_sales`='".$totalLoyalty."', `credit_sales`='".$totalCredit."', `cheque_sales`='".$totalCheque."' WHERE invoice_id = '".$invoiceId."'");
	}
}

$SalesScreenQuery = new SalesScreenQuery;