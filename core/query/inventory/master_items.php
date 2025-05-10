<?php
class InventoryMasterItemsQuery {
	
	private $tableName="inventory_items";
    
    public function get($itemId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE item_id='".$itemId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($itemId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE item_id='".$itemId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($itemId)
	{
		global $db;
		$res = $db->fetchAll("SELECT item_id FROM ".$this->tableName." WHERE item_id='".$itemId."'");
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
						
						category_id='".$data['category_id']."',
						brand_id='".$data['brand_id']."',
						unit_id='".$data['unit_id']."',
						supplier_id='".$data['supplier_id']."',
						name='".$data['name']."',
						description='".$data['description']."',
						barcode='".$data['barcode']."',
						barcode_name='".$data['barcode_name']."',
						selling_price='".$data['selling_price']."',
						minimum_selling_price='".$data['minimum_selling_price']."',
						cost='".$data['cost']."',
						re_order_qty='".$data['re_order_qty']."',
						order_qty='".$data['order_qty']."',
						minimum_qty='".$data['minimum_qty']."',
						unique_no='".$data['unique_no']."',
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
						
						category_id='".$data['category_id']."',
						brand_id='".$data['brand_id']."',
						unit_id='".$data['unit_id']."',
						supplier_id='".$data['supplier_id']."',
						name='".$data['name']."',
						description='".$data['description']."',
						barcode='".$data['barcode']."',
						barcode_name='".$data['barcode_name']."',
						selling_price='".$data['selling_price']."',
						minimum_selling_price='".$data['minimum_selling_price']."',
						cost='".$data['cost']."',
						re_order_qty='".$data['re_order_qty']."',
						order_qty='".$data['order_qty']."',
						minimum_qty='".$data['minimum_qty']."',
						unique_no='".$data['unique_no']."',
						status='".$data['status']."'
						
						WHERE
						
						item_id = ".$data['item_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
	
	
	
	
	public function getCustomerGroupPrice($groupId,$itemId)
	{
		global $db;
		global $InventoryMasterItemsQuery;
		
		$row = $db->fetch("SELECT * FROM inventory_items_customer_group_price WHERE customer_group_id='".$groupId."' AND item_id='".$itemId."'");
	
		if($row)
		{
			
			if($row['price']>0){ return $row['price']; }
			else{ return $InventoryMasterItemsQuery->data($itemId,'selling_price'); }
			
		}
		else
		{
			return $InventoryMasterItemsQuery->data($itemId,'selling_price');
		}
	}
	
	
	public function editCustomerGroupPrice($data)
	{
		global $db;
		global $InventoryMasterItemsQuery;
		
		foreach($data['customer_group_list'] as $cgl)
		{
		
			$row = $db->fetch("SELECT * FROM inventory_items_customer_group_price WHERE customer_group_id='".$cgl['customer_group_id']."' AND item_id='".$cgl['item_id']."'");
		
			if($row)
			{
				
				$db->query("UPDATE inventory_items_customer_group_price SET price = '".$cgl['price']."' WHERE iicgp_id = '".$row['iicgp_id']."'");
				
			}
			else
			{
				$db->query("INSERT INTO inventory_items_customer_group_price SET customer_group_id='".$cgl['customer_group_id']."', item_id='".$cgl['item_id']."', price = '".$cgl['price']."'");
				
			}
		
		}
	}
	
	
}

// Instantiate the blogsModels class
$InventoryMasterItemsQuery = new InventoryMasterItemsQuery;
?>