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

<div id="report_cont" style="max-width:500px;">

    <table class="table">
    
    	<thead>
    
            <tr>
            
                <td colspan="6">
                
                    <div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
                
                </td>
            
            </tr>
        
            <tr>
            
                <td colspan="6" id="sales_report_head">
                
                    <h1>Profit &amp; Loss Statement</h1>
                    <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                    <h3><?php echo $data['print_by_n_date']; ?></h3>
                
                </td>
            
            </tr>
      	</thead>
        
        <tbody>
        
            <tr class="dgrey">
            
                <td colspan="2">Income</td>
            
            </tr>
            
            <tr>
            
                <td>Sales Revenue</td>
                <td class="text-right"><?php echo $data['totalSales']; ?></td>
            
            </tr>
            
            <tr>
            
                <td>Other Income</td>
                <td class="text-right"><?php echo $data['otherIncome']; ?></td>
            
            </tr>
            
            <tr class="grey">
            
                <td>Total Income</td>
                <td class="text-right"><?php echo $data['totalIncome']; ?></td>
            
            </tr>
            
            <tr class="dgrey">
            
                <td colspan="2">Cost of Goods Sold (COGS)</td>
            
            </tr>
            
            <tr>
            
                <td>Cost of Goods Sold</td>
                <td class="text-right"><?php echo $data['totalSaleCost']; ?></td>
            
            </tr>
            
            <tr>
            
                <td>Inventory Adjustments +</td>
                <td class="text-right"><?php echo $data['stockAdjustPlus']; ?></td>
            
            </tr>
            
            <tr>
            
                <td>Inventory Adjustments -</td>
                <td class="text-right">(<?php echo $data['stockAdjustMinus']; ?>)</td>
            
            </tr>
            
            <tr class="grey">
            
                <td>Total COGS</td>
                <td class="text-right">(<?php echo $data['totalInventory']; ?>)</td>
            
            </tr>
            
            <tr class="dgrey">
            
                <td colspan="2">Gross Profit</td>
            
            </tr>
            
            <tr>
            
                <td>Total Income</td>
                <td class="text-right">(<?php echo $data['totalIncome']; ?>)</td>
            
            </tr>
            
            <tr>
            
                <td>(-) Total COGS</td>
                <td class="text-right">(<?php echo $data['totalInventory']; ?>)</td>
            
            </tr>
            
            <tr class="grey">
            
                <td>Gross Profit</td>
                <td class="text-right"><?php echo $data['grossProfit']; ?></td>
            
            </tr>
            
            <tr class="dgrey">
            
                <td colspan="2">Operating Expenses</td>
            
            </tr>
            
            <?php foreach($data['expencestypes'] as $et){ ?>
            <tr>
            
                <td><?php echo $et['name']; ?></td>
                <td class="text-right"><?php echo $et['amount']; ?></td>
            
            </tr>
            <?php } ?>
            
            <tr>
            
                <td>Total Operating Expenses</td>
                <td class="text-right"><?php echo $data['totalExpenses']; ?></td>
            
            </tr>
            
            <tr class="grey">
            
                <td>Net Profit</td>
                <td class="text-right"><?php echo $data['netProfit']; ?></td>
            
            </tr>
        
		</tbody>
            
    </table>

</div>

</body>
</html>