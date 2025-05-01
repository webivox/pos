<div class="divDataTable">
            
    <div class="hrow">
    
        <div class="col-10">No</div>
        <div class="col-10">Date</div>
        <div class="col-20">Supplier</div>
        <div class="col-10 text-right">Amount</div>
        <div class="col-40">Details</div>
        <div class="col-10 text-center">Action</div>
    
    </div>

	<?php
    
	foreach($data['payments'] as $cat)
	{
	?>
    <div class="row rowhover">
    
        <div class="col-10"><?php echo $cat['payment_no']; ?></div>
        <div class="col-10"><?php echo $cat['added_date']; ?></div>
        <div class="col-20"><?php echo $cat['supplier_id']; ?></div>
        <div class="col-10 text-right"><?php echo $cat['amount']; ?></div>
        <div class="col-40"><?php echo $cat['details']; ?></div>
        <div class="col-10 action">
        
            <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-width="650" data-height="380"><i class="fa-light fa-pen-to-square"></i></a>
            
        
            <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
        
        </div>
    
    </div>
    <?php } ?>

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
    
</div>