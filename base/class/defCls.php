<?php
class defCls
{
	
	
	public function master($value)
	{
		global $db;
		$row = $db->fetch("SELECT * FROM master WHERE `key` = ?", [$value]);
		return $row['values'];
	}
	
	
	public function getMasterStatus($statsId)
	{
		if($statsId==0){ return 'Disabled'; }
		if($statsId==1){ return 'Enabled'; }
		
	}
	
	public function token()
	{
		global $ip;
		
		
		function generateRandomString($length = 15) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, strlen($characters) - 1)];
				}
			return $randomString;
		}
		return md5((generateRandomString().$ip->useIp()));
		
	}
	public function genURL($url)
	{
		return _SERVER.$url;
	}
	//
	
	public function docNo($type,$no)
	{
		return $type.str_pad($no, 5, '0', STR_PAD_LEFT);
	}
	
	public function showText($text)
	{
		return html_entity_decode($text);
	}
	
	public function num($number)
	{
		if($number)
		{
			return number_format($number,2, '.', '');
		}
		else{ return 0.00; }
	}
	
	public function money($money)
	{
		return number_format($money,2);
	}
	
	public function uppercase($text)
	{
		return strtoupper($text);
	}
	
	public function lowercase($text)
	{
		return strtolower($text);
	}
	
}
$defCls=new defCls;

?>