<?php

class SalesTransactionsQuotationsQuery {
	
	private $tableName="inventory_quotations";
	private $itemTableName="inventory_quotation_items";
    
    public function get($quotationId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE quotation_id='".$quotationId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($quotationId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE quotation_id='".$quotationId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($quotationId)
	{
		global $db;
		$res = $db->fetchAll("SELECT quotation_id FROM ".$this->tableName." WHERE quotation_id='".$quotationId."'");
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
		
		$addedDate = $dateCls->dateToDB($data['added_date']);
		
        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						customer_id='".$data['customer_id']."',
						location_id='".$data['location_id']."',
						user_id='".$data['user_id']."',
						added_date='".$addedDate."',
						remarks='".$data['remarks']."'
						
				";
						
        if($db->query($sql))
		{
			
			$lastId = $db->last_id();
			
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`quotation_id` = '".$lastId."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`amount` = '".$i['amount']."',
										`discount` = '".$i['discount']."',
										`final_amount` = '".$i['finalAmount']."',
										`total` = '".$i['total']."'
						
				";
				
				 if($db->query($sqlItem))
				 {
					 $lastLineId = $db->last_id();
					 
					 
					 
				 }
				
			}	
			
			return $lastId;
		}
		else{ return false; }
		
		
    }
    
    public function edit($data) {
		
		global $db;
		global $dateCls;
		global $defCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						customer_id='".$data['customer_id']."',
						location_id='".$data['location_id']."',
						added_date='".$addedDate."',
						remarks='".$data['remarks']."'
						
						WHERE
						
						quotation_id = ".$data['quotation_id']."
						
				";
						
        if($db->query($sql))
		{
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`quotation_id` = '".$data['quotation_id']."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`amount` = '".$i['amount']."',
										`discount` = '".$i['discount']."',
										`final_amount` = '".$i['finalAmount']."',
										`total` = '".$i['total']."'
						
				";
				
				if($db->query($sqlItem))
				 {
					 $lastLineId = $db->last_id();
					 
					
					 
					 
					 
				 }
				
			}
			
			
			foreach($data['eitems'] as $i)
			{
				
				if($i['qty'])
				{
				
					$sqlItem = "UPDATE ".$this->itemTableName." SET 
							
											`qty` = '".$i['qty']."',
											`amount` = '".$i['amount']."',
											`total` = '".$i['total']."'
											
											WHERE
											
											`quotation_item_id` = '".$i['quotation_item_id']."'
							
					";
					
					
				
					if($db->query($sqlItem))
					{
						
					
					
					}
				}
				else
				{
				
					$db->query("DELETE FROM ".$this->itemTableName." WHERE `quotation_item_id` = '".$i['quotation_item_id']."'");
					
				
				}
				
				
			}
		}
		else{ return false; }
		
		
    }
   
    public function runTotal($quotationId) {
		
		
        global $db;
		global $SalesTransactionsQuotationsQuery;
		
		$quotationInfo = $SalesTransactionsQuotationsQuery->get($quotationId);
		$items = $SalesTransactionsQuotationsQuery->getItems("WHERE quotation_id='".$quotationId."'");
		
		$total_amount = 0;
		$total_qty = 0;
		$total_no_items = 0;
		
		foreach($items as $item)
		{
			
			//`cost`, `master_price`, `price`, `discount`, `unit_price`, `qty`, `total`
			$amount = $item['amount'];
			$discount = $item['discount'];
			$qty = $item['qty'];
			
			if($discount){ $disc = $amount*$discount/100; }
			else{ $disc = 0; }
			
			$final_amount = $amount-$disc;
			$total = $final_amount*$qty;
			
			$db->query("UPDATE ".$this->itemTableName." SET final_amount = '".$final_amount."', total='".$total."' WHERE quotation_item_id = '".$item['quotation_item_id']."'");
			
			$total_amount += $total;
			$total_no_items += 1;
			$total_qty += $qty;
			
		}
		
		
		$db->query("UPDATE ".$this->tableName." SET no_of_items = '".$total_no_items."', no_of_qty='".$total_qty."', total='".$total_amount."' WHERE quotation_id = '".$quotationId."'");
	}
    
	
	
	
    
    public function delete($quotationId) {
		
     	global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SalesTransactionsQuotationsQuery;
		global $InventoryMasterItemsQuery;
		global $stockTransactionsCls;

		$error = [];
		$err = 0;
		
		$quotationInfo = $SalesTransactionsQuotationsQuery->get($quotationId);
		
		if($quotationInfo)
		{
			$data['item_lists'] = $SalesTransactionsQuotationsQuery->getItems("WHERE quotation_id='".$quotationId."' ORDER BY quotation_item_id ASC");
			
			
			$db->query("DELETE FROM ".$this->itemTableName." WHERE `quotation_id` = '".$quotationInfo['quotation_id']."'");
			$db->query("DELETE FROM ".$this->tableName." WHERE quotation_id = ".$quotationInfo['quotation_id']."");
			
			
			
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
$SalesTransactionsQuotationsQuery = new SalesTransactionsQuotationsQuery;
?>