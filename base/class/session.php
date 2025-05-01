<?php
class session
{
	
	private $prefix=_SESSIONPREFIX;
	
	public function load($field)
	{
		
		global $db;
		
		if(isset($_SESSION[$this->prefix.$field]))
		{
			return $db->escape($_SESSION[$this->prefix.$field]);			
		}
		else
		{
			return false;
		}
	}
	
	public function set($field,$value)
	{
		$_SESSION[$this->prefix.$field] = $value;
	}
	
	public function destroy($field)
	{
		unset($_SESSION[$this->prefix.$field]);
	}
}
$sessionCls=new session;
?>