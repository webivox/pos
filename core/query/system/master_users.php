<?php
class SystemMasterUsersQuery {
	
	private $tableName="secure_users";
    
    public function get($user_id) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($user_id,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($user_id)
	{
		global $db;
		$res = $db->fetchAll("SELECT user_id FROM ".$this->tableName." WHERE user_id='".$user_id."'");
		$count = count($res);
		return $count;
	}
	
	
    public function getUSerDataByUserPass($username, $password) {
		
        global $db;

		$row = $db->fetch("SELECT * FROM secure_users WHERE username= ? AND password=?", [$username, $password]);
		
		// Return $row if it's not empty, otherwise return an empty array
		return !empty($row) ? $row : [];

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
						
						group_id='".$data['group_id']."', 
						location_id='".$data['location_id']."', 
						name='".$data['name']."', 
						username='".$data['username']."', 
						password='".sha1($data['password'])."', 
						loginRedirectTo='".$data['loginRedirectTo']."', 
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
						
						group_id='".$data['group_id']."', 
						location_id='".$data['location_id']."', 
						name='".$data['name']."', 
						username='".$data['username']."',";
						
						if($data['password']){ $sql .= "password='".sha1($data['password'])."',"; }
						
	$sql .= "loginRedirectTo='".$data['loginRedirectTo']."', 
						status='".$data['status']."'
						
						WHERE
						
						user_id = ".$data['user_id']."
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
}

// Instantiate the blogsModels class
$SystemMasterUsersQuery = new SystemMasterUsersQuery;
?>