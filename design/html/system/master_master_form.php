<h3>Location</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
   
        
<div id="popup_form_in_form_in">  
    
    <div class="col_1">
        <label for="key">Key</label>
        <input type="text" name="key" id="key" placeholder="Key" value="<?php echo $data['key']; ?>" class="autofocus">
    </div>
    
    <div class="col_1">
        <label for="values">Value</label>
        <input type="text" name="values" id="values" placeholder="Value" value="<?php echo $data['values']; ?>">
    </div>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    
    
</form>