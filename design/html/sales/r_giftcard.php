<div id="right">

	<div id="filter_head">
    
    	<h1>Gift Card Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        
        <div class="col_4">
        
            <label for="search_no">Gift Card No</label>
            <input type="text" name="search_no" id="search_no" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_idate_from">Issue Date From</label>
            <input type="text" name="search_idate_from" id="search_idate_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_idate_to">Issue Date To</label>
            <input type="text" name="search_idate_to" id="search_idate_to" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_rdate_from">Redeemed Date From</label>
            <input type="text" name="search_rdate_from" id="search_rdate_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_rdate_to">Redeemed Date To</label>
            <input type="text" name="search_rdate_to" id="search_rdate_to" class="dateField" placeholder="">
        
        </div>
        
        
        <div class="col_4">
        
            <label for="search_status">Status</label>
            <select name="search_status" id="search_status">
            
            	<option value="">- Choose -</option>
            	<option value="">All</option>
            	<option value="R">Redeemed</option>
            	<option value="P">Pending</option>
            	<option value="B">Balance</option>
            
            </select>
        
        </div>
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

