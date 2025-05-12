<?php

class CustomersMasterCustomergroupsQuery {
	
	private $tableName="customers_groups";
    
    public function get($customergroupId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE customer_group_id='".$customergroupId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($customergroupId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE customer_group_id='".$customergroupId."'");
		
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($customergroupId)
	{
		global $db;
		$res = $db->fetchAll("SELECT customer_group_id FROM ".$this->tableName." WHERE customer_group_id='".$customergroupId."'");
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
						status='".$data['status']."'
						
						WHERE
						
						customer_group_id = ".$data['customer_group_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
	
    
    public function delete($customerGroupId) {
		
        global $db;

		$error = [];
		$err = 0;
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM customers_customers WHERE customer_group_id = '".$customerGroupId."'");
if($count['count'] > 0){ $error[] = "This customer group cannot be deleted as it is currently used in customers!"; $err++; }

$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_items_customer_group_price WHERE customer_group_id = '".$customerGroupId."'");
if($count['count'] > 0){ $error[] = "This customer group cannot be deleted as it is currently used in inventory items customer group price!"; $err++; }



		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE customer_group_id = ".$customerGroupId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
			
			
    }
}

// Instantiate the blogsModels class
$CustomersMasterCustomergroupsQuery = new CustomersMasterCustomergroupsQuery;
?>