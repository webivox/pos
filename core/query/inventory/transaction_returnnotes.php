<?php

class InventoryTransactionsReturnnotesQuery {
	
	private $tableName="inventory_return_notes";
	private $itemTableName="inventory_return_note_items";
    
    public function get($returnnoteId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE return_note_id='".$returnnoteId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($returnnoteId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE return_note_id='".$returnnoteId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($returnnoteId)
	{
		global $db;
		$res = $db->fetchAll("SELECT return_note_id FROM ".$this->tableName." WHERE return_note_id='".$returnnoteId."'");
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
		global $stockTransactionsCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);
		
        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						location_id='".$data['location_id']."',
						supplier_id='".$data['supplier_id']."',
						user_id='".$data['user_id']."',
						rn_no='".$data['rn_no']."',
						added_date='".$addedDate."',
						remarks='".$data['remarks']."'
						
				";
						
        if($db->query($sql))
		{
			
			$lastId = $db->last_id();
			
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`return_note_id` = '".$lastId."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`price` = '".$i['amount']."',
										`total` = '".$i['total']."'
						
				";
				
				 if($db->query($sqlItem))
				 {
					 $lastLineId = $db->last_id();
					 
					 //// UPDATE STOCKS
					 
					 $stockData = [];
					 
					 $stockData['item_id'] = $i['itemId'];
					 $stockData['location_id'] = $data['location_id'];
					 $stockData['reference_id'] = $lastId;
					 $stockData['reference_row_id'] = $lastLineId;
					 $stockData['transaction_type'] = 'RETN';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = $i['amount'];
					 $stockData['qty_in'] = 0;
					 $stockData['qty_out'] = $i['qty'];
					 $stockData['remarks'] = $defCls->docNo('RETN-',$lastId);
					 
					 $stockTransactionsCls->add($stockData);
					 
					 
					 
				 };
				
			}	
			
			return $lastId;
		}
		else{ return false; }
		
		
    }
    
    public function edit($data) {
		
		global $db;
		global $dateCls;
		global $defCls;
		global $stockTransactionsCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						location_id='".$data['location_id']."',
						supplier_id='".$data['supplier_id']."',
						rn_no='".$data['rn_no']."',
						added_date='".$addedDate."',
						remarks='".$data['remarks']."'
						
						WHERE
						
						return_note_id = ".$data['return_note_id']."
						
				";
						
        if($db->query($sql))
		{
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`return_note_id` = '".$data['return_note_id']."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`price` = '".$i['amount']."',
										`total` = '".$i['total']."'
						
				";
				
				if($db->query($sqlItem))
				{
					
					$lastLineId = $db->last_id();
					
					//// UPDATE STOCKS
					
					$stockData = [];
					
					$stockData['item_id'] = $i['itemId'];
					$stockData['location_id'] = $data['location_id'];
					$stockData['reference_id'] = $data['return_note_id'];
					$stockData['reference_row_id'] = $lastLineId;
					$stockData['transaction_type'] = 'RETN';
					$stockData['added_date'] = $addedDate;
					$stockData['amount'] = $i['amount'];
					$stockData['qty_in'] = 0;
					$stockData['qty_out'] = $i['qty'];
					$stockData['remarks'] = $defCls->docNo('RETN-',$data['return_note_id']);
					
					$stockTransactionsCls->add($stockData);
				
				
				
				}
				
			}
			
			
			foreach($data['eitems'] as $i)
			{
				
				if($i['qty'])
				{
				
					$sqlItem = "UPDATE ".$this->itemTableName." SET 
							
											`qty` = '".$i['qty']."',
											`price` = '".$i['amount']."',
											`total` = '".$i['total']."'
											
											WHERE
											
											`return_note_item_id` = '".$i['return_note_item_id']."'
							
					";
				
					if($db->query($sqlItem))
					{
						
						//// UPDATE STOCKS
						
						$stockData = [];
						
						$stockData['location_id'] = $data['location_id'];
						$stockData['reference_id'] = $data['return_note_id'];
						$stockData['reference_row_id'] = $i['return_note_item_id'];
						$stockData['transaction_type'] = 'RETN';
						$stockData['added_date'] = $addedDate;
						$stockData['amount'] = $i['amount'];
						$stockData['qty_in'] = 0;
						$stockData['qty_out'] = $i['qty'];
						$stockData['remarks'] = $defCls->docNo('RETN-',$data['return_note_id']);
						
						$stockTransactionsCls->edit($stockData);
					
					
					
					}
				}
				else
				{
				
					$db->query("DELETE FROM ".$this->itemTableName." WHERE `return_note_item_id` = '".$i['return_note_item_id']."'");
					
					
					//// UPDATE STOCKS
					
					$stockData = [];
					
					$stockData['reference_id'] = $data['return_note_id'];
					$stockData['reference_row_id'] = $i['return_note_item_id'];
					$stockData['transaction_type'] = 'RETN';
					
					$stockTransactionsCls->delete($stockData);
					
				}
				
				
			}
		}
		else{ return false; }
		
		
    }
    
    public function updateTotals($rnId) {
		
		global $db;
		
		$total_value = 0;
		$no_of_items = 0;
		$no_of_qty = 0;
		
		$res = $db->fetchAll("SELECT * FROM ".$this->itemTableName." WHERE `return_note_id` = '".$rnId."'");
		foreach($res as $row)
		{
			
			$total_value += $row['price']*$row['qty'];;
			$no_of_items += 1;
			$no_of_qty += $row['qty'];
			
		}
		
		$db->query("UPDATE ".$this->tableName." SET 
						
						total_value='".$total_value."',
						no_of_items='".$no_of_items."',
						no_of_qty='".$no_of_qty."'
						
						WHERE
						
						return_note_id = ".$rnId."
						
					");
						
		
	}
}

// Instantiate the blogsModels class
$InventoryTransactionsReturnnotesQuery = new InventoryTransactionsReturnnotesQuery;
?>