<?php
class InventoryMasterWarrantyQuery {
	
	private $tableName="inventory_warranty";
    
    public function get($warrantyId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE warranty_id='".$warrantyId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($warrantyId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE warranty_id='".$warrantyId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($warrantyId)
	{
		global $db;
		$res = $db->fetchAll("SELECT warranty_id FROM ".$this->tableName." WHERE warranty_id='".$warrantyId."'");
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
						
						warranty_id = ".$data['warranty_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
    
    public function delete($warrantyId) {
		
        global $db;

		$error = [];
		$err = 0;
		
       	$count = $db->fetch("SELECT COUNT(*) as count FROM inventory_items WHERE warranty_id = '".$warrantyId."'");
		if($count['count'] > 0){ $error[] = "This warranty cannot be deleted as it is currently used in inventory items!"; $err++; }


		if($err)
		{
			return $error;
		}
		else
		{
			$sql = "DELETE FROM ".$this->tableName." WHERE warranty_id = ".$warrantyId."";
						
			if($db->query($sql))
			{
				return 'deleted';
			}
			else{ return false; }
		}
			
    }
}

// Instantiate the blogsModels class
$InventoryMasterWarrantyQuery = new InventoryMasterWarrantyQuery;
?>