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

<div id="report_cont" style="max-width:450px;">

    <table class="table">
    
    	<thead>
    
        <tr>
        
            <td colspan="18">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="18" id="sales_report_head">
            
            	<h1>Cashier Report</h1>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
			
			
    
    	<tr>
        
        	<td>Description</td>
        	<td class="text-right">Amount </td>
        
        </tr>
        
        </thead>
        
        <tbody>
        
        	<tr>
            
            	<td>Total Sales</td>
               <td class="text-right"><?php echo $data['totalSales']; ?></td>
            
            </tr>
        
        	<tr>
            
                <td>Item Discount</td>
               <td class="text-right"><?php echo $data['itemDiscount']; ?></td>
            
            </tr>
        
        	<tr>
            
                <td>Sales Discount</td>
               <td class="text-right"><?php echo $data['totalSalesDiscount']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Total Discounts</td>
               <td class="text-right"><?php echo $data['totalDiscount']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Total Gross Sales</td>
               <td class="text-right"><?php echo $data['totalGrossSales']; ?></td>
            
            </tr>
        
        	<tr class="grey">
            
            	<td colspan="2" class="text-center">Payment Summary</td>
            
            </tr>
            <tr class="grey">
            
                <td>Description</td>
                <td class="text-right">Amount </td>
            
            </tr>
        
        	<tr>
            
            	<td>Cash Balance</td>
               <td class="text-right"><?php echo $data['cashBalance']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Cash Out</td>
               <td class="text-right"><?php echo $data['totalCashOut']; ?></td>
            
            </tr>
            
        	<tr>
            
            	<td>Cash Payments</td>
               <td class="text-right"><?php echo $data['totalCashSales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Card Payments</td>
               <td class="text-right"><?php echo $data['totalCardSales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Return Used</td>
               <td class="text-right"><?php echo $data['totalReturnSales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Gift Card Used</td>
               <td class="text-right"><?php echo $data['totalGiftCardSales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Loyalty Points Redeemed</td>
               <td class="text-right"><?php echo $data['totalLoyaltySales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Credit</td>
               <td class="text-right"><?php echo $data['totalCreditSales']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Cheques</td>
               <td class="text-right"><?php echo $data['totalChequeSales']; ?></td>
            
            </tr>
        
        	<tr class="grey">
            
            	<td colspan="2" class="text-center">Other Info</td>
            
            </tr>
            <tr class="grey">
            
                <td>Description</td>
                <td class="text-right">Value </td>
            
            </tr>
        
        	<tr>
            
            	<td>Invoices Issued</td>
               <td class="text-right"><?php echo $data['totalInvoices']; ?></td>
            
            </tr>
        
        	<tr>
            
            	<td>Items Sold</td>
               <td class="text-right"><?php echo $data['totalQty']; ?></td>
            
            </tr>
    </table>

</div>

</body>
</html>