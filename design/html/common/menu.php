<?php
global $db;
global $SystemMasterUsergroupsQuery;
/////////////

$userInfoMenu = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
$userGroupInfoMenu = $SystemMasterUsergroupsQuery->get($userInfo['group_id']);

$permissionDirPathMenu = [];
$permissionPathTRNMASTMenu = [];
$permissionPathMenu = [];
foreach($userGroupInfoMenu as $ugiMenu)
{
	$ugiDcMenu = json_decode($ugiMenu, true);
	
	if (is_array($ugiDcMenu)) {
		foreach ($ugiDcMenu as $itemMenu) {
			if (isset($itemMenu['path'])) {
				$rowsugp_rowMenu = $db->fetch("SELECT * FROM secure_users_group_paths WHERE path_id = '".$itemMenu['path']."'");
				/*
				echo "Path: " . $item['path'] . "<br>";
				echo "DB Path: " . $rowsugp_row['path'] . "<br>";
				echo $item['permission'][0] . "<br>";
				echo "Permissions: " . implode(', ', $item['permission']) . "<br><br>";
				*/
				$expPathMenu = explode('/',$rowsugp_rowMenu['path']);
				$expPathMenuTRNMS = explode('_',$expPathMenu[1]);
				
				if($itemMenu['permission'][0]){ $permissionDirPathMenu[] = $expPathMenu[0]; }
				if($itemMenu['permission'][0])
				{
					$permissionPathTRNMASTMenu[] = $expPathMenu[0].'/'.$expPathMenuTRNMS[0];
					$permissionPathMenu[] = $rowsugp_rowMenu['path'];
				}
			}
		}
	}
	
}
?>
<nav id="menu">

    <ul>
    
    
        <?php if(in_array('common',$permissionDirPathMenu)){ ?>
        	<li><a href="<?php echo _SERVER; ?>common/dashboard"><i class="fal fa-tachometer-alt"></i><span>Dash </span></a></li>
		<?php } ?>
        
        <?php if(in_array('sales',$permissionDirPathMenu)){ ?>
        <li><a><i class="fa-light fa-keyboard"></i>Sales</a>
            
            <ul class="first-ul">
                
                <h3>Sales</h3>
                
                <?php if(in_array('sales/transaction',$permissionPathTRNMASTMenu) || in_array('sales/screen',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Transactions </a>
                
                    <ul>
            
            			<?php if(in_array('sales/screen',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/screen'); ?>">Screen</a></li>
                        <?php } ?>
                        <?php if(in_array('sales/transaction_invoices',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_invoices'); ?>">Invoices</a></li>
                        <?php } ?>
                        <?php if(in_array('sales/transaction_return',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_return'); ?>">Return</a></li>
                        <?php } ?>
                        <?php if(in_array('sales/transaction_giftcards',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_giftcards'); ?>">Gift Card</a></li>
                        <?php } ?>
                        <?php if(in_array('sales/transaction_quotations',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_quotations'); ?>">Quotations</a></li>
                        <?php } ?>
                        <?php if(in_array('sales/transaction_kot_print',$permissionPathMenu)){ ?>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_kot_print'); ?>">KOT Print</a></li>
                        <?php } ?>
                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('sales/master',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Master </a>
                
                    <ul>
            
            			<?php if (in_array('sales/master_rep', $permissionPathMenu)) { ?>
                        <li><a href="<?php echo $defCls->genURL('sales/master_rep'); ?>">Sales Rep</a></li>
                        <?php } ?>
                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('sales/r',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Reports</a>
                    <ul>
                        <?php if (in_array('sales/r_dailysales', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_dailysales'); ?>">Daily Sales</a></li>
                        <?php } ?>
                        <?php if (in_array('sales/r_saleslisting', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_saleslisting'); ?>">Sales Listing</a></li>
                        <?php } ?>
                        <?php if (in_array('sales/r_calcelled', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_calcelled'); ?>">Cancelled Sales</a></li>
                        <?php } ?>
                        <?php if (in_array('sales/r_salesreturn', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_salesreturn'); ?>">Sales Return</a></li>
                        <?php } ?>
                        <?php if (in_array('sales/r_loyalty', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_loyalty'); ?>">Loyalty</a></li>
                        <?php } ?>
                        <?php if (in_array('sales/r_giftcard', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('sales/r_giftcard'); ?>">Gift Card</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>
            
            </ul>
            
        </li>
        <?php } ?>
        <?php if(in_array('accounts',$permissionDirPathMenu)){ ?>
        <li><a><i class="fa-light fa-receipt"></i>Accounts</a>
            
            <ul class="first-ul">
                
                <h3>Accounts</h3>
                
                <?php if(in_array('sales/transaction',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Transactions </a>
                
                    <ul>
            
                        <?php if (in_array('accounts/transaction_expences', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/transaction_expences'); ?>">Expenses</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/transaction_transfers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/transaction_transfers'); ?>">Transfers</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/transaction_adjustments', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/transaction_adjustments'); ?>">Adjustments</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/transaction_cheque', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/transaction_cheque'); ?>">Cheques</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('accounts/master',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Master </a>
                
                    <ul>
            
                        <?php if (in_array('accounts/master_payee', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/master_payee'); ?>">Payee</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/master_expencestypes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/master_expencestypes'); ?>">Expenses Types</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/master_accounts', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/master_accounts'); ?>">Accounts</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('accounts/r',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Reports </a>
                
                    <ul>
            
                        <?php if (in_array('accounts/r_expences', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_expences'); ?>">Expense</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/r_transfers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_transfers'); ?>">Transfers</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/r_adjustments', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_adjustments'); ?>">Adjustments</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/r_ledger_listing', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_ledger_listing'); ?>">Ledger Listing</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/r_cheques', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_cheques'); ?>">Cheques</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('accounts/r_pnl', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('accounts/r_pnl'); ?>">PNL</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
            
            </ul>
            
        </li>
        
        <?php } ?>
        <?php if(in_array('customers',$permissionDirPathMenu)){ ?>
        
        <li><a><i class="fa-light fa-handshake"></i>Customers</a>
            
            <ul class="first-ul">
                
                <h3>Customers</h3>
                
                
                <?php if(in_array('customers/transaction',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Transactions </a>
                
                    <ul>
            
                        <?php if (in_array('customers/transaction_settlements', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/transaction_settlements'); ?>">Settlements</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/transaction_debitnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/transaction_debitnotes'); ?>">Debit Notes</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/transaction_creditnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/transaction_creditnotes'); ?>">Credit Notes</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('customers/master',$permissionPathTRNMASTMenu)){ ?>
                 <li><a>Master </a>
                
                    <ul>
            
                       <?php if (in_array('customers/master_customers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/master_customers'); ?>">Customers</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/master_customergroups', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/master_customergroups'); ?>">Customer Groups</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('customers/r',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Reports </a>
                
                    <ul>
            
                        <?php if (in_array('customers/r_settlements', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_settlements'); ?>">Settlements</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/r_debitnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_debitnotes'); ?>">Debit Notes</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/r_creditnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_creditnotes'); ?>">Credit Notes</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/r_customers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_customers'); ?>">Customers</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/r_outstanding', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_outstanding'); ?>">Outstanding</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('customers/r_ledger', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('customers/r_ledger'); ?>">Customer Ledger</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                
            
            </ul>
            
        </li>
        
        <?php } ?>
        <?php if(in_array('inventory',$permissionDirPathMenu)){ ?>
        
        <li><a><i class="fa-light fa-boxes-packing"></i>Inventory</a>
            
            <ul class="first-ul">
                
                <h3>Inventory</h3>
                
                
                <?php if(in_array('inventory/transaction',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Transactions </a>
                
                    <ul>
            
                        <?php if (in_array('inventory/transaction_receivingnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/transaction_receivingnotes'); ?>">Receiving Note</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/transaction_returnnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/transaction_returnnotes'); ?>">Return Note</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/transaction_transfernotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/transaction_transfernotes'); ?>">Transfer Note</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/transaction_adjustmentnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/transaction_adjustmentnotes'); ?>">Adjustment Note</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/transaction_uniquenos', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/transaction_uniquenos'); ?>">Unique No's</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('inventory/master',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Master </a>
                
                    <ul>
            
                        <?php if (in_array('inventory/master_items', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/master_items'); ?>">Items</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/master_category', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/master_category'); ?>">Category</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/master_units', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/master_units'); ?>">Units</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/master_brands', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/master_brands'); ?>">Brands</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/master_warranty', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/master_warranty'); ?>">Warranty</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('inventory/r',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Reports </a>
                
                    <ul>
            
                        <?php if (in_array('inventory/r_receiving', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_receiving'); ?>">Receiving</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/r_return', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_return'); ?>">Return</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/r_transfer', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_transfer'); ?>">Transfer</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/r_adjustment', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_adjustment'); ?>">Adjustment</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/r_stock_listing', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_stock_listing'); ?>">Stock Listing</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('inventory/r_ledger', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('inventory/r_ledger'); ?>">Item Ledger</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                
            
            </ul>
            
        </li>
        
        <?php } ?>
        <?php if(in_array('suppliers',$permissionDirPathMenu)){ ?>
        
        <li><a><i class="fa-light fa-users"></i>Suppliers</a>
            
            <ul class="first-ul">
                
                <h3>Suppliers</h3>
                
               
                <?php if(in_array('suppliers/transaction',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Transactions </a>
                
                    <ul>
            
                        <?php if (in_array('suppliers/transaction_payments', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/transaction_payments'); ?>">Payments</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('suppliers/transaction_creditnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/transaction_creditnotes'); ?>">Credit Notes</a></li>
                        <?php } ?>
                        
                        <?php if (in_array('suppliers/transaction_debitnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/transaction_debitnotes'); ?>">Debit Notes</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('suppliers/master',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Master </a>
                
                    <ul>
            
                        <?php if (in_array('suppliers/master_suppliers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/master_suppliers'); ?>">Suppliers</a></li>
                        <?php } ?>

                    
                    </ul>
                
                </li>
                <?php } ?>
                <?php if(in_array('suppliers/r',$permissionPathTRNMASTMenu)){ ?>
                <li><a>Reports </a>
                
                    <ul>
            
                        <?php if (in_array('suppliers/r_payments', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_payments'); ?>">Payments</a></li>
                        <?php } ?>
                        <?php if (in_array('suppliers/r_creditnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_creditnotes'); ?>">Credit Notes</a></li>
                        <?php } ?>
                        <?php if (in_array('suppliers/r_debitnotes', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_debitnotes'); ?>">Debit Notes</a></li>
                        <?php } ?>
                        <?php if (in_array('suppliers/r_suppliers', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_suppliers'); ?>">Suppliers</a></li>
                        <?php } ?>
                        <?php if (in_array('suppliers/r_outstanding', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_outstanding'); ?>">Outstanding</a></li>
                        <?php } ?>
                        <?php if (in_array('suppliers/r_ledger', $permissionPathMenu)) { ?>
                            <li><a href="<?php echo $defCls->genURL('suppliers/r_ledger'); ?>">Supplier Ledger</a></li>
                        <?php } ?>
                    
                    </ul>
                
                </li>
                <?php } ?>
                
            
            </ul>
            
        </li>
        
        <?php } ?>
        <?php if(in_array('system',$permissionDirPathMenu)){ ?>
        
        <li><a><i class="fa-light fa-gears"></i>System</a>
            
            <ul class="first-ul">
                
                <h3>System</h3>
                
                <?php if (in_array('system/master_locations', $permissionPathMenu)) { ?>
                    <li><a href="<?php echo $defCls->genURL('system/master_locations'); ?>">Locations</a></li>
                <?php } ?>
                <?php if (in_array('system/master_cashierpoints', $permissionPathMenu)) { ?>
                    <li><a href="<?php echo $defCls->genURL('system/master_cashierpoints'); ?>">Cashier Points</a></li>
                <?php } ?>
                <?php if (in_array('system/master_users', $permissionPathMenu)) { ?>
                    <li><a href="<?php echo $defCls->genURL('system/master_users'); ?>">Users</a></li>
                <?php } ?>
                <?php if (in_array('system/master_usergroups', $permissionPathMenu)) { ?>
                    <li><a href="<?php echo $defCls->genURL('system/master_usergroups'); ?>">User Groups</a></li>
                <?php } ?>
                <?php if (in_array('system/setup', $permissionPathMenu)) { ?>
                    <li><a href="<?php echo $defCls->genURL('system/setup'); ?>">Setup</a></li>
                <?php } ?>

                <?php if($sessionCls->load('signedUserId')==1){ ?><li><a href="<?php echo $defCls->genURL('system/master_master'); ?>">Master</a></li><?php } ?>
                
                
            </ul>
            
        </li>
        
        <?php } ?>
        
        <li><a href="<?php echo $defCls->genURL('secure/signout'); ?>"><i class="fa-light fa-right-from-bracket"></i>Sign Out</a></li>
    
    </ul>

</nav>