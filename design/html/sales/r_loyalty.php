<div id="right">

	<div id="filter_head">
    
    	<h1>Loyalty  Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        
        <div class="col_4">
        
            <label for="search_type">Report Type</label>
            <select name="search_type" id="search_type">
            
            	<option value="">- Choose -</option>
            	<option value="S">Summary</option>
            	<option value="C">Customer</option>
            
            </select>
        
        </div>
        
        <script>
		
			$(document).on('change','#search_type',function(){
				
				var val = $(this).val();
				
				if(val == 'C')
				{
					$("#search_date_from").prop("disabled", true);
					$("#search_date_to").prop("disabled", true);
				}
				else
				{
					$("#search_date_from").prop("disabled", false);
					$("#search_date_to").prop("disabled", false);
				}
				
				
			});
		
		</script>
        
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
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

