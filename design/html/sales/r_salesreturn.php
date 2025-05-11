<div id="right">

	<div id="filter_head">
    
    	<h1>Sales Return Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        <div class="col_4">
        
            <label for="search_date_from">Date From</label>
            <input type="text" name="search_date_from" id="search_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_date_to">Date To</label>
            <input type="text" name="search_date_to" id="search_date_to" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_customer">Customer</label>
            <select name="search_customer" id="search_customer">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['customer_list'] as $cat){
                ?>
                <option value="<?php echo $cat['customer_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_location">Location</label>
            <select name="search_location" id="search_location">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['location_list'] as $cat){
                ?>
                <option value="<?php echo $cat['location_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_sales_rep">Sales Rep</label>
            <select name="search_sales_rep" id="search_sales_rep">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['sales_rep_list'] as $cat){
                ?>
                <option value="<?php echo $cat['rep_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_user">User</label>
            <select name="search_user" id="search_user">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['user_list'] as $cat){
                ?>
                <option value="<?php echo $cat['user_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_category">Category</label>
            <select name="search_category" id="search_category">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['category_list'] as $cat){
                ?>
                <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_brand">Brand</label>
            <select name="search_brand" id="search_brand">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['brand_list'] as $cat){
                ?>
                <option value="<?php echo $cat['brand_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_unit">Unit</label>
            <select name="search_unit" id="search_unit">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['unit_list'] as $cat){
                ?>
                <option value="<?php echo $cat['unit_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_supplier">Supplier</label>
            <select name="search_supplier" id="search_supplier">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['supplier_list'] as $cat){
                ?>
                <option value="<?php echo $cat['supplier_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_barcode">Barcode</label>
            <input type="text" id="search_barcode" name="search_barcode">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_barcode_name">Barcode</label>
            <input type="text" id="search_barcode_name" name="search_barcode_name">
        
        </div>
        
        <div class="col_1">
        
            <label for="search_item_name">Item Name</label>
            <input type="text" id="search_item_name" name="search_item_name">
        
        </div>
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

