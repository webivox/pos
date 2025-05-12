<?php

class AccountsTransactionsAdjustmentsQuery {
	
	private $tableName="accounts_adjustments";
    
    public function get($adjustmentId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE adjustment_id='".$adjustmentId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($adjustmentId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE adjustment_id='".$adjustmentId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($adjustmentId)
	{
		global $db;
		$res = $db->fetchAll("SELECT adjustment_id FROM ".$this->tableName." WHERE adjustment_id='".$adjustmentId."'");
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
    
    public function create($data) {
		
		global $db;
		global $dateCls;
		global $defCls;
		global $stockTransactionsCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);
		
        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						type='".$data['type']."',
						account_id='".$data['account_id']."',
						location_id='".$data['location_id']."',
						user_id='".$data['user_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						details='".$data['details']."',
						is_other_income='".$data['is_other_income']."'
						
				";
						
        if($db->query($sql))
		{
			
			$lastId = $db->last_id();
			
			return $lastId;
		}
		else{ return false; }
		
		
    }
    
    public function edit($data) {
		
		global $db;
		global $dateCls;
		global $defCls;
		global $stockTransactionsCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						type='".$data['type']."',
						account_id='".$data['account_id']."',
						location_id='".$data['location_id']."',
						account_id='".$data['account_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						details='".$data['details']."',
						is_other_income='".$data['is_other_income']."'
						
						WHERE
						
						adjustment_id = ".$data['adjustment_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
		
		
    }
	
	
    
    public function delete($adjustmentId) {
		
        global $db;
        global $defCls;
        global $AccountsTransactionsAdjustmentsQuery;
        global $AccountsTransactionChequeQuery;
        global $AccountsMasterAccountsQuery;

		$error = [];
		$err = 0;
		
		$adjustmentInfo = $AccountsTransactionsAdjustmentsQuery->get($adjustmentId);
		
		if($adjustmentInfo)
		{
			$transaction_no = $defCls->docNo('AADJ-',$adjustmentInfo['adjustment_id']);
				
			$AccountsMasterAccountsQuery->transactionDelete($adjustmentInfo['adjustment_id'],'AADJ');
			
			
			$db->query("DELETE FROM ".$this->tableName." WHERE adjustment_id = ".$adjustmentInfo['adjustment_id']."");
			
			return 'deleted';
			
		
		}
		else{ $error[] = "Invalid id!"; $err++; }
		
       	

		if($err)
		{
			return $error;
		}
			
    }
}

// Instantiate the blogsModels class
$AccountsTransactionsAdjustmentsQuery = new AccountsTransactionsAdjustmentsQuery;
?>