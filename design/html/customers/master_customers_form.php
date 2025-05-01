<h3>Customer</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
        
   
    
    <div class="col_4">
        <label for="customer_group_id">Customer Group</label>
        
        <select name="customer_group_id" id="customer_group_id" class="autofocus">
        
        	<option value="">- Choose -</option>
            
            <?php
			foreach($data['customer_group_list'] as $cat){
			?>
            <option value="<?php echo $cat['customer_group_id']; ?>" <?php if($data['customer_group_id']==$cat['customer_group_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
        
    </div>
    
    <div class="col_4">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>">
    </div>
    
    <div class="col_4">
        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" value="<?php echo $data['phone_number']; ?>">
    </div>
    
    <div class="col_4">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>">
    </div>
    
    <div class="col_4">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $data['address']; ?>">
    </div>
    
    <div class="col_4">
        <label for="credit_limit">Credit Limit</label>
        <input type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?php echo $data['credit_limit']; ?>">
    </div>
    
    <div class="col_4">
        <label for="settlement_days">Settlement Days</label>
        <input type="text" name="settlement_days" id="settlement_days" placeholder="Settlement Days" value="<?php echo $data['settlement_days']; ?>">
    </div>
    
    <div class="col_4">
        <label for="card_no">Card No</label>
        <input type="text" name="card_no" id="card_no" placeholder="Card No" value="<?php echo $data['card_no']; ?>">
    </div>
    
    <div class="col_1">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="<?php echo $data['remarks']; ?>">
    </div>

    
    <div class="col_4">
    
        <label for="status">Status</label>
        <select name="status" id="status">
        
        	<option value="1" <?php if($data['status']==1){ echo 'selected'; } ?>>Enable</option>
        	<option value="0" <?php if($data['status']==0){ echo 'selected'; } ?>>Disable</option>
        
        </select>
    
    </div>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    
    
</form>