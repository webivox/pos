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
        
            <td colspan="10">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="10" id="sales_report_head">
            
            	<h1>Cheque Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Added Date</td>
        	<td>Cheque Date</td>
        	<td>Realized Date</td>
        	<td>Cheque No</td>
        	<td>Remarks</td>
        	<td>Type</td>
        	<td>Bank Code</td>
        	<td class="text-right">Amount</td>
        	<td>Account</td>
        	<td>Status</td>
        
        </tr>
        
        
        </thead>
        
        <tbody>
       
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['addedDate']; ?></td>
                <td><?php echo $r['chequeDate']; ?></td>
                <td><?php echo $r['realizedDate']; ?></td>
                <td><?php echo $r['chequeNo']; ?></td>
                <td><?php echo $r['remarks']; ?></td>
                <td><?php echo $r['type']; ?></td>
                <td><?php echo $r['bankCode']; ?></td>
                <td class="text-right"><?php echo $r['amount']; ?></td>
                <td><?php echo $r['depositedAccount']; ?></td>
                <td><?php echo $r['status']; ?></td>
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
            <tr>
            
                <td colspan="7">Total</td>
                <td class="text-right"><?php echo $data['tAmount']; ?></td>
                <td colspan="2"></td>
            
            </tr>
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>