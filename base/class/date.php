<?php

class dateFunction
{
	public $configtimezone=_TIMEZONE;
	
	public function nowTime()
	{
		$timezone = new DateTimeZone($this->configtimezone);
		$date = new DateTime();
		$date->setTimezone($timezone );
		$todaydate=$date->format('d-m-Y');
		return $date->format( 'H:i:s A' );
	}
	
	public function nowTimeDb()
	{
		$timezone = new DateTimeZone($this->configtimezone);
		$date = new DateTime();
		$date->setTimezone($timezone );
		$todaydate=$date->format('d-m-Y');
		return $date->format( 'H:i:s' );
	}
	
	public function todayDate($dateformat)
	{
		$timezone = new DateTimeZone($this->configtimezone);
		$date = new DateTime();
		$date->setTimezone($timezone );
		return $date->format($dateformat);
	}
	
	public function nowDb()
	{
		$timezone = new DateTimeZone($this->configtimezone);
		$date = new DateTime();
		$date->setTimezone($timezone );
		$todaydate=$date->format('d-m-Y');
		return $date->format( 'Y-m-d H:i:s' );
	}
	
	public function showDate($dateValue)
	{
		if($dateValue)
		{
			if($dateValue!=='0000-00-00'){ return date('d-m-Y',strtotime($dateValue)); }
			else{ return ''; }
		}
		else{ return ''; }
	}
	
	
	public function dateToDB($dateValue)
	{
		if($dateValue)
		{
			if($dateValue!=='0000-00-00' && $dateValue){ return date('Y-m-d',strtotime($dateValue)); }
			else{ return ''; }
		}
		else{ return ''; }
	}
}
	
$dateCls=new dateFunction();

?>