<h3>Supplier</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
        
<div id="popup_form_in_form_in">  
   
    
    <div class="col_4">
    
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>" class="autofocus">
    
    </div>
    
    <div class="col_4">
    
        <label for="contact_person">Contact Person</label>
        <input type="text" name="contact_person" id="contact_person" placeholder="Contact Person" value="<?php echo $data['contact_person']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" value="<?php echo $data['phone_number']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>">
    
    </div>
    
    <div class="col_1">
    
        <label for="address">Address</label>
        <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $data['address']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="city">City</label>
        <input type="text" name="city" id="city" placeholder="City" value="<?php echo $data['city']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="state">State</label>
        <input type="text" name="state" id="state" placeholder="State" value="<?php echo $data['state']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="country">Country</label>
        <input type="text" name="country" id="country" placeholder="Country" value="<?php echo $data['country']; ?>">
    
    </div>
    
    <div class="col_4">
    
        <label for="payment_terms">Payment Terms</label>
        <input type="text" name="payment_terms" id="payment_terms" placeholder="Payment Terms" value="<?php echo $data['payment_terms']; ?>">
    
    </div>
    
    <div class="col_1">
    
        <label for="bank_details">Bank Details</label>
        <textarea name="bank_details" id="bank_details" placeholder="Bank Details" style="height:65px"><?php echo $data['bank_details']; ?></textarea>
    
    </div>
    
    <div class="col_4">
    
        <label for="tax_number">Tax Number</label>
        <input type="text" name="tax_number" id="tax_number" placeholder="Tax Number" value="<?php echo $data['tax_number']; ?>">
    
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