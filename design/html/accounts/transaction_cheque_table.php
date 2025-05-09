<div class="divDataTable">

	<div class="divDataTableIn">
            
         <div class="hrow">
    
            <div class="col-10">Cheque Date</div>
            <div class="col-10">Added Date</div>
            <div class="col-10">Cheque No</div>
            <div class="col-10">Bank/Acc Code</div>
            <div class="col-10">Amount</div>
            <div class="col-10">Remarks</div>
            <div class="col-10">Type</div>
            <div class="col-10">Realized Date</div>
            <div class="col-10">Dep Acc</div>
            <div class="col-10 text-center">Action</div>
        
        </div>
    
        <?php
        
        foreach($data['cheques'] as $cat)
        {
        ?>
        <div class="row rowhover">
        
            <div class="col-10"><?php echo $cat['cheque_date']; ?></div>
            <div class="col-10"><?php echo $cat['added_date']; ?></div>
            <div class="col-10"><?php echo $cat['cheque_no']; ?></div>
            <div class="col-10"><?php echo $cat['bank_code']; ?></div>
            <div class="col-10"><?php echo $cat['amount']; ?></div>
            <div class="col-10"><?php echo $cat['remarks']; ?></div>
            <div class="col-10" ><?php echo $cat['type']; ?></div>
            
            <div class="col-10"><?php if($cat['status']=='Pending'){ ?><input type="text" value="<?php echo $cat['added_date']; ?>" id="realized_date<?php echo $cat['cheque_id']; ?>" style="height:35px;" class="dateField" /><?php } ?></div>
            
            <div class="col-10" id="statusTxt<?php echo $cat['cheque_id']; ?>">
            
                <?php if($cat['status']=='Pending' && $cat['type']=='Received'){ ?>
                <select name="account_id<?php echo $cat['cheque_id']; ?>" id="account_id<?php echo $cat['cheque_id']; ?>" style="height:35px;">
                    
                    <option value="">- Choose -</option>
                    <?php
                    foreach($data['account_list'] as $a){
                    ?>
                    <option value="<?php echo $a['account_id']; ?>" <?php if($cat['deposited_account_id']==$a['account_id']){ echo 'selected'; } ?>><?php echo $a['name']; ?></option>
                    <?php } ?>
                
                </select>
                <?php }else{ echo $cat['status']; } ?>
                
            </div>
            
            
            <div class="col-10 action">
            
                <?php if($cat['status']=='Pending'){ ?>
                <a data-id="<?php echo $cat['cheque_id']; ?>" id="actionBtn<?php echo $cat['cheque_id']; ?>" class="btn btn-success changeStatus"><i class="fa fa-check" aria-hidden="true"></i></a>
                <?php }else{ ?>
                <a data-id="<?php echo $cat['cheque_id']; ?>" id="actionBtn<?php echo $cat['cheque_id']; ?>" class="btn btn-black revertStatus" style="line-height:13px !important;">Revert</a>
                <?php } ?>
            
            </div>
            
        
        </div>
        <?php } ?>
    
        <div class="row">
        
            <div class="col-100"><?php echo $data['showing_text']; ?><?php echo $data['pagination_html']; ?></div>
        
        </div>
        
	</div>    
</div>