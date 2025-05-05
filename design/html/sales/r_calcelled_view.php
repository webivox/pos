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
        
            <td colspan="18">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="18" id="sales_report_head">
            
            	<h1>Cancelled Sales Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Date</td>
        	<td>Invoice No</td>
        	<td>Customer Name</td>
        	<td class="text-right">Total Sales</td>
        	<td class="text-right">Cost</td>
        	<td class="text-right">Profit</td>
        	<td class="text-right">Profit %</td>
        	<td>Sales Rep</td>
        	<td>Location</td>
        	<td>User</td>
        	<td class="text-right">Cash</td>
        	<td class="text-right">Card</td>
        	<td class="text-right">Return</td>
        	<td class="text-right">Gift Card</td>
        	<td class="text-right">Loyalty</td>
        	<td class="text-right">Credit</td>
        	<td class="text-right">Cheque</td>
        
        
        
        </tr>
        
        </thead>
        
        <tbody>
        
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['added_date']; ?></td>
                <td><?php echo $r['invoice_no']; ?></td>
                <td><?php echo $r['customer']; ?></td>
                <td class="text-right"><?php echo $r['total_sale']; ?></td>
                <td class="text-right"><?php echo $r['cost']; ?></td>
                <td class="text-right"><?php echo $r['profit']; ?></td>
                <td class="text-right"><?php echo $r['profit_percentage']; ?></td>
                <td><?php echo $r['sales_Rep']; ?></td>
                <td><?php echo $r['location']; ?></td>
                <td><?php echo $r['user']; ?></td>
                <td class="text-right"><?php echo $r['cash']; ?></td>
                <td class="text-right"><?php echo $r['card']; ?></td>
                <td class="text-right"><?php echo $r['return']; ?></td>
                <td class="text-right"><?php echo $r['gift_card']; ?></td>
                <td class="text-right"><?php echo $r['loyalty']; ?></td>
                <td class="text-right"><?php echo $r['credit']; ?></td>
                <td class="text-right"><?php echo $r['cheque']; ?></td>
            
            
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
            <tr>
            
                <td colspan="3">Total</td>
                <td class="text-right"><?php echo $data['totalTotalSale']; ?></td>
                <td class="text-right"><?php echo $data['totalCost']; ?></td>
                <td class="text-right"><?php echo $data['totalProfit']; ?></td>
                <td class="text-right"><?php echo $data['totalprofitPercentage']; ?></td>
                <td colspan="3"></td>
                <td class="text-right"><?php echo $data['totalCash']; ?></td>
                <td class="text-right"><?php echo $data['totalCard']; ?></td>
                <td class="text-right"><?php echo $data['totalReturn']; ?></td>
                <td class="text-right"><?php echo $data['totalGiftCard']; ?></td>
                <td class="text-right"><?php echo $data['totalLoyalty']; ?></td>
                <td class="text-right"><?php echo $data['totalCredit']; ?></td>
                <td class="text-right"><?php echo $data['totalCheque']; ?></td>
            
            
            
            </tr>
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>