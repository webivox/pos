<?php
class SystemMasterUsersQuery {
	
	private $tableName="secure_users";
    
    public function get($user_id) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($user_id,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($user_id)
	{
		global $db;
		$res = $db->fetchAll("SELECT user_id FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		$count = count($res);
		return $count;
	}
	
	
    public function getUSerDataByUser($username) {
		
        global $db;

		$row = $db->fetch("SELECT * FROM secure_users WHERE username= ?", [$username]);
		
		// Return $row if it's not empty, otherwise return an empty array
		return !empty($row) ? $row : [];

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
		
		$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						group_id='".$data['group_id']."', 
						location_id='".$data['location_id']."', 
						name='".$data['name']."', 
						username='".$data['username']."', 
						password='".$hashed_password."', 
						loginRedirectTo='".$data['loginRedirectTo']."', 
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
		
		$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						group_id='".$data['group_id']."', 
						location_id='".$data['location_id']."', 
						name='".$data['name']."', 
						username='".$data['username']."',";
						
						if($data['password']){ $sql .= "password='".$hashed_password."',"; }
						
	$sql .= "loginRedirectTo='".$data['loginRedirectTo']."', 
						status='".$data['status']."'
						
						WHERE
						
						user_id = ".$data['user_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
	
    
    public function delete($userId) {
		
        global $db;

		$error = [];
		$err = 0;
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_adjustments WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in accounts adjustments!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_expences WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in accounts expences!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM accounts_transfers WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in accounts transfers!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_credit_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in customers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_debit_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in customers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM customers_settlements WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in customers settlements!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_adjustment_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in inventory adjustment notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_quotations WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in inventory quotations!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_receiving_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in inventory receiving notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_return_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in inventory return notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_transfer_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in inventory transfer notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_invoices WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in sales invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_pending_invoices WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in sales pending invoices!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_return WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in sales return!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM sales_shifts WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in sales shifts!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_credit_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in suppliers credit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_debit_notes WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in suppliers debit notes!"; $err++; }
		
		$count = $db->fetch("SELECT COUNT(*) as count FROM suppliers_payments WHERE user_id = '".$userId."'");
		if($count['count'] > 0){ $error[] = "This user cannot be deleted as it is currently used in suppliers payments!"; $err++; }
		



		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE user_id = ".$userId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
			
			
    }
}

// Instantiate the blogsModels class
$SystemMasterUsersQuery = new SystemMasterUsersQuery;
?>