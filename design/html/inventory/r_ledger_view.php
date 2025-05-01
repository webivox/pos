<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign In | <?php echo $data['companyName']; ?></title>
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
        
            <td colspan="6">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="6" id="sales_report_head">
            
            	<h1>Item Ledger Listing Report</h1>
                <h3>Filter: sdsd</h3>
                <h3>Print By: Shihan | Printed On: 01-01-2025</h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Date</td>
        	<td>Details</td>
        	<td>Amount</td>
        	<td class="text-right">In</td>
        	<td class="text-right">Out</td>
        	<td class="text-right">Balance</td>
        
        </tr>
        
        </thead>
        
        <tbody>
       
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['added_date']; ?></td>
                <td><?php echo $r['remarks']; ?></td>
                <td><?php echo $r['amount']; ?></td>
                <td class="text-right"><?php echo $r['debit']; ?></td>
                <td class="text-right"><?php echo $r['credit']; ?></td>
                <td class="text-right"><?php echo $r['balance']; ?></td>
            
            
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
            <tr>
            
                <td colspan="3">Total</td>
                <td class="text-right"><?php echo $data['tDebit']; ?></td>
                <td class="text-right"><?php echo $data['tCredit']; ?></td>
                <td class="text-right"><?php echo $data['tBalance']; ?></td>
            
            </tr>
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>