<?php

class SuppliersMasterSuppliersQuery {
	
	private $tableName="suppliers_suppliers";
    
    public function get($supplierId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE supplier_id='".$supplierId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($supplierId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE supplier_id='".$supplierId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($supplierId)
	{
		global $db;
		$res = $db->fetchAll("SELECT supplier_id FROM ".$this->tableName." WHERE supplier_id='".$supplierId."'");
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
						contact_person='".$data['contact_person']."',
						phone_number='".$data['phone_number']."',
						email='".$data['email']."',
						address='".$data['address']."',
						city='".$data['city']."',
						state='".$data['state']."',
						country='".$data['country']."',
						payment_terms='".$data['payment_terms']."',
						bank_details='".$data['bank_details']."',
						tax_number='".$data['tax_number']."',
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
						contact_person='".$data['contact_person']."',
						phone_number='".$data['phone_number']."',
						email='".$data['email']."',
						address='".$data['address']."',
						city='".$data['city']."',
						state='".$data['state']."',
						country='".$data['country']."',
						payment_terms='".$data['payment_terms']."',
						bank_details='".$data['bank_details']."',
						tax_number='".$data['tax_number']."',
						status='".$data['status']."'
						
						WHERE
						
						supplier_id = ".$data['supplier_id']."
						
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

		$row = $db->fetchAll("SELECT * FROM supplier_transactions ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	
	/////
	public function transactionAdd($supplierData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($supplierData['added_date']);
		
		$sql = "INSERT INTO supplier_transactions SET
		
												`supplier_id`='".$supplierData['supplier_id']."',
												`reference_id`='".$supplierData['reference_id']."',
												`added_date`='".$addedDate."',
												`transaction_type`='".$supplierData['transaction_type']."',
												`debit`='".$supplierData['debit']."',
												`credit`='".$supplierData['credit']."',
												`remarks`='".$supplierData['remarks']."'
		
			";
		
		$db->query($sql);
		
		
		$this->transactionsBalanceUpdate($supplierData['supplier_id']);
		
	}
	
	/////
	public function transactionEdit($supplierData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($supplierData['added_date']);
		
		$sql = "UPDATE supplier_transactions SET
		
												`added_date`='".$addedDate."',
												`debit`='".$supplierData['debit']."',
												`credit`='".$supplierData['credit']."',
												`remarks`='".$supplierData['remarks']."'
												
												WHERE
												
												`reference_id`='".$supplierData['reference_id']."'
												
												AND
												
												`transaction_type`='".$supplierData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		
		$row = $db->fetch("SELECT * FROM supplier_transactions WHERE `reference_id`='".$supplierData['reference_id']."' AND `transaction_type`='".$supplierData['transaction_type']."'");
		
		$this->transactionsBalanceUpdate($row['supplier_id']);
	}
	
	/////
	public function transactionDelete($supplierData)
	{
		global $db;
		global $dateCls;
		
		
		
		
		$row = $db->fetch("SELECT * FROM supplier_transactions WHERE `reference_id`='".$supplierData['reference_id']."' AND `transaction_type`='".$supplierData['transaction_type']."'");
		
		$supplier_id = $row['supplier_id'];
		
		$sql = "DELETE FROM supplier_transactions 
												
												WHERE
												
												`reference_id`='".$supplierData['reference_id']."'
												
												AND
												
												`transaction_type`='".$supplierData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		$this->transactionsBalanceUpdate($supplier_id);
	}
	
	
	public function transactionsBalanceUpdate($supplierId)
	{
	
		global $db;
		
		
		$balanceAll = 0;
		$res = $db->fetchAll("SELECT * FROM supplier_transactions WHERE supplier_id=".$supplierId." ORDER BY added_date, transaction_id ASC");
		
		foreach($res as $row) {
			
			$balanceAll += $row['debit'];
			$balanceAll -= $row['credit'];
			
			$db->query("UPDATE supplier_transactions SET balance='".$balanceAll."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
		}
		
		
		$db->query("UPDATE suppliers_suppliers SET closing_balance='".$balanceAll."' WHERE supplier_id=".$supplierId."");
	}
	
	
	
	
    
    public function delete($supplierId) {
		
        global $db;

		$error = [];
		$err = 0;
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_items WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in inventory items!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_receiving_notes WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in inventory receiving notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_return_notes WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in inventory return notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_credit_notes WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in suppliers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_debit_notes WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in suppliers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_payments WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in suppliers payments!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM supplier_transactions WHERE supplier_id = '".$supplierId."'");
		if($count['count'] > 0){ $error[] = "This supplier cannot be deleted as it is currently used in supplier transactions!"; $err++; }


		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE supplier_id = ".$supplierId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
			
			
    }
}

// Instantiate the blogsModels class
$SuppliersMasterSuppliersQuery = new SuppliersMasterSuppliersQuery;
?>