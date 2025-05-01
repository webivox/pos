<div style="margin-left:200px; margin-top:200px;">

	<?php
	
		require_once _QUERY."system/master_locations.php";
	
		global $db;
		global $SystemMasterLocationsQuery;
		
		
		$itemId = 1;
		
		
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
			
			$stock_balance = json_encode($stock_balance);
			
			$db->query("UPDATE inventory_stock_transactions SET stock_balance_locations='".$stock_balance."' WHERE transaction_id='".$row['transaction_id']."'");
			
			
			$stocksAll += $row['qty_in'];
			$stocksAll -= $row['qty_out'];
		}
		
		
		$db->query("UPDATE inventory_items SET closing_stocks='".$stocksAll."' WHERE item_id='".$itemId."'");
		


	/*
	$resLoca = $db->fetchAll("SELECT * FROM system_locations ORDER BY location_id ASC");
	foreach($resLoca as $rowLoc)
	{
		
		$stocks[$rowLoc['location_id']] = 0;
	}
	
	
	$res = $db->fetchAll("SELECT * FROM inventory_stock_transactions WHERE item_id=1 ORDER BY added_date,transaction_id ASC");
	
	$stocks = 0;
	foreach($res as $row)
	{
		
		$stocks[$row['location_id']] += $row['qty_in'];
		$stocks[$row['location_id']] += -$row['qty_out'];
		
		$stocks += $row['qty_in'];
		$stocks += -$row['qty_out'];
		
		
		echo $row['transaction_id'].'--'.$row['item_id'].'--'.$row['reference_id'].'--'.$row['added_date'].'--'.$row['qty_in'].'--'.$row['qty_out'].'--'.$row['remarks'].'--'.$stocks.'--'.'<br><br>';
		
		
	}*/
?>
</div>