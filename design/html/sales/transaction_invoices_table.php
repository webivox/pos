<div class="divDataTable">

	<div class="divDataTableIn">
            
        <div class="hrow">
        
            <div class="col-10 text-center">Action</div>
            <div class="col-10 text-center">Print</div>
            <div class="col-10">No</div>
            <div class="col-10">Date</div>
            <div class="col-20">Customer</div>
            <div class="col-20">Location</div>
            <div class="col-10">User</div>
            <div class="col-10 text-right">Total</div>
        
        
        </div>
    
        <?php
        
        foreach($data['return'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10 action">
            
                
                <?php if($cat['status']==1){ ?>
                <select class="invoicestatuschange" id="invstatus<?php echo $cat['invoice_id']; ?>" data-id="<?php echo $cat['invoice_id']; ?>">
                
                    <option value="1" <?php if($cat['status']==1){ echo 'selected'; } ?>>Completed</option>
                    <option value="0" <?php if($cat['status']==0){ echo 'selected'; } ?>>Cancelled</option>
                
                </select>
                <?php }else{ echo 'Cancelled'; } ?>
            
            </div>
        
            <div class="col-10 action">
            
            	
            
                <a class="btn btn-black open_popup_form" href="<?php echo $cat['printURL']; ?>"><i class="fa-light fa-print"></i></a>
                
            
            </div>
            <div class="col-10"><?php echo $cat['invoice_no']; ?></div>
            <div class="col-10"><?php echo $cat['added_date']; ?></div>
            <div class="col-20"><?php echo $cat['customer']; ?></div>
            <div class="col-20"><?php echo $cat['location']; ?></div>
            <div class="col-10"><?php echo $cat['user']; ?></div>
            <div class="col-10 text-right"><?php echo $cat['total_sale']; ?></div>
        
        </div>
        <?php } ?>
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>