<?php

// Parse URL for routing
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['secure'];

$dir = isset($url[0]) ? $url[0] : 'secure';
$file = isset($url[1]) ? $url[1] : 'signin';


$connector = isset($dir) && isset($file) ? ucfirst($dir) . str_replace(' ', '', ucwords(str_replace('_', ' ', $file))) . 'Connector' : 'SecureSigninConnector';

$method = isset($url[2]) ? $url[2] : 'index';
$id = isset($url[3]) ? $url[3] : '0';

$connectorPath = _CONNECTOR . $dir . "/" . $file .".php";

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