<?php

class AccountsMasterAccountsQuery {
	
	private $tableName="accounts_accounts";
    
    public function get($accountId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE account_id='".$accountId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($accountId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE account_id='".$accountId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($accountId)
	{
		global $db;
		$res = $db->fetchAll("SELECT account_id FROM ".$this->tableName." WHERE account_id='".$accountId."'");
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
						
						name='".$data['name']."',
						payment_method='".$data['payment_method']."',
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
						
						name='".$data['name']."',
						payment_method='".$data['payment_method']."',
						status='".$data['status']."'
						
						WHERE
						
						account_id = ".$data['account_id']."
						
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

		$row = $db->fetchAll("SELECT * FROM account_transactions ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	
	/////
	public function transactionAdd($customerData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($customerData['added_date']);
		
		$sql = "INSERT INTO account_transactions SET
		
												`account_id`='".$customerData['account_id']."',
												`reference_id`='".$customerData['reference_id']."',
												`added_date`='".$addedDate."',
												`transaction_type`='".$customerData['transaction_type']."',
												`debit`='".$customerData['debit']."',
												`credit`='".$customerData['credit']."',
												`remarks`='".$customerData['remarks']."'
		
			";
		
		$db->query($sql);
		
		
		$this->transactionsBalanceUpdate($customerData['account_id']);
		
	}
	
	/////
	public function transactionEdit($customerData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($customerData['added_date']);
		
		$sql = "UPDATE account_transactions SET
		
												`account_id`='".$customerData['account_id']."',
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
		
		
		$row = $db->fetch("SELECT * FROM account_transactions WHERE `reference_id`='".$customerData['reference_id']."' AND `transaction_type`='".$customerData['transaction_type']."'");
		
		$this->transactionsBalanceUpdate($row['account_id']);
	}
	
	/////
	public function transactionDelete($referenceId,$transactionType)
	{
		global $db;
		global $dateCls;
		
		
		
		
		$row = $db->fetch("SELECT * FROM account_transactions WHERE `reference_id`='".$referenceId."' AND `transaction_type`='".$transactionType."'");
		
		if($row)
		{
		
			$sql = "DELETE FROM account_transactions 
													
													WHERE
													
													`reference_id`='".$referenceId."'
													
													AND
													
													`transaction_type`='".$transactionType."'
													
													
													
				";
			
			$db->query($sql);
			
			$this->transactionsBalanceUpdate($row['account_id']);
		}
	}
	
	
	public function transactionsBalanceUpdate($accountId)
	{
	
		global $db;
		
		
		$balanceAll = 0;
		$res = $db->fetchAll("SELECT * FROM account_transactions WHERE account_id=".$accountId." ORDER BY added_date, transaction_id ASC");
		
		foreach($res as $row) {
			
			$balanceAll += $row['debit'];
			$balanceAll -= $row['credit'];
			
			$db->query("UPDATE account_transactions SET balance='".$balanceAll."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
		}
		
		
		$db->query("UPDATE accounts_accounts SET closing_balance='".$balanceAll."' WHERE account_id=".$accountId."");
	}
}

// Instantiate the blogsModels class
$AccountsMasterAccountsQuery = new AccountsMasterAccountsQuery;
?>