<?php

class CustomersMasterCustomersQuery {
	
	private $tableName="customers_customers";
    
    public function get($customerId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE customer_id='".$customerId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($customerId,$column)
	{
		
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE customer_id='".$customerId."'");
		
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($customerId)
	{
		global $db;
		$res = $db->fetchAll("SELECT customer_id FROM ".$this->tableName." WHERE customer_id='".$customerId."'");
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
    
    public function create($data) {
		
		global $db;

        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						customer_group_id='".$data['customer_group_id']."',
						name='".$data['name']."',
						phone_number='".$data['phone_number']."',
						email='".$data['email']."',
						address='".$data['address']."',
						credit_limit='".$data['credit_limit']."',
						settlement_days='".$data['settlement_days']."',
						remarks='".$data['remarks']."',
						card_no='".$data['card_no']."',
						status='".$data['status']."'

						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
    
    public function edit($data) {
		
		global $db;

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						customer_group_id='".$data['customer_group_id']."',
						name='".$data['name']."',
						phone_number='".$data['phone_number']."',
						email='".$data['email']."',
						address='".$data['address']."',
						credit_limit='".$data['credit_limit']."',
						settlement_days='".$data['settlement_days']."',
						remarks='".$data['remarks']."',
						card_no='".$data['card_no']."',
						status='".$data['status']."'
						
						WHERE
						
						customer_id = ".$data['customer_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
	
	
	
	////////////// FINAL
	
	
    
    public function transactionGets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM customer_transactions ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	
	/////
	public function transactionAdd($customerData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($customerData['added_date']);
		
		$sql = "INSERT INTO customer_transactions SET
		
												`customer_id`='".$customerData['customer_id']."',
												`reference_id`='".$customerData['reference_id']."',
												`added_date`='".$addedDate."',
												`transaction_type`='".$customerData['transaction_type']."',
												`debit`='".$customerData['debit']."',
												`credit`='".$customerData['credit']."',
												`remarks`='".$customerData['remarks']."'
		
			";
		
		$db->query($sql);
		
		
		$this->transactionsBalanceUpdate($customerData['customer_id']);
		
	}
	
	/////
	public function transactionEdit($customerData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($customerData['added_date']);
		
		$sql = "UPDATE customer_transactions SET
		
												`added_date`='".$addedDate."',
												`debit`='".$customerData['debit']."',
												`credit`='".$customerData['credit']."',
												`remarks`='".$customerData['remarks']."'
												
												WHERE
												
												`reference_id`='".$customerData['reference_id']."'
												
												AND
												
												`transaction_type`='".$customerData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		
		$row = $db->fetch("SELECT * FROM customer_transactions WHERE `reference_id`='".$customerData['reference_id']."' AND `transaction_type`='".$customerData['transaction_type']."'");
		
		$this->transactionsBalanceUpdate($row['customer_id']);
	}
	
	/////
	public function transactionDelete($customerData)
	{
		global $db;
		global $dateCls;
		
		
		
		
		$row = $db->fetch("SELECT * FROM customer_transactions WHERE `reference_id`='".$customerData['reference_id']."' AND `transaction_type`='".$customerData['transaction_type']."'");
		
		$itemId = $row['item_id'];
		
		$sql = "DELETE FROM customer_transactions 
												
												WHERE
												
												`reference_id`='".$customerData['reference_id']."'
												
												AND
												
												`transaction_type`='".$customerData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		$this->transactionsBalanceUpdate($itemId);
	}
	
	
	public function transactionsBalanceUpdate($customerId)
	{
	
		global $db;
		
		
		$balanceAll = 0;
		$res = $db->fetchAll("SELECT * FROM customer_transactions WHERE customer_id=".$customerId." ORDER BY added_date, transaction_id ASC");
		
		foreach($res as $row) {
			
			$balanceAll += $row['debit'];
			$balanceAll -= $row['credit'];
			
			$db->query("UPDATE customer_transactions SET balance='".$balanceAll."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
		}
		
		
		$db->query("UPDATE customers_customers SET closing_balance='".$balanceAll."' WHERE customer_id=".$customerId."");
	}
	
	
	////Loyalty
	
	
	
	
	/////
	public function loyaltyTransactionAdd($loyaltyData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($loyaltyData['added_date']);
		
		$sql = "INSERT INTO customer_loyalty_transactions SET
		
												`customer_id`='".$loyaltyData['customer_id']."',
												`reference_id`='".$loyaltyData['reference_id']."',
												`added_date`='".$addedDate."',
												`transaction_type`='".$loyaltyData['transaction_type']."',
												`debit`='".$loyaltyData['debit']."',
												`credit`='".$loyaltyData['credit']."',
												`remarks`='".$loyaltyData['remarks']."'
		
			";
		
		$db->query($sql);
		
		
		$this->loyaltyTransactionsBalanceUpdate($loyaltyData['customer_id']);
		
	}
	
	
	public function loyaltyTransactionsBalanceUpdate($customerId)
	{
	
		global $db;
		
		
		$balanceAll = 0;
		$res = $db->fetchAll("SELECT * FROM customer_loyalty_transactions WHERE customer_id=".$customerId." ORDER BY added_date, transaction_id ASC");
		
		foreach($res as $row) {
			
			$balanceAll += $row['debit'];
			$balanceAll -= $row['credit'];
			
			$db->query("UPDATE customer_loyalty_transactions SET balance='".$balanceAll."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
		}
		
		
		$db->query("UPDATE customers_customers SET loyalty_points='".$balanceAll."' WHERE customer_id=".$customerId."");
	}
}

// Instantiate the blogsModels class
$CustomersMasterCustomersQuery = new CustomersMasterCustomersQuery;
?>