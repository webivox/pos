<h3>Location</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
   
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>" class="autofocus">
    </div>
    
    
    <div class="col_3">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $data['address']; ?>">
    </div>
    
    <div class="col_3">
        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" value="<?php echo $data['phone_number']; ?>">
    </div>
    
    <div class="col_3">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>">
    </div>
    
    <div class="col_3">
        <label for="invoice_no_start">Invoice No Start</label>
        <input type="text" name="invoice_no_start" id="invoice_no_start" placeholder="Invoice No. Start" value="<?php echo $data['invoice_no_start']; ?>">
    </div>

    
    <div class="col_3">
    
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