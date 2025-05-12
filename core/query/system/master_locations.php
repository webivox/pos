<?php
class SystemMasterLocationsQuery {
	
	private $tableName="system_locations";
    
    public function get($locationId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE location_id='".$locationId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($locationId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE location_id='".$locationId."'");
		
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($locationId)
	{
		global $db;
		$res = $db->fetchAll("SELECT location_id FROM ".$this->tableName." WHERE location_id='".$locationId."'");
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
						address='".$data['address']."', 
						phone_number='".$data['phone_number']."', 
						email='".$data['email']."', 
						invoice_no_start='".$data['invoice_no_start']."', 
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
						address='".$data['address']."', 
						phone_number='".$data['phone_number']."', 
						email='".$data['email']."', 
						invoice_no_start='".$data['invoice_no_start']."', 
						status='".$data['status']."'
						
						WHERE
						
						location_id = ".$data['location_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
    
    public function delete($locationId) {
		
        global $db;

		$error = [];
		$err = 0;
		
        $count = $db->fetch("SELECT COUNT(*) as count FROM inventory_receiving_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in inventory receiving notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_adjustment_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in inventory adjustment notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_quotations WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in sales quotations!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_return_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in inventory return notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_stock_transactions WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in inventory stock transactions (location from id)!"; $err++; }
	
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_transfer_notes WHERE location_from_id = '".$locationId."' OR location_to_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in inventory transfer notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_expences WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in accounts expences!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_transfers WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in accounts transfers!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_adjustments WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in accounts adjustments!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_credit_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in customers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_debit_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in customers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_settlements WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in customers settlements!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_invoices WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in sales invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_pending_invoices WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in sales pending invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_return WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in sales return!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM secure_users WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in secure users!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_credit_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in suppliers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_debit_notes WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in suppliers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_payments WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in suppliers payments!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM system_cashierpoints WHERE location_id = '".$locationId."'");
		if($count['count'] > 0){ $error[] = "This location cannot be deleted as it is currently used in system cashier points!".$count; $err++; }


		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE location_id = ".$locationId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
			
			
    }
}

// Instantiate the blogsModels class
$SystemMasterLocationsQuery = new SystemMasterLocationsQuery;
?>