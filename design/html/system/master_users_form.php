<h3>User</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
        
   
    
    <div class="col_4">
        <label for="group_id">Group</label>
        <select name="group_id" id="group_id" class="autofocus">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['user_group_list'] as $cat){
			?>
            <option value="<?php echo $cat['group_id']; ?>" <?php if($data['group_id']==$cat['group_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_4">
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
    
    <div class="col_4">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>">
    </div>
    
    <div class="col_4">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $data['username']; ?>">
    </div>
    
    <div class="col_4">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" value="<?php echo $data['password']; ?>" autocomplete="off">
    </div>
    
    <div class="col_4">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm_password" value="<?php echo $data['confirm_password']; ?>" autocomplete="off">
    </div>
    
    <div class="col_4">
        <label for="loginRedirectTo">Login Redirect To</label>
       
        <select name="loginRedirectTo" id="loginRedirectTo">
            
            <option value="">- Choose -</option>
            <option value="common/dashboard" <?php if($data['loginRedirectTo']=='common/dashboard'){ echo 'selected'; } ?>>Dashboad</option>
            <option value="sales/screen" <?php if($data['loginRedirectTo']=='sales/screen'){ echo 'selected'; } ?>>Sales Screen</option>
        
        </select>
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