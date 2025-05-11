<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10">No</div>
            <div class="col-20">Date</div>
            <div class="col-20">Account From</div>
            <div class="col-20">Account To</div>
            <div class="col-20 text-right">Amount</div>
            <div class="col-10 text-center">Action</div>
        
        </div>
    
        <?php
        
        foreach($data['transfers'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $cat['updateURL']; ?>" data-formsizeclass="popup_form_in_size_medium"><i class="fa-light fa-pen-to-square"></i></a>
            
                <a class="btn btn-black open_popup_form" href="<?php echo $cat['printURL']; ?>" target="_blank"><i class="fa-light fa-print"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-10"><?php echo $cat['transfer_no']; ?></div>
            <div class="col-20"><?php echo $cat['added_date']; ?></div>
            <div class="col-20"><?php echo $cat['account_from']; ?></div>
            <div class="col-20"><?php echo $cat['account_to']; ?></div>
            <div class="col-20 text-right"><?php echo $cat['amount']; ?></div>
        
        </div>
        <?php } ?>
    
        <div class="row">
        
            <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
        
        </div>
        
	</div>    
</div>