<div class="divDataTable">
            
    <div class="hrow">
    
        <div class="col-10">No</div>
        <div class="col-10">Date</div>
        <div class="col-50">Supplier</div>
        <div class="col-10 text-right">No of Item/Qty</div>
        <div class="col-10 text-right">Total</div>
        <div class="col-10 text-center">Action</div>
    
    </div>

	<?php
    
	foreach($data['receivingnotes'] as $cat)
	{
	?>
    <div class="row rowhover">
    
        <div class="col-10"><?php echo $cat['rn_no']; ?></div>
        <div class="col-10"><?php echo $cat['added_date']; ?></div>
        <div class="col-50"><?php echo $cat['supplier_id']; ?></div>
        <div class="col-10 text-right"><?php echo $cat['items']; ?></div>
        <div class="col-10 text-right"><?php echo $cat['total_value']; ?></div>
        <div class="col-10 action">
        
            <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-width="1024" data-height="550"><i class="fa-light fa-pen-to-square"></i></a>
            
        
            <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
        
        </div>
    
    </div>
    <?php } ?>

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
    
</div>