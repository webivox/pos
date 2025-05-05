<?php

class SalesLoyaltyQuery{
	
	private $tableName="customer_loyalty_transactions";
    
    public function get($customerId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE customer_id='".$customerId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
}

// Instantiate the blogsModels class
$SalesLoyaltyQuery = new SalesLoyaltyQuery;
?>