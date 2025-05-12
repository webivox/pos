<?php

class SuppliersTransactionsPaymentsQuery {
	
	private $tableName="suppliers_payments";
    
    public function get($paymentId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE payment_id='".$paymentId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($paymentId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE payment_id='".$paymentId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($paymentId)
	{
		global $db;
		$res = $db->fetchAll("SELECT payment_id FROM ".$this->tableName." WHERE payment_id='".$paymentId."'");
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
						
						supplier_id='".$data['supplier_id']."',
						location_id='".$data['location_id']."',
						user_id='".$data['user_id']."',
						account_id='".$data['account_id']."',
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
						
						supplier_id='".$data['supplier_id']."',
						location_id='".$data['location_id']."',
						account_id='".$data['account_id']."',
						supplier_id='".$data['supplier_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						cheque_no='".$data['cheque_no']."',
						cheque_date='".$chequeDate."',
						details='".$data['details']."'
						
						WHERE
						
						payment_id = ".$data['payment_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
		
		
    }
	
	
	
	
    
    public function delete($paymentId) {
		
        global $db;
        global $defCls;
        global $SuppliersTransactionsPaymentsQuery;
        global $AccountsTransactionChequeQuery;
        global $AccountsMasterAccountsQuery;
        global $SuppliersMasterSuppliersQuery;

		$error = [];
		$err = 0;
		
		$paymentInfo = $SuppliersTransactionsPaymentsQuery->get($paymentId);
		
		if($paymentInfo)
		{
			$transaction_no = $defCls->docNo('SPMNT-',$paymentInfo['payment_id']);
			
			$chequeInfo = $AccountsTransactionChequeQuery->getByTrn($paymentInfo['payment_id'],'SPMNT');
			
			if($chequeInfo && $chequeInfo['status']!==0)
			{
				$error[] = "Cheque already realized. Please Revert your cheque!"; $err++;
			}
			
			if(!$err)
			{
				
				$AccountsTransactionChequeQuery->delete($paymentInfo['payment_id'],'SPMNT');
				$AccountsMasterAccountsQuery->transactionDelete($paymentInfo['payment_id'],'SPMNT');
				
				////Supplier transactipn update
				$supplierData = [];
				$supplierData['reference_id'] = $paymentInfo['payment_id'];
				$supplierData['transaction_type'] = 'SPMNT';
				
				$SuppliersMasterSuppliersQuery->transactionDelete($supplierData);
				
				$db->query("DELETE FROM ".$this->tableName." WHERE payment_id = ".$paymentInfo['payment_id']."");
				
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
$SuppliersTransactionsPaymentsQuery = new SuppliersTransactionsPaymentsQuery;
?>