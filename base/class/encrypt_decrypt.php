<?php
class encDecr
{
	private $enc_url=array
		(
			'k00'=>'a',
			'Crh'=>'b',
			'2ah'=>'c',
			'ved'=>'d',
			'tbm'=>'e',
			'UuQ:'=>'f',
			'bsC'=>'g',
			'jsx'=>'h',
			'cdj'=>'i',
			'xsr'=>'j',
			'lnm'=>'k',
			'wi3'=>'l',
			'gra'=>'m',
			'mat'=>'n',
			'cia'=>'o',
			'fer'=>'p',
			'asa'=>'q',
			'jvr'=>'r',
			'loa'=>'s',
			'5ds'=>'t',
			'vpa'=>'u',
			'tqv'=>'v',
			'sml'=>'w',
			'pqa'=>'x',
			'xls'=>'z',
			'sla'=>'/',
		
		);
	
	
	public function enc($value)
	{
		
		$arr=str_split($value);
		
		$encripted='';
		foreach($arr as $a)
		{
			$encripted.=array_search($a, $this->enc_url);
		}
		
		return $encripted;
		
	}
	
	public function dec($value)
	{

		$valuespl=str_split($value, 3);
	
		$descript = '';
		
		foreach($valuespl as $g)
		{
			if(array_key_exists($g,$this->enc_url))
			{
				$descript.=$this->enc_url[$g];
			}
		}
		
		return $descript;
		
	}
	
}
$encDec=new encDecr;

?>