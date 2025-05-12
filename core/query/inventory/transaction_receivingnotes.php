<?php

class  InventoryTransactionsReceivingnotesQuery{
	
	private $tableName="inventory_receiving_notes";
	private $itemTableName="inventory_receiving_note_items";
    
    public function get($receivingnoteId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE receiving_note_id='".$receivingnoteId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($receivingnoteId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE receiving_note_id='".$receivingnoteId."'");
	
		if($res)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($receivingnoteId)
	{
		global $db;
		$res = $db->fetchAll("SELECT receiving_note_id FROM ".$this->tableName." WHERE receiving_note_id='".$receivingnoteId."'");
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
    
    public function getItem($receivingnoteItemId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->itemTableName." WHERE receiving_note_item_id='".$receivingnoteItemId."'");
		
		return !empty($row) ? $row : [];
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
		
		if($data['due_date']){ $due_date = $dateCls->dateToDB($data['due_date']); }
		else{ $due_date = ''; }
		
        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						po_id='".$data['po_id']."',
						location_id='".$data['location_id']."',
						supplier_id='".$data['supplier_id']."',
						user_id='".$data['user_id']."',
						added_date='".$addedDate."',
						invoice_no='".$data['invoice_no']."',
						due_date='".$due_date."',
						remarks='".$data['remarks']."'
						
				";
						
        if($db->query($sql))
		{
			
			$lastId = $db->last_id();
			
			
			
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`receiving_note_id` = '".$lastId."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`price` = '".$i['amount']."',
										`buying_price` = '".$i['buyingPrice']."',
										`discount` = '".$i['discount']."',
										`final_price` = '".$i['finalPrice']."',
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
					 $stockData['transaction_type'] = 'RN';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = $i['finalPrice'];
					 $stockData['qty_in'] = $i['qty'];
					 $stockData['qty_out'] = 0;
					 $stockData['remarks'] = $defCls->docNo('RN-',$lastId);
					 
					 $stockTransactionsCls->add($stockData);
					 
					 
					 
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
		global $stockTransactionsCls;
		
		$addedDate = $dateCls->dateToDB($data['added_date']);
		
		if($data['due_date']){ $due_date = $dateCls->dateToDB($data['due_date']); }
		else{ $due_date = ''; }

        // Query to fetch all blogs
        $sql = "UPDATE ".$this->tableName." SET 
						
						po_id='".$data['po_id']."',
						location_id='".$data['location_id']."',
						supplier_id='".$data['supplier_id']."',
						added_date='".$addedDate."',
						invoice_no='".$data['invoice_no']."',
						due_date='".$due_date."',
						remarks='".$data['remarks']."'
						
						WHERE
						
						receiving_note_id = ".$data['receiving_note_id']."
						
				";
						
        if($db->query($sql))
		{
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`receiving_note_id` = '".$data['receiving_note_id']."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."',
										`price` = '".$i['amount']."',
										`buying_price` = '".$i['buyingPrice']."',
										`discount` = '".$i['discount']."',
										`final_price` = '".$i['finalPrice']."',
										`total` = '".$i['total']."'
						
				";
				
				if($db->query($sqlItem))
				{
					
					$lastLineId = $db->last_id();
					
					//// UPDATE STOCKS
					
					$stockData = [];
					
					$stockData['item_id'] = $i['itemId'];
					$stockData['location_id'] = $data['location_id'];
					$stockData['reference_id'] = $data['receiving_note_id'];
					$stockData['reference_row_id'] = $lastLineId;
					$stockData['transaction_type'] = 'RN';
					$stockData['added_date'] = $addedDate;
					$stockData['amount'] = $i['finalPrice'];
					$stockData['qty_in'] = $i['qty'];
					$stockData['qty_out'] = 0;
					$stockData['remarks'] = $defCls->docNo('RN-',$data['receiving_note_id']);
					
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
											`buying_price` = '".$i['buyingPrice']."',
											`discount` = '".$i['discount']."',
											`final_price` = '".$i['finalPrice']."',
											`total` = '".$i['total']."'
											
											WHERE
											
											`receiving_note_item_id` = '".$i['receiving_note_item_id']."'
							
					";
				
					if($db->query($sqlItem))
					{
						
						//// UPDATE STOCKS
						
						$stockData = [];
						
						$stockData['location_id'] = $data['location_id'];
						$stockData['reference_id'] = $data['receiving_note_id'];
						$stockData['reference_row_id'] = $i['receiving_note_item_id'];
						$stockData['transaction_type'] = 'RN';
						$stockData['added_date'] = $addedDate;
						$stockData['amount'] = $i['finalPrice'];
						$stockData['qty_in'] = $i['qty'];
						$stockData['qty_out'] = 0;
						$stockData['remarks'] = $defCls->docNo('RN-',$data['receiving_note_id']);
						
						$stockTransactionsCls->edit($stockData);
					
					
					
					}
					
				}
				else
				{
				
					$db->query("DELETE FROM ".$this->itemTableName." WHERE `receiving_note_item_id` = '".$i['receiving_note_item_id']."'");
					
					
					//// UPDATE STOCKS
					
					$stockData = [];
					
					$stockData['reference_id'] = $data['receiving_note_id'];
					$stockData['reference_row_id'] = $i['receiving_note_item_id'];
					$stockData['transaction_type'] = 'RN';
					
					$stockTransactionsCls->delete($stockData);
					
				}
				
				
			}
		}
		else{ return false; }
		
		
    }
    
    public function updateTotals($rnId) {
		
		global $db;
		global $SuppliersMasterSuppliersQuery;
		
		$total_value = 0;
		$total_saving = 0;
		$no_of_items = 0;
		$no_of_qty = 0;
		
		$res = $db->fetchAll("SELECT * FROM ".$this->itemTableName." WHERE `receiving_note_id` = '".$rnId."'");
		foreach($res as $row)
		{
			$discountCal = $row['buying_price']*$row['discount']/100;
			$finalPrice = $row['buying_price']-$discountCal;
			$totalLine = $finalPrice*$row['qty'];
			
			$total_value += $totalLine;
			$total_saving += ($row['price']-$row['buying_price'])+$discountCal;
			$no_of_items += 1;
			$no_of_qty += $row['qty'];
			
		}
		
		$db->query("UPDATE ".$this->tableName." SET 
						
						total_value='".$total_value."',
						total_saving='".$total_saving."',
						no_of_items='".$no_of_items."',
						no_of_qty='".$no_of_qty."'
						
						WHERE
						
						receiving_note_id = ".$rnId."
						
					");	
		
	}
	
	
	
	
    
    public function delete($receivingNoteId) {
		
     	global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryTransactionsReceivingnotesQuery;
		global $InventoryMasterItemsQuery;
		global $stockTransactionsCls;

		$error = [];
		$err = 0;
		
		$getReceivingnoteInfo = $InventoryTransactionsReceivingnotesQuery->get($receivingNoteId);
		
		if($getReceivingnoteInfo)
		{
			$data['item_lists'] = $InventoryTransactionsReceivingnotesQuery->getItems("WHERE receiving_note_id='".$receivingNoteId."' ORDER BY receiving_note_item_id ASC");
			
			foreach($data['item_lists'] as $i){
				
				$db->query("DELETE FROM ".$this->itemTableName." WHERE `receiving_note_item_id` = '".$i['receiving_note_item_id']."'");
					
				//// UPDATE STOCKS
				
				$stockData = [];
				
				$stockData['reference_id'] = $getReceivingnoteInfo['receiving_note_id'];
				$stockData['reference_row_id'] = $i['receiving_note_item_id'];
				$stockData['transaction_type'] = 'RN';
				
				$stockTransactionsCls->delete($stockData);
				
			}
			
			$db->query("DELETE FROM ".$this->tableName." WHERE receiving_note_id = ".$getReceivingnoteInfo['receiving_note_id']."");
			
			////Supplier transactipn update
			$supplierData = [];
			$supplierData['reference_id'] = $getReceivingnoteInfo['receiving_note_id'];
			$supplierData['transaction_type'] = 'RN';
			
			$SuppliersMasterSuppliersQuery->transactionDelete($supplierData);
			
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
$InventoryTransactionsReceivingnotesQuery = new InventoryTransactionsReceivingnotesQuery;
?>