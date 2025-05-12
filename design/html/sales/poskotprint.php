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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link id="page_favicon" href="<?php echo _IMAGES; ?>fav.png" rel="icon" type="image/x-icon" />
<base href="<?php echo _SERVER; ?>">
<script>
    window.onload = function () {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    };
</script>

</head>

<body>

<div id="maindiv">
<div id="maindivin">
    <table class="table" style="width:100%; max-width:350px;">
    
    	<thead>
        
            <tr>
            
                <td colspan="2" id="invoice_head">
                
                	
            		<h1 style="font-size:24px">KOT PRINT<br><?php echo $data['invoice_no']; ?></h1>
                    <p><?php echo $data['added_date']; ?></p>
                
                </td>
            
            </tr>
            
        
        </thead>
        
        <tbody>  
    
        	<tr>
        
                <td colspan="2" id="invoice_item_head">
                
                    <table class="table">
                    
                        <thead>
                        
                            <tr>
                            
                                <td class="invoice_item_head_ln">Ln</td>
                                <td class="invoice_item_head_item" style="width:inherit">Item</td>
                            
                            </tr>                
                        
                        </thead>
                        
                        <tbody style="font-size:18px;">
                        
                        	<?php
							$i=0;
							foreach($data['invoice_items'] as $ii)
							{
								
								$i+=1;
								?>
                            <tr>
                            
                            	
                                <td class="invoice_item_ln"><?php echo $i; ?></td>
                                <td colspan="1"><?php echo $defCls->showText($InventoryMasterItemsQuery->data($ii['sii_item_id'],'name')); ?></td>
                            	
                            
                            </tr>
                            <tr>
                            
                                <td colspan="2" align="right">Qty: <?php echo $defCls->num($ii['sii_qty']); ?></td>
                            
                            </tr>
                            <?php } ?>
                            
                        
                        </tfoot>
                    
                    </table>
                
                </td>
        
        	</tr>
            
    
    	</tbody>
    
    </table>

</div>
</div>

</body>
</html>