<div class="divDataTable">

	<div class="divDataTableIn">
            
        <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-40">Name</div>
            <div class="col-50">Value</div>
        
        </div>
    
        <?php
        
        foreach($data['master'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_small"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-40"><?php echo $cat['key']; ?></div>
            <div class="col-50"><?php echo $cat['values']; ?></div>
        
        </div>
        <?php } ?>
    
      
	</div>    
    
    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>