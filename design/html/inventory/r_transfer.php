<div id="right">

	<div id="filter_head">
    
    	<h1>Transfer Note Report</h1>
        
    
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
        
            <label for="search_location_from">Location From</label>
            <select name="search_location_from" id="search_location_from">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['location_list'] as $cat){
                ?>
                <option value="<?php echo $cat['location_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_4">
        
            <label for="search_location_to">Location To</label>
            <select name="search_location_to" id="search_location_to">
                
                <option value="">- Choose -</option>
                <?php
                foreach($data['location_list'] as $cat){
                ?>
                <option value="<?php echo $cat['location_id']; ?>"><?php echo $cat['name']; ?></option>
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

