<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
        
            <div class="col-60">Name</div>
            <div class="col-20 text-center">Balance</div>
            <div class="col-10 text-center">Status</div>
            <div class="col-10 text-center">Action</div>
        
        </div>
    
        <?php
        
        foreach($data['accounts'] as $cat)
        {
        ?>
        <div class="row rowhover" id="rowLine<?php echo $cat['account_id']; ?>">
        
            <div class="col-60"><?php echo $cat['name']; ?></div>
            <div class="col-20 text-right"><?php echo $cat['balance']; ?></div>
            <div class="col-10 text-center status"><?php echo $cat['status']; ?></div>
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_small"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a data-url="<?php echo $cat['deleteURL']; ?>" data-id="<?php echo $cat['account_id']; ?>" class="btn btn-danger delete"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
        
        </div>
        <?php } ?>
    
        <div class="row">
        
            <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
        
        </div>
	</div>    
</div>