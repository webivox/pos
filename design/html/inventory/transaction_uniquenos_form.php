<h3>Unique NOs</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
        
   
        
    <div class="col_1">
        
        <label for="item_name">Item Name</label>
        <input type="text" id="item_name" name="item_name" class="itemAjax" data-focus="unique_no" class="autofocus" data-setid="item_id" value="<?php echo $data['item_name']; ?>" />
        <input type="hidden" id="item_id" name="item_id" value="<?php echo $data['item_id']; ?>" />

    </div>
    
    <div class="col_1">
    
        <label for="unique_no">Unique No</label>
        <input type="text" name="unique_no" id="unique_no" placeholder="Unique No" value="<?php echo $data['unique_no']; ?>">
    
    </div>
        
    <div class="col_1">
        
        <label for="cost">Cost</label>
        <input type="text" id="cost" name="cost" value="<?php echo $data['cost']; ?>" />

    </div>
    
    <div class="col_1">
    
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="<?php echo $data['remarks']; ?>">
    
    </div>
    
    <div class="col_1">
    
        <label for="status">Status</label>
        <select name="status" id="status">
        
        	<option value="">- Choose -</option>
        	<option value="0" <?php if($data['status']==0){ echo 'selected'; } ?>>Created</option>
        	<option value="1" <?php if($data['status']==1){ echo 'selected'; } ?>>Used</option>
        
        </select>
    
    </div>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    
    
</form>