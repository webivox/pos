<div id="right">

	<div id="filter_head">
    
    	<h1>Cheque Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        
        <div class="col_4">
        
            <label for="search_cheque_no">Cheque No</label>
            <input type="text" name="search_cheque_no" id="search_cheque_no" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_added_date_from">Added Date From</label>
            <input type="text" name="search_added_date_from" id="search_added_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_added_date_to">Added Date To</label>
            <input type="text" name="search_added_date_to" id="search_added_date_to" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_cheque_date_from">Cheque Date From</label>
            <input type="text" name="search_cheque_date_from" id="search_cheque_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_cheque_date_to">Cheque Date To</label>
            <input type="text" name="search_cheque_date_to" id="search_cheque_date_to" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_realized_date_from">Realized Date From</label>
            <input type="text" name="search_realized_date_from" id="search_realized_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_realized_date_to">Realized Date To</label>
            <input type="text" name="search_realized_date_to" id="search_realized_date_to" class="dateField" placeholder="">
        
        </div>
        
        
        <div class="col_4">
        
            <label for="search_type">Type</label>
            <select name="search_type" id="search_type">
            
            	<option value="">All</option>
            	<option value="Issued">Issued</option>
            	<option value="Received">Received</option>
            
            </select>
        
        </div>
        
        
        <div class="col_4">
        
            <label for="search_status">Status</label>
            <select name="search_status" id="search_status">
            
            	<option value="">All</option>
            	<option value="0">Pending</option>
            	<option value="1">Realized</option>
            
            </select>
        
        </div>
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

