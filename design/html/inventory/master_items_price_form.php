<h3>Item Prices</small></h3>


<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <table class="table">
    
    	<thead>
        
        	<tr class="text-center">
            
            	<td>Selling Price</td>
                <td>Min.SellingPrice</td>
            
            </tr>
        
        	<tr class="text-center" style="background:#FFF;">
            
            	<td>Rs.<?php echo $data['sellingPrice']; ?></td>
                <td>Rs.<?php echo $data['minimumSellingPrice']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Group Name</td>
                <td>Price</td>
            
            </tr>
        
        </thead>
    
    	<tbody>
        
        	<?php
			foreach($data['customer_group_list'] as $cgp)
			{
				?>
        	<tr>
            
            	<td><?php echo $defCls->showText($cgp['name']); ?></td>
                <td><input type="number" name="price<?php echo $cgp['customer_group_id']; ?>" value="<?php echo $cgp['price']; ?>"></td>
            
            </tr>
            <?php } ?>
        
        </tbody>
    
    
    </table>
   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_1">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div>   
    
</form>