<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-20">No</div>
            <div class="col-10">Date</div>
            <div class="col-60">Location</div>
        
        </div>
    
        <?php
        
        foreach($data['adjustmentnotes'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-20"><?php echo $cat['adjustment_no']; ?></div>
            <div class="col-10"><?php echo $cat['added_date']; ?></div>
            <div class="col-60"><?php echo $cat['location']; ?></div>
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-width="1024" data-height="550"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
        
        </div>
        <?php } ?>
    
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>