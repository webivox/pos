<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $data['title_tag']; ?></title>
<link href="<?php echo _CSS; ?>report.css" rel="stylesheet" type="text/css">
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
</head>

<body>

<div id="report_cont">

    <table class="table">
    
    	<thead>
    
        <tr>
        
            <td colspan="12">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="12" id="sales_report_head">
            
            	<h1>Inventory Receiving Note Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
        
        </thead>
        
        <tbody>
        
        	<?php
			foreach($data['rows'] as $r)
			{
				
				if($r['rnh_row']){
				
					if($r['currentRNId'])
					{
					?>
                    
                     <tr class="grey">
        
                        <td colspan="1"><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="1"></td>
                        <td class="text-right"><?php echo $r['totalQty']; ?></td>
                        <td class="text-right"><?php echo $r['totalPrice']; ?></td>
                        <td class="text-right"><?php echo $r['totalBuyingPrice']; ?></td>
                        <td colspan="1"></td>
                        <td class="text-right"><?php echo $r['totalFinalPrice']; ?></td>
                        <td class="text-right"><?php echo $r['totalTotal']; ?></td>
                   
                    </tr>
					
					<?php } ?>
                <tr class="dgrey">
        
                    <td colspan="1">No</td>
                    <td colspan="1">Date</td>
                    <td>Invoice No</td>
                    <td colspan="2">Supplier Name</td>
                    <td colspan="1">Location</td>
                    <td colspan="1">User</td>
                    <td colspan="1">Due Date</td>
                
                </tr>
                        
                <tr>
                
                	<td colspan="1"><?php echo $r['rn_no']; ?></td>
                	<td><?php echo $r['added_date']; ?></td>
                	<td colspan="1"><?php echo $r['invoice_no']; ?></td>
                	<td colspan="2"><?php echo $r['supplier']; ?></td>
                	<td colspan="1"><?php echo $r['location']; ?></td>
                	<td colspan="1"><?php echo $r['user']; ?></td>
                	<td colspan="1"><?php echo $r['due_date']; ?></td>
                
                </tr>
                
                
    
                <tr class="grey">
                
                    <td>Item No</td>
                    <td>Item Name</td>
                    <td>Qty</td>
                    <td>Price</td>
                    <td>Buying Price</td>
                    <td>Discount</td>
                    <td>Final Price</td>
                    <td>Total</td>
                
                
                </tr>
                
                
                <?php
				}
				else{
				?>
					<tr>
					
						<td><?php echo $r['item_id']; ?></td>
						<td><?php echo $r['item_name']; ?></td>
						<td class="text-right"><?php echo $r['qty']; ?></td>
						<td class="text-right"><?php echo $r['price']; ?></td>
						<td class="text-right"><?php echo $r['buying_price']; ?></td>
						<td class="text-right"><?php echo $r['discount']; ?></td>
						<td class="text-right"><?php echo $r['final_price']; ?></td>
						<td class="text-right"><?php echo $r['total']; ?></td>
					   
					</tr>
            <?php } if ($r === end($data['rows'])) { ?>
			
			
                     <tr class="grey">
        
                        <td colspan="1"><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="1"></td>
                        <td class="text-right"><?php echo $r['totalQty']; ?></td>
                        <td class="text-right"><?php echo $r['totalPrice']; ?></td>
                        <td class="text-right"><?php echo $r['totalBuyingPrice']; ?></td>
                        <td colspan="1"></td>
                        <td class="text-right"><?php echo $r['totalFinalPrice']; ?></td>
                        <td class="text-right"><?php echo $r['totalTotal']; ?></td>
                   
                    </tr>
			
			
		<?php	}} ?>
    
    
    	</tbody>
        
    </table>

</div>

</body>
</html>