<h3>Settlement</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
    	<label for="settlement_no">CSETT No</label>
        <input type="text" id="settlement_no" value="<?php echo $data['settlement_no']; ?>" disabled>
    </div>
    
    <div class="col_3">
        <label for="added_date">Added Date</label>
        <input type="text" name="added_date" id="added_date" value="<?php echo $data['added_date']; ?>" class="dateField autofocus">
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
        <label for="customer_id">Customer ID</label>
        <select name="customer_id" id="customer_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['customer_list'] as $cat){
			?>
            <option value="<?php echo $cat['customer_id']; ?>" data-outstanding="<?php echo $defCls->num($cat['closing_balance']); ?>" <?php if($data['customer_id']==$cat['customer_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
   </div>
	<script>
    
    $("#customer_id").change(function(){
        var outstanding = $(this).find('option:selected').data('outstanding');
    
        $("#outstanding").val(outstanding);
    });
    
    
    
    $("#amount").change(function(){
        
        var outstanding = parseFloat($("#customer_id").find('option:selected').data('outstanding')) || 0;
        var amount = parseFloat($("#amount").val());
        
        if(outstanding<amount){ alert("You can't enter an amount higher than the outstanding amount!"); }
        
    });
    
    </script>
    
    <div class="col_3">
        <label for="outstanding">Outstanding</label>
        <input type="text" name="outstanding" id="outstanding" placeholder="0.00" value="<?php echo $data['outstanding']; ?>" disabled="disabled">
    </div>
    
    <div class="col_3">
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" placeholder="0.00" value="<?php echo $data['amount']; ?>">
    </div>
    
    
    <div class="col_3">
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
    
    <div class="col_3">
        <label for="bank_code">Bank Code</label>
        <input type="text" name="bank_code" id="bank_code" placeholder="Bank Code" value="<?php echo $data['bank_code']; ?>">
    </div>
    
    <div class="col_3">
        <label for="cheque_no">Cheque No</label>
        <input type="text" name="cheque_no" id="cheque_no" placeholder="Cheque No" value="<?php echo $data['cheque_no']; ?>">
    </div>
    
    <div class="col_3">
        <label for="cheque_date">Cheque Date</label>
        <input type="text" name="cheque_date" id="cheque_date" placeholder="" value="<?php echo $data['cheque_date']; ?>" class="dateField">
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