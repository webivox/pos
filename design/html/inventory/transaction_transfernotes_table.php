<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-10">No</div>
            <div class="col-10">Date</div>
            <div class="col-30">Location From</div>
            <div class="col-30">Location To</div>
            <div class="col-10 text-right">No of Item/Qty</div>
        
        </div>
    
        <?php
        
        foreach($data['transfernotes'] as $cat)
        {
        ?>
        <div class="row rowhover" id="rowLine<?php echo $cat['transfer_note_id']; ?>">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_large"><i class="fa-light fa-pen-to-square"></i></a>
            
                <a class="btn btn-black" href="<?php echo $cat['printURL']; ?>" target="_blank"><i class="fa-light fa-print"></i></a>
                
            
                <a data-url="<?php echo $cat['deleteURL']; ?>" data-id="<?php echo $cat['transfer_note_id']; ?>" class="btn btn-danger delete"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-10"><?php echo $cat['transfer_no']; ?></div>
            <div class="col-10"><?php echo $cat['added_date']; ?></div>
            <div class="col-30"><?php echo $cat['location_from']; ?></div>
            <div class="col-30"><?php echo $cat['location_to']; ?></div>
            <div class="col-10 text-right"><?php echo $cat['items']; ?></div>
        
        </div>
        <?php } ?>
    
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>