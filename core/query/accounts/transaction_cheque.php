<?php

class AccountsTransactionChequeQuery {
	
	private $tableName="accounts_cheque_transactions";
    
    public function get($chequeId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE cheque_id='".$chequeId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function getByTrn($transactionId, $transactionType) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE transaction_type='".$transactionType."' AND reference_id='".$transactionId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($chequeId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE cheque_id='".$chequeId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($chequeId)
	{
		global $db;
		$res = $db->fetchAll("SELECT cheque_id FROM ".$this->tableName." WHERE cheque_id='".$chequeId."'");
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
						
					reference_id='".$data['reference_id']."',
					added_date='".$data['added_date']."',
					transaction_type='".$data['transaction_type']."',
					type='".$data['type']."',
					bank_code='".$data['bank_code']."',
					cheque_date='".$data['cheque_date']."',
					cheque_no='".$data['cheque_no']."',
					amount='".$data['amount']."',
					remarks='".$data['remarks']."',
					deposited_account_id='".$data['deposited_account_id']."',
					status='".$data['status']."'
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
    
    public function update($data) {
		
		global $db;

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						added_date='".$data['added_date']."',
						type='".$data['type']."',
						bank_code='".$data['bank_code']."',
						cheque_date='".$data['cheque_date']."',
						cheque_no='".$data['cheque_no']."',
						amount='".$data['amount']."',
						deposited_account_id='".$data['deposited_account_id']."'
						
						WHERE
						
						reference_id = ".$data['reference_id']." AND transaction_type='".$data['transaction_type']."'
						
				";
				
				
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
	
    }
    
    public function delete($referenceId,$transactionType) {
		
		global $db;
		
        // Query to fetch all blogs
        $sql = "DELETE FROM ".$this->tableName." WHERE reference_id = ".$referenceId." AND transaction_type='".$transactionType."'";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
		
	}
    
    public function chequeStatusUpdate($data) {
		
		global $db;
		global $dateCls;

		$realized_date = $dateCls->dateToDB($data['realized_date']);
        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						realized_date='".$realized_date."',
						deposited_account_id='".$data['accountId']."',
						status=1
						
						WHERE
						
						cheque_id = ".$data['cheque_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
	
    }
    
    public function chequeRevert($id) {
		
		global $db;
		
		$row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE `cheque_id`='".$id."'");

		if($row['type']=='Issued'){ $deposited_account_id = $row['deposited_account_id']; }
		else{ $deposited_account_id = 0; }
		
        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						realized_date='',
						deposited_account_id='".$deposited_account_id."',
						status=0
						
						WHERE
						
						cheque_id = ".$row['cheque_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
	
    }
    
    public function chequeRemove($chequeData) {
		
		global $db;
		
		$row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE `reference_id`='".$chequeData['reference_id']."' AND `transaction_type`='".$chequeData['transaction_type']."' AND `type`='".$chequeData['type']."'");
		
		
		if($row)
		{
		
			// Query to fetch all blogs
			$sql = "DELETE FROM ".$this->tableName." 
							
							WHERE
							
							cheque_id = ".$row['cheque_id']."
							
					";
							
			if($db->query($sql))
			{
				return true;
			}
			else{ return false; }
		}
		else{ return false; }
    }
	
}

// Instantiate the blogsModels class
$AccountsTransactionChequeQuery = new AccountsTransactionChequeQuery;
?>