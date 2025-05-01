<h3>Gift Card</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
        
<div id="popup_form_in_form_in">  
    
    <div class="col_1">
    
        <label for="no">Gift Card No</label>
        <input type="text" name="no" id="no" placeholder="No" value="<?php echo $data['no']; ?>" class="autofocus">
    
    </div>
    
    <div class="col_1">
    
        <label for="expiry_date">Expiry Date</label>
        <input type="text" name="expiry_date" id="expiry_date" class="dateField" placeholder="dd-mm-YYYY" value="<?php echo $data['expiry_date']; ?>">
    
    </div>
    
    <div class="col_1">
    
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" placeholder="0.00" value="<?php echo $data['amount']; ?>">
    
    </div>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    
    
</form>