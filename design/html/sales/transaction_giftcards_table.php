<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10 text-center">Action</div>
            <div class="col-20">Gift Card No</div>
            <div class="col-10">Expiry Date</div>
            <div class="col-20">Amount</div>
            <div class="col-20">Used Amount</div>
            <div class="col-20">Balance Amount</div>
        
        </div>
    
        <?php
        
        foreach($data['gc'] as $gc)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10 action">
            
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $gc['updateURL']; ?>" data-width="420" data-height="380"><i class="fa-light fa-pen-to-square"></i></a>
                
            
                <a href="" class="btn btn-danger"><i class="fa-light fa-trash-can"></i></a>
            
            </div>
            <div class="col-20"><?php echo $gc['no']; ?></div>
            <div class="col-10"><?php echo $gc['expiry_date']; ?></div>
            <div class="col-20"><?php echo $gc['amount']; ?></div>
            <div class="col-20"><?php echo $gc['used_amount']; ?></div>
            <div class="col-20"><?php echo $gc['balance_amount']; ?></div>
            
        
        </div>
        <?php } ?>
    
        
	</div>    

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
</div>