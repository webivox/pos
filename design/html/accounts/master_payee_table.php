<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-80">Name</div>
            <div class="col-10 text-center">Status</div>
            <div class="col-10 text-center">Action</div>
        
        </div>
    
        <?php
        
        foreach($data['payee'] as $payee)
        {
        ?>
        <div class="row rowhover" id="rowLine<?php echo $payee['payee_id']; ?>">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $payee['updateURL']; ?>" data-formsizeclass="popup_form_in_size_small"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a data-url="<?php echo $payee['deleteURL']; ?>" data-id="<?php echo $payee['payee_id']; ?>" class="btn btn-danger delete"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-80"><?php echo $payee['name']; ?></div>
            <div class="col-10 text-center status"><?php echo $payee['status']; ?></div>
        
        </div>
        <?php } ?>
    
        <div class="row">
        
            <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
        
        </div>
    
	</div>    
</div>