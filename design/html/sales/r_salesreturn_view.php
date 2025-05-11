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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
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
            
            	<h1>Sales Return Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
        
        </thead>
        
        <tbody>
        
        	<?php
			foreach($data['rows'] as $r)
			{
				
				if($r['return_row']){
				
					if($r['currentInvoiceId'])
					{
					?>
					<tr class="grey">
		
						<td><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="2"></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalQty']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalPrice']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalCost']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalProfit']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalProfitPercentage']; ?></td>
                      
                        
					
					</tr>
					<?php } ?>
                <tr class="dgrey">
        
                    <td>Date</td>
                    <td>Invoice No</td>
                    <td>Customer Name</td>
                    <td>Location</td>
                    <td>User</td>
                    <td colspan="3">Remarks</td>
                
                </tr>
                        
                <tr>
                
                	<td><?php echo $r['added_date']; ?></td>
                	<td><?php echo $r['invoice_no']; ?></td>
                	<td><?php echo $r['customer']; ?></td>
                	<td><?php echo $r['location']; ?></td>
                	<td><?php echo $r['user']; ?></td>
                	<td colspan="3"><?php echo $r['remarks']; ?></td>
                
                </tr>
                
                
    
                <tr class="grey">
                
                    <td>Item No</td>
                    <td colspan="2">Item Name</td>
                    <td>Qty</td>
                    <td>Price</td>
                    <td>Cost</td>
                    <td>Profit</td>
                    <td>Profit %</td>
                
                
                </tr>
                
                
                <?php
				}
				else{
				?>
					<tr>
					
						<td><?php echo $r['item_id']; ?></td>
						<td colspan="2"><?php echo $r['item_name']; ?></td>
						<td class="text-right"><?php echo $r['qty']; ?></td>
						<td class="text-right"><?php echo $r['price']; ?></td>
						<td class="text-right"><?php echo $r['cost']; ?></td>
						<td class="text-right"><?php echo $r['profit']; ?></td>
						<td class="text-right"><?php echo $r['profit_percentage']; ?></td>
					   
					</tr>
          <?php } if ($r === end($data['rows'])) { ?>
			
			<tr class="grey">
		
						<td><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="2"></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalQty']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalPrice']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalCost']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalProfit']; ?></td>
                        <td colspan="1" class="text-right"><?php echo $r['totalProfitPercentage']; ?></td>
                      
                        
					
					</tr>
			
		<?php	}} ?>
    
    
    	</tbody>
        
    </table>

</div>

</body>
</html>