<?php

class InventoryTransactionsTransfernotesQuery {
	
	private $tableName="inventory_transfer_notes";
	private $itemTableName="inventory_transfer_note_items";
    
    public function get($transfernoteId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE transfer_note_id='".$transfernoteId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($transfernoteId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE transfer_note_id='".$transfernoteId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($transfernoteId)
	{
		global $db;
		$res = $db->fetchAll("SELECT transfer_note_id FROM ".$this->tableName." WHERE transfer_note_id='".$transfernoteId."'");
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
						
						location_from_id='".$data['location_from_id']."',
						location_to_id='".$data['location_to_id']."',
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
						
										`transfer_note_id` = '".$lastId."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."'
						
				";
				 if($db->query($sqlItem))
				 {
					 $lastLineId = $db->last_id();
					 
					 //// UPDATE STOCKS
					 
					 $stockData = [];
					 
					 $stockData['item_id'] = $i['itemId'];
					 $stockData['location_id'] = $data['location_from_id'];
					 $stockData['reference_id'] = $lastId;
					 $stockData['reference_row_id'] = $lastLineId;
					 $stockData['transaction_type'] = 'TRNOUT';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = 0;
					 $stockData['qty_in'] = 0;
					 $stockData['qty_out'] = $i['qty'];
					 $stockData['remarks'] = $defCls->docNo('TRN-',$lastId);
					 
					 $stockTransactionsCls->add($stockData);
					 
					 $stockData = [];
					 
					 $stockData['item_id'] = $i['itemId'];
					 $stockData['location_id'] = $data['location_to_id'];
					 $stockData['reference_id'] = $lastId;
					 $stockData['reference_row_id'] = $lastLineId;
					 $stockData['transaction_type'] = 'TRNIN';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = 0;
					 $stockData['qty_in'] = $i['qty'];
					 $stockData['qty_out'] = 0;
					 $stockData['remarks'] = $defCls->docNo('TRN-',$lastId);
					 
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
						
						location_from_id='".$data['location_from_id']."',
						location_to_id='".$data['location_to_id']."',
						added_date='".$addedDate."',
						remarks='".$data['remarks']."'
						
						WHERE
						
						transfer_note_id = ".$data['transfer_note_id']."
						
				";
						
        if($db->query($sql))
		{
			foreach($data['items'] as $i)
			{
				
				$sqlItem = "INSERT INTO ".$this->itemTableName." SET 
						
										`transfer_note_id` = '".$data['transfer_note_id']."',
										`item_id` = '".$i['itemId']."',
										`qty` = '".$i['qty']."'
						
				";
				
				
				 if($db->query($sqlItem))
				 {
					 $lastLineId = $db->last_id();
					 
					 //// UPDATE STOCKS
					 
					 $stockData = [];
					 
					 $stockData['item_id'] = $i['itemId'];
					 $stockData['location_id'] = $data['location_from_id'];
					 $stockData['reference_id'] = $data['transfer_note_id'];
					 $stockData['reference_row_id'] = $lastLineId;
					 $stockData['transaction_type'] = 'TRNOUT';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = 0;
					 $stockData['qty_in'] = 0;
					 $stockData['qty_out'] = $i['qty'];
					 $stockData['remarks'] = $defCls->docNo('TRN-',$data['transfer_note_id']);
					 
					 $stockTransactionsCls->add($stockData);
					 
					 $stockData = [];
					 
					 $stockData['item_id'] = $i['itemId'];
					 $stockData['location_id'] = $data['location_to_id'];
					 $stockData['reference_id'] = $data['transfer_note_id'];
					 $stockData['reference_row_id'] = $lastLineId;
					 $stockData['transaction_type'] = 'TRNIN';
					 $stockData['added_date'] = $addedDate;
					 $stockData['amount'] = 0;
					 $stockData['qty_in'] = $i['qty'];
					 $stockData['qty_out'] = 0;
					 $stockData['remarks'] = $defCls->docNo('TRN-',$data['transfer_note_id']);
					 
					 $stockTransactionsCls->add($stockData);
					 
					 
					 
				 };
				
			}
			
			
			foreach($data['eitems'] as $i)
			{
				
				if($i['qty'])
				{
				
					$sqlItem = "UPDATE ".$this->itemTableName." SET 
							
											`qty` = '".$i['qty']."'
											
											WHERE
											
											`transfer_note_item_id` = '".$i['transfer_note_item_id']."'
							
					";
				
					if($db->query($sqlItem))
					{
						
						//// UPDATE STOCKS
						
						$stockData = [];
						
						$stockData['location_id'] = $data['location_from_id'];
						$stockData['reference_id'] = $data['transfer_note_id'];
						$stockData['reference_row_id'] = $i['transfer_note_item_id'];
						$stockData['transaction_type'] = 'TRNOUT';
						$stockData['added_date'] = $addedDate;
						$stockData['amount'] = 0;
						$stockData['qty_in'] = 0;
						$stockData['qty_out'] = $i['qty'];
						$stockData['remarks'] = $defCls->docNo('TRN-',$data['transfer_note_id']);
						
						$stockTransactionsCls->edit($stockData);
						
						$stockData = [];
						
						$stockData['location_id'] = $data['location_to_id'];
						$stockData['reference_id'] = $data['transfer_note_id'];
						$stockData['reference_row_id'] = $i['transfer_note_item_id'];
						$stockData['transaction_type'] = 'TRNIN';
						$stockData['added_date'] = $addedDate;
						$stockData['amount'] = 0;
						$stockData['qty_in'] = $i['qty'];
						$stockData['qty_out'] = 0;
						$stockData['remarks'] = $defCls->docNo('TRN-',$data['transfer_note_id']);
						
						$stockTransactionsCls->edit($stockData);
					
					
					
					}
				}
				else
				{
				
					$db->query("DELETE FROM ".$this->itemTableName." WHERE `transfer_note_item_id` = '".$i['transfer_note_item_id']."'");
					
					
					//// UPDATE STOCKS
					
					$stockData = [];
					
					$stockData['reference_id'] = $data['transfer_note_id'];
					$stockData['reference_row_id'] = $i['transfer_note_item_id'];
					$stockData['transaction_type'] = 'TRNOUT';
					
					$stockTransactionsCls->delete($stockData);
					
					$stockData = [];
					
					$stockData['reference_id'] = $data['transfer_note_id'];
					$stockData['reference_row_id'] = $i['transfer_note_item_id'];
					$stockData['transaction_type'] = 'TRNIN';
					
					$stockTransactionsCls->delete($stockData);
				}
				
				
			}
		}
		else{ return false; }
		
		
    }
    
    public function updateTotals($transferId) {
		
		global $db;
		
		$total_value = 0;
		$no_of_items = 0;
		$no_of_qty = 0;
		
		$res = $db->fetchAll("SELECT * FROM ".$this->itemTableName." WHERE `transfer_note_id` = '".$transferId."'");
		foreach($res as $row)
		{
			$no_of_items += 1;
			$no_of_qty += $row['qty'];
			
		}
		
		$db->query("UPDATE ".$this->tableName." SET 
						
						no_of_items='".$no_of_items."',
						no_of_qty='".$no_of_qty."'
						
						WHERE
						
						transfer_note_id = ".$transferId."
						
					");
						
		
	}
	
	
	
    
    public function delete($transferNoteId) {
		
     	global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $InventoryTransactionsTransfernotesQuery;
		global $InventoryMasterItemsQuery;
		global $stockTransactionsCls;

		$error = [];
		$err = 0;
		
		$getTransferNoteInfo = $InventoryTransactionsTransfernotesQuery->get($transferNoteId);
		
		if($getTransferNoteInfo)
		{
			$data['item_lists'] = $InventoryTransactionsTransfernotesQuery->getItems("WHERE transfer_note_id='".$transferNoteId."' ORDER BY transfer_note_item_id ASC");
			
			foreach($data['item_lists'] as $i){
				
				$db->query("DELETE FROM ".$this->itemTableName." WHERE `transfer_note_item_id` = '".$i['transfer_note_item_id']."'");
					
				//// UPDATE STOCKS
				
				$stockData = [];
				
				$stockData['reference_id'] = $getTransferNoteInfo['transfer_note_id'];
				$stockData['reference_row_id'] = $i['transfer_note_item_id'];
				$stockData['transaction_type'] = 'TRN';
				
				$stockTransactionsCls->delete($stockData);
				
			}
			
			$db->query("DELETE FROM ".$this->tableName." WHERE transfer_note_id = ".$getTransferNoteInfo['transfer_note_id']."");
			
			
			
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
$InventoryTransactionsTransfernotesQuery = new InventoryTransactionsTransfernotesQuery;
?>