<div id="right">

	<div id="filter_head">
    
    	<h1>Transfers</h1>
        
        
        <a class="open_popup_form btn btn-primary" id="filter_head_create" data-url="<?php echo $data['create_url']; ?>" data-width="650" data-height="380">Create New</a>
    
    </div>
    
    <div id="filter_content">
    
    	<h3>Search Here</h3>
        
        <form method="post" id="searchForm" data-url="<?php echo $data['load_table_url'] ; ?>">
        
        <div class="col_4">
        
            <label for="search_no">No</label>
            <input type="text" name="search_no" id="search_no" placeholder="No" value="ATRN-">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_date_from">Date From</label>
            <input type="text" name="search_date_from" id="search_date_from" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="search_date_to">Date To</label>
            <input type="text" name="search_date_to" id="search_date_to" class="dateField" placeholder="">
        
        </div>
        
        <div class="col_4">
        
            <label for="account_from_txt">Account From</label>
            <input type="text" name="account_from_txt" id="account_from_txt" data-setid="account_from" placeholder="" class="accountAjax">
            <input type="hidden" id="account_from" name="account_from" />
        
        </div>
        
        <div class="col_4">
        
            <label for="account_to_txt">Account To</label>
            <input type="text" name="account_to_txt" id="account_to_txt" data-setid="account_to" placeholder="" class="accountAjax">
            <input type="hidden" id="account_to" name="account_to" />
        
        </div>
        
        <div class="col_1">
        
        	<input type="hidden" value="1" id="pageno" name="pageno" readonly="readonly" />
            <button class="btn btn-primary" type="submit" id="SearchFormBtn">Search</button>
            <button class="btn btn-black" id="searchReset">Clear</button>
        
        </div>
        </form>
    
    </div>
    
    
    <div id="list_content">
    
    	
    
    </div>

</div>



<div id="popup_form">


	<div id="popup_form_in">
    
    	<a id="popup_form_in_close">X</a>
    
    	<div id="popup_form_in_form">
        
        
        </div>
    
    </div>

</div>


<div id="modal_loading"><div id="modal_loading_in"><div id="modal_loading_in_icon"></div></div></div>

<div id="modal">

	<div id="modal_in">
    
    	<div id="modal_in_error">
        
        	<i class="fa-solid fa-brake-warning"></i>
            
            <h2>Error Found</h2>
            <p>We found the following error. Please fix it and retry.</p>
            
        </div>
        
        <ul>
        
        </ul>
        
        <a id="modal_in_close" accesskey="x">Close</a>
    
    </div>

</div>


<div id="modal_success">

	<div id="modal_success_in">
    
    	<div id="modal_success_in_error">
        
    		<i class="fa-solid fa-shield-check"></i>
            
            <h2>Successfully Completed!!!</h2>
            <p></p>
            
        </div>
        
        <a id="modal_success_in_close" accesskey="x">Close</a>
    
    </div>

</div>


<script type="text/javascript" src="<?php echo _JS; ?>core/accounts_rn.js"></script>