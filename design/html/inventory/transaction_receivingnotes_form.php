<h3>Receiving Note</h3>

<form method="post" id="saveForm" data-url="<?php echo $data['form_url']; ?>">
        
<div id="popup_form_in_form_in">  
    
    <div class="col_4">
    	<label for="rn_id">RN No</label>
        <input type="text" id="rn_id" value="<?php echo $data['rn_no']; ?>" disabled>
    </div>
    
    <div class="col_4">
    	<label for="po_id">PO No</label>
        <input type="text" id="po_id" value="<?php echo $data['po_no']; ?>" disabled>
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
        <label for="supplier_id">Supplier ID <a class="open_popup_form_sub label_create_btn" data-url="<?php echo $data['supplier_create_url']; ?>" data-width="950" data-height="450">[Create]</a></label>
        
        <input type="text" name="supplier_id_txt" id="supplier_id_txt" data-setid="supplier_id" placeholder="" class="supplierAjax" value="<?php echo $data['supplier_id_txt']; ?>">
        <input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo $data['supplier_id']; ?>" />
        
    </div>
    
    <div class="col_4">
        <label for="added_date">Added Date</label>
        <input type="text" name="added_date" id="added_date" value="<?php echo $data['added_date']; ?>" class="dateField">
    </div>
    
    <div class="col_4">
        <label for="invoice_no">Invoice No</label>
        <input type="text" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo $data['invoice_no']; ?>">
    </div>
    
    <div class="col_4">
        <label for="due_date">Due Date</label>
        <input type="text" name="due_date" id="due_date" value="<?php echo $data['due_date']; ?>" class="dateField">
    </div>
    
    <div class="col_4">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="<?php echo $data['remarks']; ?>">
    </div>
    
    
    
    <div class="col_1">
    
    	<div id="addingItemTable">
    
            <table class="multi-table">
            
                <thead>
                
                    <tr>
                
                        <td style="width:50px">No</td>
                        <td>Item Name <a class="open_popup_form_sub label_create_btn" data-url="<?php echo $data['item_create_url']; ?>" data-width="950" data-height="450">[Create a New]</a></td>
                        <td style="width:75px">Qty</td>
                        <td style="width:85px">Amount</td>
                        <td style="width:85px">Buying Price</td>
                        <td style="width:65px">Discount</td>
                        <td style="width:85px">Final Price</td>
                        <td style="width:95px">Total</td>
                        <td style="width:55px">Action</td>
                
                    </tr>
                
                </thead>  
            
                <tbody id="addedItemsList">
                
                    <tr>
                
                        <td><input type="text" id="no" name="no" disabled="disabled" value="<?php echo $data['no_of_items']+1; ?>" /></td>
                        <td>
                            <input type="text" id="item_name" name="item_name" class="itemAjax" data-focus="qty" data-setid="item_name_id" />
                            <input type="hidden" id="item_name_id" name="item_name_id" />
                            
                        </td>
                        <td><input type="text" id="qty" name="qty" class="text-right addlinechange" value="" /></td>
                        <td><input type="text" id="amount" name="amount" class="text-right addlinechange" value="" /></td>
                        <td><input type="text" id="buying_price" name="buying_price" class="text-right addlinechange" value="" /></td>
                        <td><input type="text" id="discount" name="discount" class="text-right addlinechange" value="0" /></td>
                        <td><input type="text" id="final_price" name="final_price" class="text-right" disabled="disabled" value="" /></td>
                        <td><input type="text" id="total" name="total" disabled value="" class="text-right" value="" /></td>
                        <td><a class="btn btn-primary" id="addItem" accesskey="a">+</a></td>
                
                    </tr>
                    
                    <?php
                    $ino = 0;
                    $total_saved = 0;
                    foreach($data['item_lists'] as $i)
                    {
                        $ino+=1;
                        $lineDisc = $i['buying_price']*$i['discount']/100;
                        $total_saved += ($i['price']-$i['final_price'])*$i['qty'];
                        ?>
                        <tr id="rowe<?php echo $i['receiving_note_item_id']; ?>" class="linerows">
                
                            <td><input type="text" id="eno<?php echo $i['receiving_note_item_id']; ?>" name="no" disabled="disabled" value="<?php echo $ino; ?>" /></td>
                            <td><input type="text" id="eitem_name<?php echo $i['receiving_note_item_id']; ?>" disabled name="eitem_name<?php echo $i['receiving_note_item_id']; ?>" value="<?php echo $InventoryMasterItemsQuery->data($i['item_id'],'name'); ?>" /></td>
                            <td><input type="text" id="eqty<?php echo $i['receiving_note_item_id']; ?>" name="eqty<?php echo $i['receiving_note_item_id']; ?>" class="text-right editlinechange eqty" value="<?php echo $defCls->num($i['qty']); ?>" /></td>
                            <td><input type="text" id="eamount<?php echo $i['receiving_note_item_id']; ?>" name="eamount<?php echo $i['receiving_note_item_id']; ?>" class="text-right editlinechange eprice" value="<?php echo $defCls->num($i['price']); ?>" /></td>
                            <td><input type="text" id="ebuying_price<?php echo $i['receiving_note_item_id']; ?>" name="ebuying_price<?php echo $i['receiving_note_item_id']; ?>" class="text-right editlinechange ebuying_price" value="<?php echo $defCls->num($i['buying_price']); ?>" /></td>
                            <td><input type="text" id="ediscount<?php echo $i['receiving_note_item_id']; ?>" name="ediscount<?php echo $i['receiving_note_item_id']; ?>" class="text-right editlinechange ediscount" value="<?php echo $defCls->num($i['discount']); ?>" /></td>
                            <td><input type="text" id="efinal_price<?php echo $i['receiving_note_item_id']; ?>" name="efinal_price<?php echo $i['receiving_note_item_id']; ?>" class="text-right efinal_price" disabled="disabled" value="<?php echo $defCls->num($i['final_price']); ?>" /></td>
                            <td><input type="text" id="etotal<?php echo $i['receiving_note_item_id']; ?>" name="etotal<?php echo $i['receiving_note_item_id']; ?>" disabled class="text-right etotal" value="<?php echo $defCls->num($i['total']); ?>" /></td>
                            <td><a class="btn btn-danger removeItem" data-id="e<?php echo $i['receiving_note_item_id']; ?>"><i class="fa-light fa-trash-xmark"></i></a></td>
                    
                        </tr>
                    <?php } ?>
                
                </tbody>
                
                <tfoot>
                
                    <tr>
                
                        <td colspan="2"></td>
                        <td colspan="1"><input type="text" id="bottom_total_qty" disabled value="<?php echo $defCls->num($data['no_of_qty']); ?>" class="text-right" /></td>
                        <td colspan="1"></td>
                        <td class="text-right">Total Save &nbsp;</td>
                        <td><input type="text" id="bottom_total_save" disabled value="<?php echo $defCls->num($total_saved); ?>" class="text-right" /></td>
                        <td class="text-right">Total &nbsp;</td>
                        <td><input type="text" id="bottom_total" disabled value="<?php echo $defCls->num($data['total_value']); ?>" class="text-right" /></td>
                        <td></td>
                
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