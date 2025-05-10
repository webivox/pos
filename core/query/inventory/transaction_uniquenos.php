<?php

class InventoryTransactionUniquenosQuery {
	
	private $tableName="inventory_unique_nos";
    
    public function get($uniqueId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE unique_id='".$uniqueId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
    
    public function getByUniqueNo($uniqueNo) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE unique_no='".$uniqueNo."'");
		
		return !empty($row) ? $row : [];
    }
	
	public function data($uniqueId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE unique_id='".$uniqueId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($uniqueId)
	{
		global $db;
		$res = $db->fetchAll("SELECT unique_id FROM ".$this->tableName." WHERE unique_id='".$uniqueId."'");
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
		global $dateCls;
		
		
		$dateToday = $dateCls->todayDate('Y-m-d');

        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						added_date='".$dateToday."',
						item_id='".$data['item_id']."',
						unique_no='".$data['unique_no']."',
						remarks='".$data['remarks']."',
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
						
						item_id='".$data['item_id']."',
						unique_no='".$data['unique_no']."',
						remarks='".$data['remarks']."',
						status='".$data['status']."'
						
						WHERE
						
						unique_id = ".$data['unique_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
}

// Instantiate the blogsModels class
$InventoryTransactionUniquenosQuery = new InventoryTransactionUniquenosQuery;
?>