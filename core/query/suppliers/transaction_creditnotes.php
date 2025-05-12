<?php
class SuppliersTransactionsCreditnotesQuery {
	
	private $tableName="suppliers_credit_notes";
	private $itemTableName="suppliers_credit_note_items";
    
    public function get($creditnoteId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE credit_note_id='".$creditnoteId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($creditnoteId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE credit_note_id='".$creditnoteId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($creditnoteId)
	{
		global $db;
		$res = $db->fetchAll("SELECT credit_note_id FROM ".$this->tableName." WHERE credit_note_id='".$creditnoteId."'");
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
						
						supplier_id='".$data['supplier_id']."',
						location_id='".$data['location_id']."',
						user_id='".$data['user_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
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

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						supplier_id='".$data['supplier_id']."',
						location_id='".$data['location_id']."',
						supplier_id='".$data['supplier_id']."',
						added_date='".$addedDate."',
						amount='".$data['amount']."',
						details='".$data['details']."'
						
						WHERE
						
						credit_note_id = ".$data['credit_note_id']."
						
				";
						
        if($db->query($sql))
		{
			return true;
		}
		else{ return false; }
		
		
    }
	
	
	
	
    
    public function delete($creditNoteId) {
		
        global $db;
        global $defCls;
        global $SuppliersTransactionsCreditnotesQuery;
        global $SuppliersMasterSuppliersQuery;

		$error = [];
		$err = 0;
		
		$creditNoteInfo = $SuppliersTransactionsCreditnotesQuery->get($creditNoteId);
		
		if($creditNoteInfo)
		{
			////Supplier transactipn update
			$supplierData = [];
			$supplierData['reference_id'] = $creditNoteInfo['credit_note_id'];
			$supplierData['transaction_type'] = 'SCN';
			
			$SuppliersMasterSuppliersQuery->transactionDelete($supplierData);
			
			$db->query("DELETE FROM ".$this->tableName." WHERE credit_note_id = ".$creditNoteInfo['credit_note_id']."");
			
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
$SuppliersTransactionsCreditnotesQuery = new SuppliersTransactionsCreditnotesQuery;
?>