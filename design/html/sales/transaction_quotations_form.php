<h3>Quotation</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_3">
    	<label for="quotation_no">Quotation No</label>
        <input type="text" id="quotation_no" value="<?php echo $data['quotation_no']; ?>" disabled>
    </div>
    
    
    <div class="col_3">
        <label for="customer_id">Customer <a class="open_popup_form_sub label_create_btn" data-url="<?php echo $data['customer_create_url']; ?>" data-formsizeclass="popup_form_in_size_large_sub">[Create]</a></label>
        
        <input type="text" name="customer_id_txt" id="customer_id_txt" data-setid="customer_id" placeholder="" class="customerAjax" value="<?php echo $data['customer_id_txt']; ?>">
        <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $data['customer_id']; ?>" />
        
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
        <label for="added_date">Added Date</label>
        <input type="text" name="added_date" id="added_date" value="<?php echo $data['added_date']; ?>" class="dateField">
    </div>
    
    <div class="col_2">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="<?php echo $data['remarks']; ?>">
    </div>
    
    
    
    <div class="col_1">
    
    	<div id="addingItemTable">
    
    
    	<table class="multi-table">
        
        	<thead>
            
            	<tr>
            
            		<td style="width:50px">No</td>
            		<td>Item Name</td>
            		<td style="width:75px">Amount</td>
            		<td style="width:75px">Discount</td>
            		<td style="width:85px">Final Amount</td>
            		<td style="width:75px">Qty</td>
            		<td style="width:85px">Total</td>
            		<td style="width:55px">Action</td>
            
            	</tr>
            
            </thead>  
        
        	<tbody id="addedItemsList">
            
            	<tr>
            
            		<td><input type="text" id="no" name="no" disabled="disabled" value="<?php echo $data['no_of_items']+1; ?>" /></td>
            		<td>
                    	<input type="text" id="item_name" name="item_name" class="itemAjax" data-sendcustomer="customer_id" data-focus="qty" data-setid="item_name_id" data-priceset="amount" />
                    	<input type="hidden" id="item_name_id" name="item_name_id" />
                        
                    </td>
            		<td><input type="text" id="amount" name="amount" class="text-right addlinechange" value="" /></td>
            		<td><input type="text" id="discount" name="discount" class="text-right addlinechange" value="" /></td>
            		<td><input type="text" id="finalamount" name="finalamount" class="text-right" value="" disabled="disabled" /></td>
            		<td><input type="text" id="qty" name="qty" class="text-right addlinechange" value="" /></td>
            		<td><input type="text" id="total" name="total" class="text-right addlinechange" value="" disabled="disabled" /></td>
            		<td><a class="btn btn-primary" id="addItem" accesskey="a">+</a></td>
            
            	</tr>
                
                <?php
				$ino = 0;
				foreach($data['item_lists'] as $i)
				{
					$ino+=1;
					
					?>
                    <tr id="rowe<?php echo $i['quotation_item_id']; ?>" class="linerows">
            
                        <td><input type="text" id="eno<?php echo $i['quotation_item_id']; ?>" name="no" disabled="disabled" value="<?php echo $ino; ?>" /></td>
                        <td><input type="text" id="eitem_name<?php echo $i['quotation_item_id']; ?>" disabled name="eitem_name<?php echo $i['quotation_item_id']; ?>" value="<?php echo $InventoryMasterItemsQuery->data($i['item_id'],'name'); ?>" /></td>
                        
                    
                        <td><input type="text" id="eamount<?php echo $i['quotation_item_id']; ?>" name="eamount<?php echo $i['quotation_item_id']; ?>" class="text-right editlinechange eamount" value="<?php echo $defCls->num($i['amount']); ?>" /></td>
                        <td><input type="text" id="ediscount<?php echo $i['quotation_item_id']; ?>" name="ediscount<?php echo $i['quotation_item_id']; ?>" class="text-right editlinechange ediscount" value="<?php echo $defCls->num($i['discount']); ?>" /></td>
                        <td><input type="text" id="efinalamount<?php echo $i['quotation_item_id']; ?>" name="efinalamount<?php echo $i['quotation_item_id']; ?>" class="text-right efinalefinalamount" disabled="disabled" value="<?php echo $defCls->num($i['final_amount']); ?>" /></td>
                        <td><input type="text" id="eqty<?php echo $i['quotation_item_id']; ?>" name="eqty<?php echo $i['quotation_item_id']; ?>" class="text-right editlinechange eqty" value="<?php echo $defCls->num($i['qty']); ?>" /></td>
                        <td><input type="text" id="etotal<?php echo $i['quotation_item_id']; ?>" name="etotal<?php echo $i['quotation_item_id']; ?>" disabled class="text-right etotal" value="<?php echo $defCls->num($i['total']); ?>" /></td>
                        <td><a class="btn btn-danger removeItem" data-id="e<?php echo $i['quotation_item_id']; ?>"><i class="fa-light fa-trash-xmark"></i></a></td>
                
                    </tr>
                <?php } ?>
            
            </tbody>
            
        	<tfoot>
            
            	<tr>
                
                	<td><input type="text" disabled="disabled" id="totalNoOfItems" value="<?php echo $data['no_of_items']; ?>" /></td>
                	<td colspan="4">Total</td>
                    <td><input type="text" disabled="disabled" id="totalQty" class="text-right" value="<?php echo $data['no_of_qty']; ?>" /></td>
                    <td class="text-right"><input type="text" disabled="disabled" id="totalTotal" class="text-right" value="<?php echo $data['total_tiotal']; ?>" /></td>
                    <td></
                
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