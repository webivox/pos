<?php
if($from_homecx_load)
{
	require_once(_BASE."class/db.php");
	require_once(_BASE."class/defCls.php");
	require_once(_BASE."class/session.php");	
	require_once(_BASE."class/date.php");	
	require_once(_BASE."class/firewall.php");
	require_once(_BASE."class/stock.php");	
	
	
	$baseDir = _QUERY;

	foreach (glob($baseDir . '/*', GLOB_ONLYDIR) as $folder) {
		foreach (glob($folder . '/*.php') as $file) {
			require_once $file;
		}
	}
	
	/*
	require_once(_QUERY."customers/master_customers.php");
	require_once(_QUERY."suppliers/master_suppliers.php");	
	require_once(_QUERY."accounts/master_accounts.php");
	require_once(_QUERY."accounts/master_payee.php");
	require_once(_QUERY."accounts/master_expencestypes.php");
	require_once(_QUERY."system/master_users.php");	
	require_once(_QUERY."system/master_usergroups.php");	
	require_once(_QUERY."sales/sales.php");	
	require_once _QUERY."suppliers/transaction_payments.php";
	require_once _QUERY."suppliers/transaction_debitnotes.php";
	require_once _QUERY."suppliers/transaction_creditnotes.php";
	
	require_once _QUERY."sales/master_rep.php";
	
	require_once _QUERY."inventory/transaction_transfernotes.php";
	require_once _QUERY."inventory/master_items.php";
	
	require_once _QUERY."inventory/transaction_returnnotes.php";
	
	require_once _QUERY."inventory/transaction_receivingnotes.php";
	
	require_once _QUERY."inventory/master_warranty.php";
	
	require_once _QUERY."customers/transaction_debitnotes.php";
	require_once _QUERY."customers/transaction_creditnotes.php";
	
	require_once _QUERY."customers/master_customers.php";
	
	require_once _QUERY."accounts/transaction_expences.php";
	*/
}