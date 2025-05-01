<div class="divDataTable">
            
    <div class="hrow">
    
        <div class="col-80">Name</div>
        <div class="col-10 text-center">Status</div>
        <div class="col-10 text-center">Action</div>
    
    </div>

	<?php
    
	foreach($data['cashierpoints'] as $cat)
	{
	?>
    <div class="row rowhover">
    
        <div class="col-80"><?php echo $cat['name']; ?></div>
        <div class="col-10 text-center status"><?php echo $cat['status']; ?></div>
        <div class="col-10 action">
        
            <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-width="1024" data-height="540"><i class="fa-light fa-pen-to-square"></i></a>
            
        
            <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
        
        </div>
    
    </div>
    <?php } ?>

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
    
</div>