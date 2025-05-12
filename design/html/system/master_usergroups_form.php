<h3>User Group</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
   
        
<div id="popup_form_in_form_in">  
    
    <div class="col_2">
    
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>" class="autofocus">
    
    </div>
    
    <div class="col_2">
    
        <label for="status">Status</label>
        <select name="status" id="status">
        
        	<option value="1" <?php if($data['status']==1){ echo 'selected'; } ?>>Enable</option>
        	<option value="0" <?php if($data['status']==0){ echo 'selected'; } ?>>Disable</option>
        
        </select>
    
    </div>
    
    
    <table class="table">
        
            
            <tr>
            
            	<td>Path</td>
            	<td>Access</td>
            	<td>Add</td>
            	<td>Edit</td>
            	<td>Delete</td>
            
            </tr><?php
			
			
			 
			 ?>
            <?php
			
			if(isset($data['openCheckBox'])){
			$res=$db->fetchAll("SELECT * FROM secure_users_group_paths");
			
			foreach($res as $row)
			{
				
				$targetPath = $row['path_id'];

				$matched = array_values(array_filter($data['savedPermissions'], function($item) use ($targetPath) {
					return $item['path'] == $targetPath;
				}));
				
				$permissions = $matched[0]['permission'];
				

			?>
            <tr>
            
            	<td><?php echo $row['path']; ?></td>
                <input type="hidden" value="<?php echo $row['path_id']; ?>" name="path_id<?php echo $row['path_id']; ?>">
            	<td>
					<?php if($row['access']==1){ ?>
                    <input type="checkbox" name="access<?php echo $row['path_id']; ?>" value="1" <?php if($permissions[0]){ echo 'checked'; } ?>>
                    <?php } ?>
                </td>
            	<td>
					<?php if($row['create']==1){ ?>
                    <input type="checkbox" name="create<?php echo $row['path_id']; ?>" value="1" <?php if($permissions[1]){ echo 'checked'; } ?>>
                    <?php } ?>
                </td>
            	<td>
					<?php if($row['edit']==1){ ?>
                    <input type="checkbox" name="edit<?php echo $row['path_id']; ?>" value="1" <?php if($permissions[2]){ echo 'checked'; } ?>>
                    <?php } ?>
                </td>
            	<td>
					<?php if($row['delete']==1){ ?>
                    <input type="checkbox" name="delete<?php echo $row['path_id']; ?>" value="1" <?php if($permissions[3]){ echo 'checked'; } ?>>
                    <?php } ?>
                </td>
            
            </tr>
            <?php }} ?>
        
        </table>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    
    
</form>