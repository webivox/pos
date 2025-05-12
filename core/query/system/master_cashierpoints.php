<?php

class SystemMasterCashierpointsQuery {
	
	private $tableName="system_cashierpoints";
    
    public function get($cashierpointId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE cashierpoint_id='".$cashierpointId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($cashierpointId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE cashierpoint_id='".$cashierpointId."'");
		
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($cashierpointId)
	{
		global $db;
		$res = $db->fetchAll("SELECT cashierpoint_id FROM ".$this->tableName." WHERE cashierpoint_id='".$cashierpointId."'");
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
						
						location_id='".$data['location_id']."',
						name='".$data['name']."',
						cash_account_id='".$data['cash_account_id']."',
						transfer_account_id='".$data['transfer_account_id']."',
						card_account_1_name='".$data['card_account_1_name']."',
						card_account_1_id='".$data['card_account_1_id']."',
						card_account_2_name='".$data['card_account_2_name']."',
						card_account_2_id='".$data['card_account_2_id']."',
						card_account_3_name='".$data['card_account_3_name']."',
						card_account_3_id='".$data['card_account_3_id']."',
						card_account_4_name='".$data['card_account_4_name']."',
						card_account_4_id='".$data['card_account_4_id']."',
						card_account_5_name='".$data['card_account_5_name']."',
						card_account_5_id='".$data['card_account_5_id']."',
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
						
						location_id='".$data['location_id']."',
						name='".$data['name']."',
						cash_account_id='".$data['cash_account_id']."',
						transfer_account_id='".$data['transfer_account_id']."',
						card_account_1_name='".$data['card_account_1_name']."',
						card_account_1_id='".$data['card_account_1_id']."',
						card_account_2_name='".$data['card_account_2_name']."',
						card_account_2_id='".$data['card_account_2_id']."',
						card_account_3_name='".$data['card_account_3_name']."',
						card_account_3_id='".$data['card_account_3_id']."',
						card_account_4_name='".$data['card_account_4_name']."',
						card_account_4_id='".$data['card_account_4_id']."',
						card_account_5_name='".$data['card_account_5_name']."',
						card_account_5_id='".$data['card_account_5_id']."',
						status='".$data['status']."'

						
						WHERE
						
						cashierpoint_id = ".$data['cashierpoint_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
    
    public function delete($cashierPointId) {
		
        global $db;

		$error = [];
		$err = 0;
		
        $count = $db->fetch("SELECT COUNT(*) as count FROM accounts_adjustments WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in accounts adjustments!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_expences WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in accounts expences!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_transfers WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in accounts transfers!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_credit_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in customers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_debit_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in customers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_settlements WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in customers settlements!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_adjustment_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in inventory adjustment notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_quotations WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in inventory quotations!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_receiving_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in inventory receiving notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_return_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in inventory return notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_transfer_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in inventory transfer notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_invoices WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in sales invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_pending_invoices WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in sales pending invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_return WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in sales return!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_shifts WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in sales shifts!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_credit_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in suppliers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_debit_notes WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in suppliers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_payments WHERE cashier_point_id = '".$cashierPointId."'");
		if($count['count'] > 0){ $error[] = "This cashier point cannot be deleted as it is currently used in suppliers payments!"; $err++; }



		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE cashierpoint_id = ".$cashierPointId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
			
			
    }
}

// Instantiate the blogsModels class
$SystemMasterCashierpointsQuery = new SystemMasterCashierpointsQuery;
?>