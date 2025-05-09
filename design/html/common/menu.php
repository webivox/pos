<nav id="menu">

    <ul>
    
    
        <li><a href="<?php echo _SERVER; ?>common/dashboard"><i class="fal fa-tachometer-alt"></i><span>Dash </span></a></li>
        
        <li><a><i class="fa-light fa-keyboard"></i>Sales</a>
            
            <ul class="first-ul">
                
                <h3>Sales</h3>
                
                <li><a>Transactions </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('sales/screen'); ?>">Screen</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_invoices'); ?>">Invoices</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_return'); ?>">Return</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_giftcards'); ?>">Gift Card</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/transaction_quotations'); ?>">Quotations</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Master </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('sales/master_rep'); ?>">Sales Rep</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Reports </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('sales/r_dailysales'); ?>">Daily Sales</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/r_saleslisting'); ?>">Sales Listing</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/r_calcelled'); ?>">Cancelled Sales</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/r_salesreturn'); ?>">Sales Return</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/r_loyalty'); ?>">Loyalty</a></li>
                        <li><a href="<?php echo $defCls->genURL('sales/r_giftcard'); ?>">Gift Card</a></li>
                    
                    </ul>
                
                </li>
            
            </ul>
            
        </li>
        
        <li><a><i class="fa-light fa-receipt"></i>Accounts</a>
            
            <ul class="first-ul">
                
                <h3>Accounts</h3>
                
                <li><a>Transactions </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('accounts/transaction_expences'); ?>">Expences</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/transaction_transfers'); ?>">Transfers</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/transaction_adjustments'); ?>">Adjustments</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/transaction_cheque'); ?>">Cheques</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Master </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('accounts/master_payee'); ?>">Payee</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/master_expencestypes'); ?>">Expences Types</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/master_accounts'); ?>">Accounts</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Reports </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('accounts/r_expences'); ?>">Expence</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/r_transfers'); ?>">Transfers</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/r_adjustments'); ?>">Adjustments</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/r_ledger_listing'); ?>">Ledger Listing</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/r_cheques'); ?>">Cheques</a></li>
                        <li><a href="<?php echo $defCls->genURL('accounts/r_pnl'); ?>">PNL</a></li>
                    
                    </ul>
                
                </li>
            
            </ul>
            
        </li>
        
        <li><a><i class="fa-light fa-handshake"></i>Customers</a>
            
            <ul class="first-ul">
                
                <h3>Customers</h3>
                
                <li><a>Transactions </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('customers/transaction_settlements'); ?>">Settlements</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/transaction_debitnotes'); ?>">Debit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/transaction_creditnotes'); ?>">Credit Notes</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Master </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('customers/master_customers'); ?>">Customers</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/master_customergroups'); ?>">Customer Groups</a></li>
                    
                    </ul>
                
                </li>
                
                <li><a>Reports </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('customers/r_settlements'); ?>">Settlements</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/r_debitnotes'); ?>">Debit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/r_creditnotes'); ?>">Credit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/r_customers'); ?>">Customers</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/r_outstanding'); ?>">Outstanding</a></li>
                        <li><a href="<?php echo $defCls->genURL('customers/r_ledger'); ?>">Customer Ledger</a></li>
                    
                    </ul>
                
                </li>
            
            </ul>
            
        </li>
        
        <li><a><i class="fa-light fa-boxes-packing"></i>Inventory</a>
            
            <ul class="first-ul">
                
                <h3>Inventory</h3>
                
                <li><a>Transactions </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('inventory/transaction_receivingnotes'); ?>">Receiving Note</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/transaction_returnnotes'); ?>">Return Note</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/transaction_transfernotes'); ?>">Transfer Note</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/transaction_adjustmentnotes'); ?>">Adjustment Note</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Master </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('inventory/master_items'); ?>">Items</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/master_category'); ?>">Category</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/master_units'); ?>">Units</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/master_brands'); ?>">Brands</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/master_warranty'); ?>">Warranty</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Reports </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('inventory/r_receiving'); ?>">Receiving</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/r_return'); ?>">Return</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/r_transfer'); ?>">Transfer</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/r_adjustment'); ?>">Adjustment</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/r_stock_listing'); ?>">Stock Listing</a></li>
                        <li><a href="<?php echo $defCls->genURL('inventory/r_ledger'); ?>">Item Ledger</a></li>
                    
                    </ul>
                
                </li>
            
            </ul>
            
        </li>
        
        <li><a><i class="fa-light fa-users"></i>Suppliers</a>
            
            <ul class="first-ul">
                
                <h3>Suppliers</h3>
                
                <li><a>Transactions </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('suppliers/transaction_payments'); ?>">Payments</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/transaction_creditnotes'); ?>">Credit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/transaction_debitnotes'); ?>">Debit Notes</a></li>
                    
                    </ul>
                
                </li>
                <li><a>Master </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('suppliers/master_suppliers'); ?>">Suppliers</a></li>
                    
                    </ul>
                
                </li>
                
                <li><a>Reports </a>
                
                    <ul>
            
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_payments'); ?>">Payments</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_debitnotes'); ?>">Debit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_creditnotes'); ?>">Credit Notes</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_suppliers'); ?>">Suppliers</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_outstanding'); ?>">Outstanding</a></li>
                        <li><a href="<?php echo $defCls->genURL('suppliers/r_ledger'); ?>">Supplier Ledger</a></li>
                    
                    </ul>
                
                </li>
            
            </ul>
            
        </li>
        
        <li><a><i class="fa-light fa-gears"></i>System</a>
            
            <ul class="first-ul">
                
                <h3>System</h3>
                
                <li><a href="<?php echo $defCls->genURL('system/master_locations'); ?>">Locations</a></li>
                <li><a href="<?php echo $defCls->genURL('system/master_cashierpoints'); ?>">Cashier Points</a></li>
                <li><a href="<?php echo $defCls->genURL('system/master_users'); ?>">Users</a></li>
                <li><a href="<?php echo $defCls->genURL('system/master_usergroups'); ?>">User Groups</a></li>
                <li><a href="<?php echo $defCls->genURL('system/master_master'); ?>">Master</a></li>
                
                
            </ul>
            
        </li>
        
        <li><a href="<?php echo $defCls->genURL('secure/signout'); ?>"><i class="fa-light fa-right-from-bracket"></i>Sign Out</a></li>
    
    </ul>

</nav>