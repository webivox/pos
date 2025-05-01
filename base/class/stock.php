<?php
class stocktransactions
{
	
    
    public function transactionGets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM inventory_stock_transactions ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	/////
	public function add($stockData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($stockData['added_date']);
		
		$sql = "INSERT INTO inventory_stock_transactions SET
		
												`item_id`='".$stockData['item_id']."',
												`location_id`='".$stockData['location_id']."',
												`reference_id`='".$stockData['reference_id']."',
												`reference_row_id`='".$stockData['reference_row_id']."',
												`transaction_type`='".$stockData['transaction_type']."',
												`added_date`='".$addedDate."',
												`amount`='".$stockData['amount']."',
												`qty_in`='".$stockData['qty_in']."',
												`qty_out`='".$stockData['qty_out']."',
												`remarks`='".$stockData['remarks']."'
												
			";
		
		$db->query($sql);
		
		
		
		$this->stockUpdate($stockData['item_id']);
		
	}
	
	/////
	public function edit($stockData)
	{
		global $db;
		global $dateCls;
		
		$addedDate = $dateCls->dateToDB($stockData['added_date']);
		
		$sql = "UPDATE inventory_stock_transactions SET
		
												`location_id`='".$stockData['location_id']."',
												`added_date`='".$addedDate."',
												`amount`='".$stockData['amount']."',
												`qty_in`='".$stockData['qty_in']."',
												`qty_out`='".$stockData['qty_out']."',
												`remarks`='".$stockData['remarks']."'
												
												WHERE
												
												`reference_id`='".$stockData['reference_id']."'
												
												AND
												
												`reference_row_id`='".$stockData['reference_row_id']."'
												
												AND
												
												`transaction_type`='".$stockData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		
		$row = $db->fetch("SELECT * FROM inventory_stock_transactions WHERE `reference_id`='".$stockData['reference_id']."' AND `reference_row_id`='".$stockData['reference_row_id']."' AND `transaction_type`='".$stockData['transaction_type']."'");
		
		$this->stockUpdate($row['item_id']);
	}
	
	/////
	public function delete($stockData)
	{
		global $db;
		global $dateCls;
		
		
		
		
		$row = $db->fetch("SELECT * FROM inventory_stock_transactions WHERE `reference_id`='".$stockData['reference_id']."' AND `reference_row_id`='".$stockData['reference_row_id']."' AND `transaction_type`='".$stockData['transaction_type']."'");
		
		$itemId = $row['item_id'];
		
		$sql = "DELETE FROM inventory_stock_transactions 
												
												WHERE
												
												`reference_id`='".$stockData['reference_id']."'
												
												AND
												
												`reference_row_id`='".$stockData['reference_row_id']."'
												
												AND
												
												`transaction_type`='".$stockData['transaction_type']."'
												
												
												
			";
		
		$db->query($sql);
		
		$this->stockUpdate($itemId);
	}
	
	
	public function stockUpdate($itemId)
	{
	
		global $db;
		
		
		
		$resLoca = $db->fetchAll("SELECT * FROM system_locations ORDER BY location_id ASC");
		$stocks = [];
		foreach($resLoca as $rowLoc) {
			$stocks[$rowLoc['location_id']] = 0; 
		}
		
		$stocksAll = 0;
		$res = $db->fetchAll("SELECT * FROM inventory_stock_transactions WHERE item_id=".$itemId." ORDER BY added_date, transaction_id ASC");
		
		foreach($res as $row) {
			
			$finalStock = $row['stock_balance_locations'];
			$stock_balance = json_decode($finalStock,true);
			
			
			$stocks[$row['location_id']] += $row['qty_in'];
			$stocks[$row['location_id']] -= $row['qty_out'];
			
			$stock_balance[$row['location_id']] = $stocks[$row['location_id']];
			
			$stock_balance = json_encode($stock_balance);
			
			$db->query("UPDATE inventory_stock_transactions SET stock_balance_locations='".$stock_balance."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
			$stocksAll += $row['qty_in'];
			$stocksAll -= $row['qty_out'];
			
			
			if($row['transaction_type']!=='TRNIN' || $row['transaction_type']!=='TRNOUT')
			{
				
				$db->query("UPDATE inventory_stock_transactions SET stock_balance='".$stocksAll."' WHERE transaction_id='".$row['transaction_id']."'");
				
			}
			
			
		}
		
		
		$db->query("UPDATE inventory_items SET closing_stocks='".$stocksAll."' WHERE item_id='".$itemId."'");
	}
	
	
}
$stockTransactionsCls=new stocktransactions;

?>