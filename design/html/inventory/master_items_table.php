<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
        
            <div class="col-20 text-center">Action</div>
            <div class="col-30">Name</div>
            <div class="col-10">Barcode</div>
            <div class="col-20">Category</div>
            <div class="col-10">Stock</div>
            <div class="col-10 text-center">Status</div>
        
        </div>
    
        <?php
        
        foreach($data['items'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-20 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_large"><i class="fa-light fa-pen-to-square"></i></a>
            
                <a class="btn btn-black open_popup_form" data-url="<?php echo $cat['updatePriceURL']; ?>" data-formsizeclass="popup_form_in_size_small"><i class="fa-light fa-money-check-dollar-pen"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-30"><?php echo $cat['name']; ?></div>
            <div class="col-10"><?php echo $cat['barcode']; ?></div>
            <div class="col-20"><?php echo $cat['category']; ?></div>
            <div class="col-10"><?php echo $cat['stock']; ?></div>
            <div class="col-10 text-center status"><?php echo $cat['status']; ?></div>
        
        </div>
        <?php } ?>
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>