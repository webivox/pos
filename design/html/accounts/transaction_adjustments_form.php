<h3>Adjustment</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
    	<label for="adjustment_no">AADJ No</label>
        <input type="text" id="adjustment_no" value="<?php echo $data['adjustment_no']; ?>" disabled>
    </div>
    
    <div class="col_3">
        <label for="type">Type</label>
        <select name="type" id="type" class="autofocus">
            
            <option value="">- Choose -</option>
            <option value="Credit" <?php if($data['type']=='Credit'){ echo 'selected'; } ?>>Credit +</option>
            <option value="Debit" <?php if($data['type']=='Debit'){ echo 'selected'; } ?>>Debit -</option>
        
        </select>
    </div>
    
    <div class="col_3">
        <label for="added_date">Added Date</label>
        <input type="text" name="added_date" id="added_date" value="<?php echo $data['added_date']; ?>" class="dateField">
    </div>
    
    <div class="col_3">
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" placeholder="0.00" value="<?php echo $data['amount']; ?>">
    </div>
    
    <div class="col_3">
        <label for="location_id">Location</label>
        <select name="location_id" id="location_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['location_list'] as $cat){
			?>
            <option value="<?php echo $cat['location_id']; ?>" <?php if($data['location_id']==$cat['location_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_3">
        <label for="account_id">Account ID</label>
        <select name="account_id" id="account_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['account_list'] as $cat){
			?>
            <option value="<?php echo $cat['account_id']; ?>" <?php if($data['account_id']==$cat['account_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_3">
        <label for="is_other_income">Is Other Income</label>
        <select name="is_other_income" id="is_other_income">
            
            <option value="">- Choose -</option>
            <option value="1" <?php if($data['is_other_income']==1){ echo 'selected'; } ?>>Yes</option>
            <option value="0" <?php if($data['is_other_income']==0){ echo 'selected'; } ?>>No</option>
            
        
        </select>
    </div>
    
    <div class="col_2">
        <label for="details">Details</label>
        <input type="text" name="details" id="details" placeholder="Details" value="<?php echo $data['details']; ?>">
    </div>
    
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_3">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    


</form>