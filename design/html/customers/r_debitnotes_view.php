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
        
            <td colspan="6">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="6" id="sales_report_head">
            
            	<h1>Debit Note Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Date</td>
        	<td>Settlement No</td>
        	<td>Customer Name</td>
        	<td>Location</td>
        	<td class="text-right">Amount</td>
        	<td>User</td>
        
        </tr>
        
        </thead>
        
        <tbody>
       
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['added_date']; ?></td>
                <td><?php echo $r['no']; ?></td>
                <td><?php echo $r['customer']; ?></td>
                <td><?php echo $r['location']; ?></td>
                <td class="text-right"><?php echo $r['amount']; ?></td>
                <td><?php echo $r['user']; ?></td>
            
            
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
            <tr>
            
                <td colspan="4">Total</td>
                <td class="text-right"><?php echo $data['amount']; ?></td>
                <td colspan="1"></td>
            
            </tr>
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>