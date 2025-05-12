<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $data['title_tag']; ?>s</title>
<link href="<?php echo _CSS; ?>posprint.css" rel="stylesheet" type="text/css">
<link href="<?php echo _CSS; ?>jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo _JS; ?>jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>common.js"></script>
<link href="<?php echo _FONTS; ?>fa/css/all.css" rel="stylesheet" type="text/css">
<link href="<?php echo _FONTS; ?>fa/css/all.min.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
<base href="<?php echo _SERVER; ?>">
<script>
window.print();

window.onafterprint = function() {
    
	if (document.referrer) {
		window.location.href = document.referrer;
	} else {
		window.location.href = '<?php echo _SERVER; ?>sales/screen'; // Fallback URL if no referrer
	}
	
};
</script>
</head>

<body>

<div id="maindiv">
<div id="maindivin">
    <table class="table">
    
    	<thead>
        
        	<tr>
    
    		<?php if($data['invoice_logo_print']){ ?>
            <tr>
            
                <td colspan="4">
                
                    <div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
                
                </td>
            
            </tr>
            <?php } ?>
            <tr>
            
                <td colspan="4" id="invoice_head">
                
                	
            		<h1><?php echo $data['companyName']; ?></h1>
                    <p><?php echo $data['invoice_header']; ?></p>
                    <p><?php echo $data['print_by_n_date']; ?></p>
                
                </td>
            
            </tr>
            
            <tr>
            
            	<td id="invoice_head_second" colspan="4">
                
                	<table class="table">
                    	
                        <tr>
            
                            <td class="invoice_head_second_ll">QTE NO</td>
                            <td><?php echo $data['quotation_no']; ?></td>
                        
                        </tr>
                        <tr>
                            
                            <td class="invoice_head_second_ll">DATE</td>
                            <td><?php echo $data['added_date']; ?></td>
                        
                        </tr> 
            
                        <tr>
                        
                            <td class="invoice_head_second_ll">CASHIER</td>
                            <td><?php echo $data['cashier']; ?></td>
                         
                        </tr>
                       
            
                        <tr>
                        
                            <td class="invoice_head_second_ll">CUSTOMER</td>
                            <td colspan="3"><?php echo $data['customer']; ?></td>
                            
                        </tr> 
                    
                    </table>
                
                </td>
                
            </tr> 
        
        </thead>
        
        <tbody>  
    
        	<tr>
        
                <td colspan="4" id="invoice_item_head">
                
                    <table class="table">
                    
                        <thead>
                        
                            <tr>
                            
                                <td class="invoice_item_head_ln">Ln</td>
                                <td class="invoice_item_head_item">Item</td>
                                <td class="invoice_item_head_price">Price</td>
                                <td class="invoice_item_head_qty">Qty</td>
                                <td class="invoice_item_head_amount">Amount</td>
                            
                            </tr>                
                        
                        </thead>
                        
                        <tbody>
                        
                        	<?php
							$i=0;
							$subTotal = 0;
							$totalDiscount = 0;
							foreach($data['invoice_items'] as $ii)
							{
								
								$item_price = $InventoryMasterItemsQuery->data($ii['item_id'],'selling_price');
								
								$i+=1;
								$discount = ($item_price-$ii['final_amount'])*$ii['qty'];
								
								$totalDiscount += $discount;
								$subTotal +=$ii['total'];
								
								?>
                            <tr>
                            
                            	
                                <td class="invoice_item_ln"><?php echo $i; ?></td>
                                <td colspan="4"><?php echo $defCls->showText($InventoryMasterItemsQuery->data($ii['item_id'],'name')); ?></td>
                            	
                            
                            </tr>
                            <tr>
                            
                                <td></td>
                                <td></td>
                                <td class="invoice_item_price"><?php echo $defCls->money($item_price); ?></td>
                                <td class="invoice_item_qty"><?php echo $defCls->num($ii['qty']); ?></td>
                                <td class="invoice_item_amount"><?php echo $defCls->money($ii['total']+$discount); ?></td>
                            
                            </tr>
							<?php }
							
							if($discount>0)
							{
								?>
                            <tr>
                            
                                <td colspan="4">DISCOUNT :</td>
                                <td class="invoice_item_amount">-<?php echo $defCls->money($discount); ?></td>
                            
                            </tr>
                            <?php } ?>
                        
                        </tbody>
                        
                        <tfoot>
                        
                            <tr>
                            
                                <td colspan="4" id="subtotal_l">TOTAL</td>
                                <td id="subtotal_r"><?php echo $defCls->money($subTotal); ?></td>
                            
                            </tr>
                        
                        </tfoot>
                    
                    </table>
                
                </td>
        
        	</tr>
            
            <tr id="qty_pcs">
            
            	<td>NO OF ITEMS</td>
            	<td> : <?php echo $defCls->num($data['no_of_items']); ?></td>
                
            	<td>NO OF QTY</td>
            	<td> : <?php echo $defCls->num($data['no_of_qty']); ?></td>
                
            </tr> 
            
            
            <tr>
            
            	<td colspan="4" id="footer">
						<?php echo $data['invoice_footer']; ?>
                        
                        <br>
                        <br>
                       POWERED BY  WEBIVOX.LK - 0777904054
                </td>
                
            </tr> 
    
    	</tbody>
    
    </table>

</div>
</div>

</body>
</html>