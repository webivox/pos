<?php


class SalesTransactionGiftcardsQuery {
	
	private $tableName="sales_gift_cards";
    
    public function get($gcId) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE gift_card_id='".$gcId."'");
		
		return !empty($row) ? $row : [];
    }
    
    public function gets($sql = '') {
		
		global $db;

		$row = $db->fetchAll("SELECT * FROM ".$this->tableName." ".$sql);
		
		return !empty($row) ? $row : [];
    }
	
	public function data($gcId,$column)
	{
		global $db;
		
		$res = $db->fetch("SELECT ".$column." FROM ".$this->tableName." WHERE gift_card_id='".$gcId."'");
		$count = count($res);
		if($count)
		{
			$row = $res;
			return $row[$column];
		}
		else{ return false; }
	}
	
	public function has($gcId)
	{
		global $db;
		$res = $db->fetchAll("SELECT gift_card_id FROM ".$this->tableName." WHERE gift_card_id='".$gcId."'");
		$count = count($res);
		return $count;
	}
	
	public function validate($no,$addedDate) {
		
        global $db;

        // Query to fetch all blogs
        $row = $db->fetch("SELECT * FROM ".$this->tableName." WHERE no='".$no."' AND expiry_date>='".$addedDate."'");
		
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
		global $dateCls;
		
		$added_date = $dateCls->todayDate('Y-m-d');
		$expiry_date = $dateCls->dateToDB($data['expiry_date']);

        // Query to fetch all blogs
        $sql = "INSERT INTO ".$this->tableName." SET 
						
						added_date='".$added_date."',
						no='".$data['no']."',
						expiry_date='".$expiry_date."',
						amount='".$data['amount']."',
						used_amount='0',
						balance_amount='".$data['amount']."'
						
				";
						
        if($db->query($sql))
		{
			return $db->last_id();
		}
		else{ return false; }
		
		
    }
    
    public function addUsedAmount($giftCardId,$usedAmount) {
		
		global $db;
		global $dateCls;
		global $defCls;
		
		$db->query("UPDATE ".$this->tableName." SET used_amount = used_amount+".$usedAmount.", balance_amount = balance_amount-".$usedAmount." WHERE gift_card_id = '".$giftCardId."'");
		
	}
    
    public function removeUsedAmount($giftCardId,$usedAmount) {
		
		global $db;
		global $dateCls;
		global $defCls;
		
		$db->query("UPDATE ".$this->tableName." SET used_value = used_value-".$usedAmount.", balance_value = balance_value+".$usedAmount." WHERE gift_card_id = '".$giftCardId."'");
		
	}
}

// Instantiate the blogsModels class
$SalesTransactionGiftcardsQuery = new SalesTransactionGiftcardsQuery;
?>