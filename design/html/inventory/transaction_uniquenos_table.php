<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-10">Added Date</div>
            <div class="col-10">Used Date</div>
            <div class="col-10">Used Invoice No</div>
            <div class="col-30">Item Name</div>
            <div class="col-20">Unique No</div>
            <div class="col-10 text-center">Status</div>
        
        </div>
    
        <?php
        
        foreach($data['uniquenos'] as $cat)
        {
        ?>
        <div class="row rowhover" id="rowLine<?php echo $cat['unique_id']; ?>">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_small"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a data-url="<?php echo $cat['deleteURL']; ?>" data-id="<?php echo $cat['unique_id']; ?>" class="btn btn-danger delete"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-10"><?php echo $cat['date']; ?></div>
            <div class="col-10"><?php echo $cat['usedDate']; ?></div>
            <div class="col-10"><?php echo $cat['InvoiceNo']; ?></div>
            <div class="col-30"><?php echo $cat['item']; ?></div>
            <div class="col-20"><?php echo $cat['unique_no']; ?></div>
            <div class="col-10 text-center status"><?php echo $cat['status']; ?></div>
        
        </div>
        <?php } ?>
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>