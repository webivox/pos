<?php

class AccountsTransactionsExpencesQuery {
	
	private $tableName="accounts_expences";
    
    public function get($expenceId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE expence_id='".$expenceId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($expenceId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE expence_id='".$expenceId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($expenceId)
	{
		global $db;
		$res = $db->fetchAll("SELECT expence_id FROM ".$this->tableName." WHERE expence_id='".$expenceId."'");
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
		$chequeDate = $dateCls->dateToDB($data['cheque_date']);
		
        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						payee_id='".$data['payee_id']."',
						expences_type_id='".$data['expences_type_id']."',
						account_id='".$data['account_id']."',
						location_id='".$data['location_id']."',
						user_id='".$data['user_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						cheque_no='".$data['cheque_no']."',
						cheque_date='".$chequeDate."',
						details='".$data['details']."'
						
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
		$chequeDate = $dateCls->dateToDB($data['cheque_date']);

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						payee_id='".$data['payee_id']."',
						expences_type_id='".$data['expences_type_id']."',
						account_id='".$data['account_id']."',
						location_id='".$data['location_id']."',
						account_id='".$data['account_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						cheque_no='".$data['cheque_no']."',
						cheque_date='".$chequeDate."',
						details='".$data['details']."'
						
						WHERE
						
						expence_id = ".$data['expence_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
		
		
    }
	
	
    
    public function delete($expenceId) {
		
        global $db;
        global $defCls;
        global $AccountsTransactionsExpencesQuery;
        global $AccountsTransactionChequeQuery;
        global $AccountsMasterAccountsQuery;

		$error = [];
		$err = 0;
		
		$expenceInfo = $AccountsTransactionsExpencesQuery->get($expenceId);
		
		if($expenceInfo)
		{
			$transaction_no = $defCls->docNo('AEXP-',$expenceInfo['expence_id']);
			
			$chequeInfo = $AccountsTransactionChequeQuery->getByTrn($expenceInfo['expence_id'],'AEXP');
			
			if($chequeInfo && $chequeInfo['status']!==0)
			{
				$error[] = "Cheque already realized. Please Revert your cheque!"; $err++;
			}
			
			if(!$err)
			{
				
				$AccountsTransactionChequeQuery->delete($expenceInfo['expence_id'],'AEXP');
				$AccountsMasterAccountsQuery->transactionDelete($expenceInfo['expence_id'],'AEXP');
				
				
				$db->query("DELETE FROM ".$this->tableName." WHERE expence_id = ".$expenceInfo['expence_id']."");
				
				return 'deleted';
			}
			
		
		}
		else{ $error[] = "Invalid id!"; $err++; }
		
       	

		if($err)
		{
			return $error;
		}
			
    }
}

// Instantiate the blogsModels class
$AccountsTransactionsExpencesQuery = new AccountsTransactionsExpencesQuery;
?>