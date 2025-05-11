<?php

class SalesTransactionInvoicesQuery{
	
	private $tableName="sales_invoices";
	private $itemTableName="sales_invoice_items";
    
    public function get($invoiceId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE invoice_id='".$invoiceId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($invoiceId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE invoice_id='".$invoiceId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($invoiceId)
	{
		global $db;
		$res = $db->fetchAll("SELECT invoice_id FROM ".$this->tableName." WHERE invoice_id='".$invoiceId."'");
		$count = count($res);
		return $count;
	}
	
	
	
	public function getPagination($sql,$pageno)
	{
		$pageno=$pageno;
		global $db;
		global $defCls;
		
		$per_page=$defCls->master('per_page_results');
		
		if(!$pageno || $pageno=='1' || $pageno=='0')
		{
			$limitc='0,'.$per_page;
			$realpago=0;
		}
		// no of pages
		$res = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		$count_pn = count($res);
		
		$pagination='';
		if($per_page<$count_pn)
		{
			$totaldeivde=$count_pn/$per_page;
			
			$c=0;
			$pagination='<ul class="pagination">';
			for($s=0;$s<$totaldeivde;$s++)
			{
				$ss=$s+1;
				
				if($pageno==$ss){ $pagination.='<li class="active"><span>'.$ss.'</span></li>'; }
				else
				{ 
					$pagination.='<li><a data-pageno="'.$ss.'">'.$ss.'</a></li>';
				}
			}
			$pagination.='</ul>';
		}
		//end page no
		
		$limit_start=$pageno*$per_page-$per_page;
		$limit_end=$per_page;
		
		
		return array('total'=>$count_pn,'html'=>$pagination,'limit_start'=>$limit_start,'limit_end'=>$limit_end);
	}
	
	
    public function getItems($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->itemTableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	
    public function getPayments($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM sales_invoice_payments ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	
    public function cancellation($data) {
		
		global $db;
		global $CustomersMasterCustomersQuery;
		global $SalesTransactionInvoicesQuery;
		global $stockTransactionsCls;
		global $SalesTransactionsReturnQuery;
		global $SalesTransactionGiftcardsQuery;
		global $AccountsTransactionChequeQuery;

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						status='0'
						
						WHERE
						
						invoice_id = ".$data['invoice_id']."
						
				";
					
        if($db->query($sql))
		{
			$customerData = [];
			$customerData['reference_id'] = $data['invoice_id'];
			$customerData['transaction_type'] = 'INV';
			
			$CustomersMasterCustomersQuery->transactionDelete($customerData);
			
			///////////
			
			$itemsInfo = $SalesTransactionInvoicesQuery->getItems("WHERE invoice_id='".$data['invoice_id']."'");
			
			foreach($itemsInfo as $item)
			{
			
				$stockData = [];
				
				$stockData['item_id'] = $item['item_id'];
				$stockData['reference_id'] = $data['invoice_id'];
				$stockData['reference_row_id'] = $item['invoice_item_id'];
				$stockData['transaction_type'] = 'INV';
				
				$stockTransactionsCls->delete($stockData);
				
				if($item['unique_no'])
				{
				
					$db->query("UPDATE inventory_unique_nos SET 
							
									used_invoice_id='',
									used_date='',
									status='0'
									
									WHERE
									
									unique_no = '".$item['unique_no']."'
									
							");
				}
			}
			
			///////////
			
			$paymentsInfo = $SalesTransactionInvoicesQuery->getPayments("WHERE invoice_id='".$data['invoice_id']."'");
			
			foreach($paymentsInfo as $pmnt)
			{
				
				if($pmnt['type'] == 'CASH')
				{
				
					$accountData = [];
					$accountData['reference_id'] = $pmnt['invoice_payment_id'];
					$accountData['transaction_type'] = 'INV';
					
					$AccountsMasterAccountsQuery->transactionDelete($accountData);
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
					
				
				}
				if($pmnt['type'] == 'CARD')
				{
				
					$accountData = [];
					$accountData['reference_id'] = $pmnt['invoice_payment_id'];
					$accountData['transaction_type'] = 'INV';
					
					$AccountsMasterAccountsQuery->transactionDelete($accountData);
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
				}
				
				if($pmnt['type'] == 'RETURN')
				{
					$SalesTransactionsReturnQuery->removeUsedAmount($pmnt['return_id'],$pmnt['amount']);
					
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
				}
				
				if($pmnt['type'] == 'GIFT CARD')
				{
					$SalesTransactionGiftcardsQuery->removeUsedAmount($pmnt['gift_card_id'],$pmnt['amount']);
					
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
				}
				
				if($pmnt['type'] == 'CREDIT')
				{
					
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
					
				}
				
				if($pmnt['type'] == 'CHEQUE')
				{
					
					$chequeData = [];
					$chequeData['reference_id'] = $pmnt['invoice_payment_id'];
					$chequeData['transaction_type'] = 'INV';
					$chequeData['type'] = 'Received';
					
					$AccountsTransactionChequeQuery->chequeRemove($chequeData);
					
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
					
				}
				
				if($pmnt['type'] == 'LOYALTY')
				{
					//Remove loyalty
					$CustomersMasterCustomersQuery->loyaltyTransactionDelete($pmnt['invoice_payment_id'],'INV');
				}
				
				
				
				if($pmnt['type'] !== 'CREDIT')
				{
					////Customer transactipn update
					$customerData = [];
					$customerData['reference_id'] = $pmnt['invoice_payment_id'];
					$customerData['transaction_type'] = 'INVPMNT';
					
					$CustomersMasterCustomersQuery->transactionDelete($customerData);
				}
			}
			
		}
		else{ return false; }
    }
}

// Instantiate the blogsModels class
$SalesTransactionInvoicesQuery = new SalesTransactionInvoicesQuery;
?>