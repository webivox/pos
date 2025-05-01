<div id="right">

	<div id="filter_head">
    
    	<h1>Supplier Outstanding Report</h1>
        
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Filter Here</h3>
        
        <form method="post" id="searchReportForm" action="<?php echo $data['view_url'] ; ?>" target="_blank">
        
        
        
        
        <div class="col_1">
        
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
        
        
        <div class="col_1">
        
            <button class="btn btn-primary" type="submit" id="searchReportForm">View</button>
        
        </div>
        </form>
    
    </div>

</div>

