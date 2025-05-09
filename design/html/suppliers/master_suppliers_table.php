<div class="divDataTable">

	<div class="divDataTableIn">
            
        <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-60">Name</div>
            <div class="col-10">Contact Person</div>
            <div class="col-10">Phone No</div>
            <div class="col-10 text-center">Status</div>
        
        </div>
    
        <?php
        
        foreach($data['suppliers'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-width="1024" data-height="490"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-60"><?php echo $cat['name']; ?></div>
            <div class="col-10"><?php echo $cat['contact_person']; ?></div>
            <div class="col-10"><?php echo $cat['phone_number']; ?></div>
            <div class="col-10 text-center status"><?php echo $cat['status']; ?></div>
        
        </div>
        <?php } ?>
    
	</div>    
    
    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>