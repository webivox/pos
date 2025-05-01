<?php

$file = 'master_cashierpoints';

$dir = 'system';

//$run = true;
$run = false;

if($run)
{
	
	fopen("D:/wamp64/www/pos/core/connector/".$dir."/".$file.".php", 'w');
	fopen("D:/wamp64/www/pos/core/query/".$dir."/".$file.".php", 'w');
	fopen("D:/wamp64/www/pos/design/html/".$dir."/".$file.".php", 'w');
	fopen("D:/wamp64/www/pos/design/html/".$dir."/".$file."_form.php", 'w');
	fopen("D:/wamp64/www/pos/design/html/".$dir."/".$file."_table.php", 'w');


}











