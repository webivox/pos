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
            
            	<h1>Customer Outstanding Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>No</td>
        	<td>Group</td>
        	<td>Name</td>
        	<td>Phone Number</td>
        	<td>Email</td>
        	<td>Address</td>
        	<td>Credit Limit</td>
        	<td>Settlement Days</td>
        	<td>Card No</td>
        	<td>Loyalty Points</td>
        	<td class="text-right">Outstanding</td>
        	<td>Status</td>
        
        </tr>
        
        </thead>
        
        <tbody>
       
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['no']; ?></td>
                <td><?php echo $r['group']; ?></td>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo $r['phone']; ?></td>
                <td><?php echo $r['email']; ?></td>
                <td><?php echo $r['address']; ?></td>
                <td><?php echo $r['credit_limit']; ?></td>
                <td><?php echo $r['settlement_days']; ?></td>
                <td><?php echo $r['card_no']; ?></td>
                <td><?php echo $r['loyalty_points']; ?></td>
                <td class="text-right"><?php echo $r['closing_balance']; ?></td>
                <td><?php echo $r['status']; ?></td>
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
            <tr>
            
                <td colspan="10">Total</td>
                <td class="text-right"><?php echo $data['totalOutstanding']; ?></td>
                <td colspan="1"></td>
            
            </tr>
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>