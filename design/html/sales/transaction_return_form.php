<h3>Return Note</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_4">
    	<label for="rn_id">SRN No</label>
        <input type="text" id="rn_id" value="<?php echo $data['srn_no']; ?>" disabled>
    </div>
    
    <div class="col_4">
        <label for="location_id">Location</label>
        <select name="location_id" id="location_id" class="autofocus">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['location_list'] as $cat){
			?>
            <option value="<?php echo $cat['location_id']; ?>" <?php if($data['location_id']==$cat['location_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_4">
        <label for="customer_id">Customer ID</label>
        <select name="customer_id" id="customer_id">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['customer_list'] as $cat){
			?>
            <option value="<?php echo $cat['customer_id']; ?>" <?php if($data['customer_id']==$cat['customer_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
    </div>
    
    <div class="col_4">
        <label for="added_date">Added Date</label>
        <input type="text" name="added_date" id="added_date" value="<?php echo $data['added_date']; ?>" class="dateField okeyboard">
    </div>
    
    <div class="col_4">
        <label for="invoice_no">Invoice No</label>
        <input type="text" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo $data['invoice_no']; ?>" class="okeyboard">
    </div>
    
    <div class="col_2">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="<?php echo $data['remarks']; ?>" class="okeyboard">
    </div>
    
    
    
    <div class="col_1">
    
    	<div id="addingItemTable">
        
    	<table class="multi-table">
        
        	<thead>
            
            	<tr>
            
            		<td style="width:50px">No</td>
            		<td>Item Name</td>
            		<td style="width:85px">Cost</td>
            		<td style="width:85px">Amount</td>
            		<td style="width:75px">Qty</td>
            		<td style="width:95px">Total</td>
            		<td style="width:55px">Action</td>
            
            	</tr>
            
            </thead>  
        
        	<tbody id="addedItemsList">
            
            	<tr>
            
            		<td><input type="text" id="no" name="no" disabled="disabled" value="<?php echo $data['no_of_items']+1; ?>" /></td>
            		<td>
                    	<input type="text" id="item_name" name="item_name" class="itemAjax okeyboard" data-focus="cost" data-setid="item_name_id" data-sendcustomer="customer_id" data-focus="qty" data-setid="item_name_id" data-priceset="amount" data-costset="cost" />
                    	<input type="hidden" id="item_name_id" name="item_name_id" />
                        
                    </td>
            		<td><input type="text" id="cost" name="cost" class="text-right okeyboard" value="" /></td>
            		<td><input type="text" id="amount" name="amount" class="text-right addlinechange okeyboard" value="" /></td>
            		<td><input type="text" id="qty" name="qty" class="text-right addlinechange okeyboard" value="" /></td>
            		<td><input type="text" id="total" name="total" disabled value="" class="text-right" value="" /></td>
            		<td><a class="btn btn-primary" id="addItem" accesskey="a">+</a></td>
            
            	</tr>
                
                <?php
				$ino = 0;
				foreach($data['item_lists'] as $i)
				{
					$ino+=1;
					?>
                    <tr id="rowe<?php echo $i['sales_return_item_id']; ?>" class="linerows">
            
                        <td><input type="text" id="eno<?php echo $i['sales_return_item_id']; ?>" name="no" disabled="disabled" value="<?php echo $ino; ?>" /></td>
                        <td><input type="text" id="eitem_name<?php echo $i['sales_return_item_id']; ?>" disabled name="eitem_name<?php echo $i['sales_return_item_id']; ?>" value="<?php echo $InventoryMasterItemsQuery->data($i['item_id'],'name'); ?>" class="okeyboard" /></td>
                        <td><input type="text" id="ecost<?php echo $i['sales_return_item_id']; ?>" name="ecost<?php echo $i['sales_return_item_id']; ?>" class="text-right okeyboard" value="<?php echo $defCls->num($i['cost']); ?>" /></td>
                        <td><input type="text" id="eamount<?php echo $i['sales_return_item_id']; ?>" name="eamount<?php echo $i['sales_return_item_id']; ?>" class="text-right editlinechange eprice okeyboard" value="<?php echo $defCls->num($i['price']); ?>" /></td>
                        <td><input type="text" id="eqty<?php echo $i['sales_return_item_id']; ?>" name="eqty<?php echo $i['sales_return_item_id']; ?>" class="text-right editlinechange eqty okeyboard" value="<?php echo $defCls->num($i['qty']); ?>" /></td>
                        
                        <td><input type="text" id="etotal<?php echo $i['sales_return_item_id']; ?>" name="etotal<?php echo $i['sales_return_item_id']; ?>" disabled class="text-right etotal" value="<?php echo $defCls->num($i['total']); ?>" /></td>
                        <td><a class="btn btn-danger removeItem" data-id="e<?php echo $i['sales_return_item_id']; ?>"><i class="fa-light fa-trash-xmark"></i></a></td>
                
                    </tr>
                <?php } ?>
            
            </tbody>
            
            <tfoot>
            
            	<tr>
            
            		<td colspan="3"></td>
            		<td class="text-right">Total &nbsp;</td>
            		<td colspan="1"><input type="text" id="bottom_total_qty" disabled value="<?php echo $defCls->num($data['no_of_qty']); ?>" class="text-right" /></td>
            		<td><input type="text" id="bottom_total" disabled value="<?php echo $defCls->num($data['total_value']); ?>" class="text-right" /></td>
            
            	</tr>
            
            </tfoot>
        
        
        </table>
    	</div>
    
    </div>

   
</div> 

<div id="popup_form_in_form_button">
    <div class="col_4">
    
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
    
    </div>
</div> 
    


</form>

<?php if($data['val']){ ?>
<script type="text/javascript" src="<?php echo _JS; ?>core/sales_rn.js"></script>
<?php } ?>