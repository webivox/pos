<h3>Expence</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
    	<label for="expence_no">AEXP No</label>
        <input type="text" id="expence_no" value="<?php echo $data['expence_no']; ?>" disabled>
    </div>
    
    <div class="col_3">
        <label for="payee_id">Payee</label>
        <select name="payee_id" id="payee_id" class="autofocus">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['payee_list'] as $cat){
			?>
            <option value="<?php echo $cat['payee_id']; ?>" <?php if($data['payee_id']==$cat['payee_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_3">
        <label for="expences_type_id">Expences Type</label>
        <select name="expences_type_id" id="expences_type_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['expencestype_list'] as $cat){
			?>
            <option value="<?php echo $cat['expences_type_id']; ?>" <?php if($data['expences_type_id']==$cat['expences_type_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
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
    
    <div class="col_2">
        <label for="account_id">Account</label>
        <select name="account_id" id="account_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['account_list'] as $cat){
			?>
            <option value="<?php echo $cat['account_id']; ?>" <?php if($data['account_id']==$cat['account_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
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