<h3>Category</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
        
    <div class="col_1">
    
        <label for="parent_category_id">Parent Category</label>
        <select name="parent_category_id" id="parent_category_id" class="autofocus">
        
        	<option value="">None</option>
            
            <?php
			foreach($data['parent_category_list'] as $cat){
			?>
            <option value="<?php echo $cat['category_id']; ?>" <?php if($data['parent_category_id']==$cat['category_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    
    </div>
    
    <div class="col_1">
    
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>">
    
    </div>
    
    <div class="col_1">
    
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