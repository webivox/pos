<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $data['title_tag']; ?></title>
<link href="<?php echo _CSS; ?>a5printportrait.css" rel="stylesheet" type="text/css">
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

<style>
@media print {
    .invoice-page {
        page-break-after: always;
    }
	
    .invoice-page:last-child {
        page-break-after: auto;
    }
}
</style>

</head>

<body>
<div id="cont">

<div id="maindiv">

	<div id="first_row">
    
   		<div id="logo"> <?php if($data['invoice_logo_print']){ ?><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"><?php } ?></div> 
        
        <div id="company">
        
            <h1><?php echo $data['companyName']; ?></h1>
            <p><?php echo $data['invoice_header']; ?></p>
        
        </div>
    
    </div>
    
    
    <div id="second_row">
    
    	<div id="invoice_to">
        
        	<h4>INVOICE TO:</h4>
            <p><?php echo $data['customer']; ?></p>
        
        </div>
    
    	<div id="invoice_details">
        
        	<div class="invoice_details_row">
            
            	<div class="invoice_details_row_l">INVOICE NO</div>
            	<div class="invoice_details_row_r"><?php echo $data['invoice_no']; ?></div>
            
            </div>
        
        	<div class="invoice_details_row">
            
            	<div class="invoice_details_row_l">DATE</div>
            	<div class="invoice_details_row_r"><?php echo $data['added_date']; ?></div>
            
            </div>
        
        	<div class="invoice_details_row">
            
            	<div class="invoice_details_row_l">CASHIER</div>
            	<div class="invoice_details_row_r"><?php echo $data['cashier']; ?></div>
            
            </div>
        
        	<div class="invoice_details_row">
            
            	<div class="invoice_details_row_l">SALES REP</div>
            	<div class="invoice_details_row_r"><?php echo $data['sales_rep']; ?></div>
            
            </div>
        
        </div>
    
    </div>
    
    
    <div id="invoice_item_line_head">
    
    	<div class="invoice_item_head_ln">Ln</div>
        <div class="invoice_item_head_item">Item</div>
        <div class="invoice_item_head_price">Price</div>
        <div class="invoice_item_head_qty">Qty</div>
        <div class="invoice_item_head_amount">Amount</div>    
    
    </div>
    
    <?php
	$i=0;
	$subTotal = 0;
	$totalDiscount = 0;
	foreach($data['invoice_items'] as $ii)
	{
		
		$i+=1;
		$discount = ($ii['master_price']-$ii['unit_price'])*$ii['qty'];
		
		$totalDiscount += $discount;
		$subTotal +=$ii['total'];
		
		
		?>
    <div class="invoice_item_line_row">
    
    	<div class="invoice_item_det_ln"><?php echo $i; ?></div>
        <div class="invoice_item_det_item"><?php echo $defCls->showText($InventoryMasterItemsQuery->data($ii['item_id'],'name')); ?></div>
    
    </div>
    
    <div class="invoice_item_line_row">
    
    	<div class="invoice_item_det_unique"><?php if($ii['unique_no']){ echo $ii['unique_no']; } ?></div>
        <div class="invoice_item_det_price"><?php echo $defCls->money($ii['master_price']); ?></div>
        <div class="invoice_item_det_qty"><?php echo $defCls->num($ii['qty']); ?></div>
        <div class="invoice_item_det_amount"><?php echo $defCls->money($ii['total']+$discount); ?></div>    
    
    </div>
    
     <?php
                        
	if($discount>0)
	{
		?>
    <div class="invoice_item_line_row">
    
        <div class="invoice_item_det_discount">DISCOUNT</div>
        <div class="invoice_item_det_amount">-<?php echo $defCls->money($discount); ?></div>    
    
    </div>
    <?php }} ?>
    
      
            
            <tr>
            
            	<td>
                	<?php $lineCount=4; ?>
                	<table class="table">
                    
                    	<tr>
                        
                        	<td valign="top">
                            
                            	<table class="table">
                                    
                                    <tr>
            
                                        <td colspan="4" id="loyalty_head">LOYALTY POINTS</td>
                                        
                                    </tr>
                                    
                                    
                                    
                                    <tr>
                                    
                                        <td colspan="3" class="payment_mode_l">POINTS</td>
                                        <td class="payment_mode_r"><?php echo $defCls->money($data['before_inv_points']); ?></td>
                                        
                                    </tr>
                                    
                                    <tr>
                                    
                                        <td colspan="3" class="payment_mode_l">EARNED POINTS</td>
                                        <td class="payment_mode_r"><?php echo $defCls->money($data['earned_points']); ?></td>
                                        
                                    </tr> 
                                    
                                    <tr>
                                    
                                        <td colspan="3" class="payment_mode_l">BALANCE POINTS</td>
                                        <td class="payment_mode_r"><?php echo $defCls->money($data['balance_points']); ?></td>
                                        
                                    </tr>
                                                        
                                </table>
                            
                            
                            </td>
                        
                        	<td valign="top">
                            
                            	
                                <table class="table">
                                
                                	<tr>
                        
                                        <td colspan="1">SUB TOTAL</td>
                                        <td id="subtotal_r" class="text-right"><?php echo $defCls->money($subTotal); ?></td>
                                    
                                    </tr>
                                    
                                    <?php if($data['discount_amount']>0){ ?>
                                    <tr>
                                    
                                        <td colspan="1">DISCOUNT</td>
                                        <td id="discount_r" class="text-right">-<?php echo $defCls->money($data['discount_amount']); ?></td>
                                    
                                    </tr>
                                    <?php } ?>
                                
                                    <tr>
                                    
                                        <td colspan="1">NET TOTAL</td>
                                        <td id="net_total_r" class="text-right"><?php echo $defCls->money($data['total_sale']); ?></td>
                                    
                                    </tr>
                                    
                                    <?php if($totalDiscount>0){ ?>
                                    <tr>
                                    
                                        <td colspan="2" id="total_saving" class="text-center">
                                        
                                            <strong>YOUR SAVING: <?php echo $defCls->money($totalDiscount); ?></strong>
                                        
                                        </td>
                                        
                                    </tr>
                                    <?php } ?>
                                
                                
                                    
                                
                                </table>
                            
                            
                            </td>
                        
                        </tr>
                        
                        <tr>
                        
                        	<td valign="top">
                            
                            	<table class="table">
                                
                                
                                	<?php
                                    $balance = 0;
                                    foreach($data['invoice_payments'] as $ip)
                                    {
                                        $balance+=$ip['amount_balance'];
                                        ?>
                                        <?php $lineCount=1; ?>
                                    <tr>
                                    
                                        <td colspan="4" class="payment_mode_l"><?php echo $ip['type']; ?></td>
                                        <td class="payment_mode_r text-right"><?php echo $defCls->money($ip['amount']+$ip['amount_balance']); ?></td>
                                    
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    
                                        <td colspan="4" id="cash_bl_l">Cash Balance</td>
                                        <td id="cash_bl_r" class="text-right"><?php echo $defCls->money($balance); ?></td>
                                    
                                    </tr>
                                
                                
                                </table>
                            
                            </td>
                            
                            <td valign="top">
                            
                            	<table class="table">
                                
                                	
                                
                                	 <tr id="qty_pcs">
            
                                        <td>NO OF ITEMS</td>
                                        <td class="text-right"> <?php echo $defCls->num($data['no_of_items']); ?></td>
                                     </tr>
                                     <tr>   
                                        <td>NO OF QTY</td>
                                        <td class="text-right">  <?php echo $defCls->num($data['no_of_qty']); ?></td>
                                        
                                    </tr> 
                                
                                </table>
                            
                            </td>
                        
                        </tr>
                        
                        
                        
            <tr>
            	
            	<td colspan="2" style="height:30px; text-align:left; padding:10px;" valign="top">
                
                	NOTE: <?php echo $data['comments']; ?>
                    
                </td>
            </tr>
                        
                     
            <tr>
            
            
            	<td colspan="2" class="text-center">
                	<?php echo $data['invoice_footer']; ?>
                        
                        <br>
                       POWERED BY  WEBIVOX.LK - 0777904054
                </td>
           	</tr>
                    	
                    
                    </table>                
                
                </td>
            
            
            </tr>
            
    	
        
        </tbody>
        
    </table>



</div>
</div>

</body>
</html>
