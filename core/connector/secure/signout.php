<?php

class SecureSignoutConnector {

    public function index() {
		
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		
		if($sessionCls->load('signedUserId'))
		{
			$firewallCls->addLog("User signed out successfully.");
			
			$sessionCls->destroy('signedUserId');
			$sessionCls->destroy('signedToken');
			header("location:"._SERVER);
		}
		else
		{
			$sessionCls->destroy('signedUserId');
			$sessionCls->destroy('signedToken');
			header("location:"._SERVER);
		}
		
	}
}