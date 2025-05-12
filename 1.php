///system_locations location_id
		
		
			inventory_receiving_notes
			inventory_adjustment_notes
			inventory_quotations
			inventory_return_notes
			inventory_stock_transactions location_from_id location_to_id
			inventory_transfer_notes
			
			accounts_expences
			accounts_transfers
			accounts_adjustments
			
			customers_credit_notes
			customers_debit_notes
			customers_settlements
			
			sales_invoices
			sales_pending_invoices
			sales_return
			
			secure_users
			
			suppliers_credit_notes
			suppliers_debit_notes
			suppliers_payments
			
			system_cashierpoints
			
			
			
			
			
			/////system_cashierpoints cashierpoint_id
			
			sales_invoices cashier_point_id
			sales_pending_invoices cashier_point_id
			sales_shifts cashier_point_id
			
			
			////secure_users user_id
			accounts_adjustments
			accounts_expences
			accounts_transfers
			
			customers_credit_notes
			customers_debit_notes
			customers_settlements
			
			inventory_adjustment_notes
			inventory_quotations
			inventory_receiving_notes
			inventory_return_notes
			inventory_transfer_notes
			
			
			sales_invoices
			sales_pending_invoices
			sales_return
			sales_shifts
			
			suppliers_credit_notes
			suppliers_debit_notes
			suppliers_payments
			
			
			
			////secure_users_groups group_id
			secure_users
			
			
			////suppliers_suppliers supplier_id
			inventory_items
			inventory_receiving_notes
			inventory_return_notes
			suppliers_credit_notes
			suppliers_debit_notes
			suppliers_payments
			supplier_transactions
			
			
			///inventory_unique_nos unique_id
			sales_invoice_items unique_no //this find by no
			sales_pending_invoice_items unique_no //find by no
			
			
			
			//item del
			inventory_adjustment_note_items
			inventory_items_customer_group_price
			inventory_quotation_items
			inventory_receiving_note_items
			inventory_receiving_note_items
			inventory_stock_transactions
			inventory_transfer_note_items
			inventory_unique_nos
			sales_invoice_items
			sales_pending_invoice_items
			sales_return_items
			
			
			//category, unit, brand, warr del
			inventory_category category_id 
			inventory_items
			
			///inventory_units unit_id
			inventory_items
			
			///inventory_brands brand_id
			inventory_items
			
			///inventory_warranty warranty_id
			inventory_items
			
			
			//customers_customers customer_id
			customers_credit_notes
			customers_debit_notes
			customers_settlements
			customer_loyalty_transactions
			customer_transactions
			inventory_quotations
			sales_invoices
			sales_pending_invoices
			sales_return
			
			
			///customers_groups customer_group_id
			customers_customers
			inventory_items_customer_group_price
			
			
			///accounts_payee payee_id
			accounts_expences
			
			
			///accounts_expences_types expences_type_id
			accounts_expences
			
			
			///accounts_accounts account_id
			accounts_adjustments
			accounts_cheque_transactions ///bank_code and type='Issued'  ~ deposited_account_id
			accounts_expences
			accounts_transfers // account_from_id ¬ account_to_id
			account_transactions
			customers_settlements
			suppliers_payments
			system_cashierpoints //cash_account_id transfer_account_id card_account_1_id card_account_2_id card_account_3_id card_account_4_id card_account_5_id
			
			
			//sales_gift_cards gift_card_id
			sales_invoice_payments
			sales_pending_invoice_payments
			
			
			
			///sales_rep rep_id
			sales_invoices sales_rep_id
			sales_pending_invoices sales_rep_id
            
            
            
			