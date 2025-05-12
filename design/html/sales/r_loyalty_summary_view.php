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

<div id="report_cont" style="max-width:500px">

    <table class="table">
    
    	<thead>
    
        <tr>
        
            <td colspan="6">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="6" id="sales_report_head">
            
            	<h1>Customer Loyalty Summary Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Total Debit</td>
        	<td>Total Credit</td>
        	<td>Balance</td>
        
        </tr>
        
        </thead>
        
        <tbody>
       
            <tr>
            
                <td class="text-right"><?php echo $data['tDebit']; ?></td>
                <td class="text-right"><?php echo $data['tCredit']; ?></td>
                <td class="text-right"><?php echo $data['tBalance']; ?></td>
            
            
            
            </tr>
    
    
    	</tbody>
        
    
    </table>

</div>

</body>
</html>