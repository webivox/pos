<div id="right">

	<div id="filter_head">
    
    	<h1>Item Ledger Listing  Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        
        <div class="col_1">
        
            <label for="search_item">Item Name</label>
            <select name="search_item" id="search_item">
                <?php
                foreach($data['item_list'] as $cat){
                ?>
                <option value="<?php echo $cat['item_id']; ?>"><?php echo $cat['name']; ?></option>
                <?php } ?>
            
            </select>
        
        </div>
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

