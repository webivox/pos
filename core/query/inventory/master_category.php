<?php
class InventoryMasterCategoryQuery {
	
	private $tableName="inventory_category";
    
    public function get($categoryId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE category_id='".$categoryId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($categoryId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE category_id='".$categoryId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($categoryId)
	{
		global $db;
		$res = $db->fetchAll("SELECT category_id FROM ".$this->tableName." WHERE category_id='".$categoryId."'");
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
						
						parent_category_id='".$data['parent_category_id']."',
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
						
						parent_category_id='".$data['parent_category_id']."',
						name='".$data['name']."',
						status='".$data['status']."'
						
						WHERE
						
						category_id = ".$data['category_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
    
    public function delete($categoryId) {
		
        global $db;

		$error = [];
		$err = 0;
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_items WHERE category_id = '".$categoryId."'");
		if($count['count'] > 0){ $error[] = "This category cannot be deleted as it is currently used in inventory items!"; $err++; }
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM ".$this->tableName." WHERE parent_category_id = '".$categoryId."'");
		if($count['count'] > 0){ $error[] = "This category cannot be deleted as it is currently used in inventory sub category!"; $err++; }


		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE category_id = ".$categoryId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
    }
}

// Instantiate the blogsModels class
$InventoryMasterCategoryQuery = new InventoryMasterCategoryQuery;
			
?>