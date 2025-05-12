<?php

// Parse URL for routing
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['secure'];

$dir = isset($url[0]) ? $url[0] : 'secure';
$file = isset($url[1]) ? $url[1] : 'signin';


$connector = isset($dir) && isset($file) ? ucfirst($dir) . str_replace(' ', '', ucwords(str_replace('_', ' ', $file))) . 'Connector' : 'SecureSigninConnector';

$method = isset($url[2]) ? $url[2] : 'index';
$id = isset($url[3]) ? $url[3] : '0';

$connectorPath = _CONNECTOR . $dir . "/" . $file .".php";

/////////////
$path_dir_file = $db->escape($dir . "/" . $file);
$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
$userGroupInfo = $SystemMasterUsergroupsQuery->get($userInfo['group_id']);

$rowsugp_row = $db->fetch("SELECT * FROM secure_users_group_paths WHERE path = '".$path_dir_file."'");
$permisssions = json_decode($userGroupInfo['permissions'],true);

$targetPath = $rowsugp_row['path_id'];

$matched = array_values(array_filter($permisssions, function($item) use ($targetPath) {
	return $item['path'] == $targetPath;
}));
				
$permissions = $matched[0]['permission'];

$accessArray = array('index','load','autoComplete','printView','view','returnprint' ,'posprint', 'shiftStart', 'shiftEnd', 'suspend', 'recall', 'updateuniqueno', 'removeItem', 'updateQty', 'updateDiscount', 'updatePrice', 'updateSalesDiscount', 'comments', 'loadinventorycategory', 'loadinventoryitems', 'loadreturnBalance', 'loadGiftCardBalance', 'addPayment', 'removePayment', 'cashout');
$createArray = array('create', 'additem', 'addcustomer', 'addsalesrep', 'complete', 'validate');
$editArray = array('edit','update','revertupdate','autoComplete','printView','view','editprice');
$deleteArray = array('delete','cancellation');




if(in_array($method,$accessArray))
{
	$accessKey = 0;
}
elseif(in_array($method,$createArray))
{
	$accessKey = 1;
}
elseif(in_array($method,$editArray))
{
	$accessKey = 2;
}

elseif(in_array($method,$deleteArray))
{
	$accessKey = 3;
}
else{ $accessKey=100; }

if($permissions[$accessKey]==1)
{
	
	
	if (file_exists($connectorPath)) {
		require_once $connectorPath;
		$instance = new $connector();
		
		if (method_exists($instance, $method)) {
			call_user_func_array([$instance, $method], array_slice($url, 2));
		} else {
			
			echo "Method not found!".$method;
		}
	} else {
		echo "Connector not found!".$connector;
	}
}
else
{
	echo 'Invalid permissions'; 
}

/*
//echo $encDec->enc('sales/salesscreen');
if(isset($_REQUEST['go']))
{
	$go_urlc=$_REQUEST['go'];
	$dec_url=$encDec->dec($go_urlc);
	
	list($path,$file) = explode('/',$dec_url);
	
	if(file_exists(_CORE."connector/".$path."/".$file.".php"))
	{
		require_once(_CORE."connector/".$path."/".$file.".php");
	}
	else
	{
		echo 'error';
	}
	
	
}
else
{
	$go_urlc='';
}

if($go_urlc)
{
	
}
else
{
	require_once(_CORE."connector/secure/signin.php");
}

*/