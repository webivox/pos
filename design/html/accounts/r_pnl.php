<div id="right">

	<div id="filter_head">
    
    	<h1>Ledger Listing  Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        <div class="col_2">
        
            <label for="search_date_from">Date From</label>
            <input type="text" name="search_date_from" id="search_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_2">
        
            <label for="search_date_to">Date To</label>
            <input type="text" name="search_date_to" id="search_date_to" class="dateField" placeholder="">
        
        </div>
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

