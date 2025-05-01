<div id="right">

	<div id="filter_head">
    
    	<h1>Credit Note Report</h1>
        
    
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
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

