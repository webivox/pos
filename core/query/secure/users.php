<?php

class SecureMasterUsersQuery {
    
    
    public function get($user_id) {
		
        global $db;

		$row = $db->fetch("SELECT * FROM secure_users WHERE user_id= ?", [$user_id]);
		
		// Return $row if it's not empty, otherwise return an empty array
		return !empty($row) ? $row : [];

    }
	
    public function getUSerDataByUserPass($username, $password) {
		
        global $db;

		$row = $db->fetch("SELECT * FROM secure_users WHERE username= ? AND password=?", [$username, $password]);
		
		// Return $row if it's not empty, otherwise return an empty array
		return !empty($row) ? $row : [];

    }
}

// Instantiate the blogsModels class
$SecureMasterUsersQuery = new SecureMasterUsersQuery;

?>