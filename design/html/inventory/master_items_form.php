<h3>Item</h3>


<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_4">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" class="autofocus">
        
        	<option value="">- Choose -</option>
            
            <?php
			foreach($data['category_list'] as $cat){
			?>
            <option value="" disabled style="background:#CCC"><?php echo $cat['name']; ?></option>
            <?php
				$subCat = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id = ".$cat['category_id']." ORDER BY name ASC");
				foreach($subCat as $scat){
				?>
            	<option value="<?php echo $scat['category_id']; ?>" <?php if($data['category_id']==$scat['category_id']){ echo 'selected'; } ?>><?php echo $scat['name']; ?></option>
            <?php }} ?>
        
        </select>
    </div>
    
    <div class="col_4">
        <label for="brand_id">Brand</label>
        <select name="brand_id" id="brand_id" class="brand_id">
        
        	<option value="">- Choose -</option>
            
            <?php
			foreach($data['brand_list'] as $cat){
			?>
            	<option value="<?php echo $cat['brand_id']; ?>" <?php if($data['brand_id']==$cat['brand_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_4">
        <label for="unit_id">Unit</label>
        <select name="unit_id" id="unit_id">
        
        	<option value="">- Choose -</option>
            
            <?php
			foreach($data['unit_list'] as $cat){
			?>
            	<option value="<?php echo $cat['unit_id']; ?>" <?php if($data['unit_id']==$cat['unit_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_4">
        <label for="supplier_id">Supplier</label>
        
        <input type="text" name="supplier_id_txt" id="supplier_id_txt" data-setid="supplier_id" placeholder="" class="supplierAjax">
        <input type="hidden" id="supplier_id" name="supplier_id" />
    </div>
    
    <div class="col_4">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $data['name']; ?>">
    </div>
    
    <div class="col_2">
        <label for="description">Description</label>
        <input type="text" name="description" id="description" placeholder="Description" value="<?php echo $data['description']; ?>">
    </div>
    
    <div class="col_4">
        <label for="barcode">Barcode</label>
        <input type="text" name="barcode" id="barcode" placeholder="Barcode" value="<?php echo $data['barcode']; ?>">
    </div>
    
    <div class="col_4">
        <label for="barcode_name">Barcode Name</label>
        <input type="text" name="barcode_name" id="barcode_name" placeholder="Barcode Name" value="<?php echo $data['barcode_name']; ?>">
    </div>
    
    <div class="col_4">
        <label for="selling_price">Selling Price</label>
        <input type="text" name="selling_price" id="selling_price" placeholder="Selling Price" value="<?php echo $data['selling_price']; ?>">
    </div>
    
    <div class="col_4">
        <label for="minimum_selling_price">Minimum Selling Price</label>
        <input type="text" name="minimum_selling_price" id="minimum_selling_price" placeholder="Minimum Selling Price" value="<?php echo $data['minimum_selling_price']; ?>">
    </div>
    
    <div class="col_4">
        <label for="cost">Cost</label>
        <input type="text" name="cost" id="cost" placeholder="Cost" value="<?php echo $data['cost']; ?>">
    </div>
    
    <div class="col_4">
        <label for="re_order_qty">Reorder Quantity</label>
        <input type="text" name="re_order_qty" id="re_order_qty" placeholder="Reorder Quantity" value="<?php echo $data['re_order_qty']; ?>">
    </div>
    
    <div class="col_4">
        <label for="order_qty">Order Quantity</label>
        <input type="text" name="order_qty" id="order_qty" placeholder="Order Quantity" value="<?php echo $data['order_qty']; ?>">
    </div>
    
    <div class="col_4">
        <label for="minimum_qty">Min Order Quantity</label>
        <input type="text" name="minimum_qty" id="minimum_qty" placeholder="Min Order Quantity" value="<?php echo $data['minimum_qty']; ?>">
    </div>
    
    <div class="col_4">
    
        <label for="unique_no">Unique No</label>
        <select name="unique_no" id="unique_no">
        
        	<option value="1" <?php if($data['unique_no']==1){ echo 'selected'; } ?>>Required</option>
        	<option value="0" <?php if($data['unique_no']==0){ echo 'selected'; } ?>>NO Need</option>
        
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