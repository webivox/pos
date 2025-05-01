<div class="divDataTable">
            
    <div class="hrow">
    
        <div class="col-20">Gift Card No</div>
        <div class="col-20">Expiry Date</div>
        <div class="col-20">Amount</div>
        <div class="col-20">Used Amount</div>
        <div class="col-20">Balance Amount</div>
    
    </div>

	<?php
    
	foreach($data['gc'] as $gc)
	{
	?>
    <div class="row rowhover">
    
        <div class="col-20"><?php echo $gc['no']; ?></div>
        <div class="col-20"><?php echo $gc['expiry_date']; ?></div>
        <div class="col-20"><?php echo $gc['amount']; ?></div>
        <div class="col-20"><?php echo $gc['used_amount']; ?></div>
        <div class="col-20"><?php echo $gc['balance_amount']; ?></div>
        
    
    </div>
    <?php } ?>

    <div class="row">
    
        <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
    
    </div>
    
</div>