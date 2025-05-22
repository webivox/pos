<h3>Cashier Point</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>" enctype="multipart/form-data">
        
   
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>" class="autofocus">
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
        <label for="cash_account_id">Cash Account</label>
        <select name="cash_account_id" id="cash_account_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['account_list'] as $cat){
			?>
            <option value="<?php echo $cat['account_id']; ?>" <?php if($data['cash_account_id']==$cat['account_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_3">
        <label for="transfer_account_id">Transfer Account</label>
        <select name="transfer_account_id" id="transfer_account_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['account_list'] as $cat){
			?>
            <option value="<?php echo $cat['account_id']; ?>" <?php if($data['transfer_account_id']==$cat['account_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <?php for($x=1;$x<6;$x++){ ?>
    
    <div class="col_3">
        <label for="card_account_<?php echo $x; ?>_name">Card Account <?php echo $x; ?> Name</label>
        <input type="text" name="card_account_<?php echo $x; ?>_name" id="card_account_<?php echo $x; ?>_name" placeholder="" value="<?php echo $data['card_account_'.$x.'_name']; ?>">
    </div>
    
    <div class="col_3">
        <label for="card_account_<?php echo $x; ?>_id">Card Account <?php echo $x; ?> Id</label>
        
        <select name="card_account_<?php echo $x; ?>_id" id="card_account_<?php echo $x; ?>_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['account_list'] as $cat){
			?>
            <option value="<?php echo $cat['account_id']; ?>" <?php if($data['card_account_'.$x.'_id']==$cat['account_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    <?php } ?>

    
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
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save</button>
    
    </div>
</div> 
    
    
</form>