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
<base href="<?php echo _SERVER; ?>">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link id="page_favicon" href="<?php echo _IMAGES; ?>fav.png" rel="icon" type="image/x-icon" />
<meta http-equiv="refresh" content="15">
</head>

<body>

<div id="report_cont" style="max-width:800px;">

    <table class="table">
    
    	<thead>
    
        <tr>
        
            <td colspan="18">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="18" id="sales_report_head">
            
            	<h1>KOT</h1>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>Date</td>
        	<td>Invoice No</td>
        	<td>Item</td>
        	<td class="text-right">Qty</td>
        	<td>Status</td>
        	<td class="text-center">Action</td>
        
        
        
        </tr>
        
        </thead>
        
        <tbody>
        
        	<?php
			$invoice_no = '';
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                <td><?php echo $r['added_date']; ?></td>
                <td><?php echo $r['invoice_no']; ?></td>
                <td><?php echo $r['item']; ?></td>
                <td class="text-right"><?php echo $r['qty']; ?></td>
                <td><?php echo $r['status']; ?></td>
            	
                <td class="text-center">
                
                	<?php if($invoice_no!==$r['invoice_no']){ ?>
                	<a href="<?php echo $r['printUrl']; ?>" target="_blank" class="btn btn-primary <?php if($r['status']!=='Printed'){ echo 'printThis'; } ?>">Re Print</a>
                    <?php } $invoice_no=$r['invoice_no']; ?>
                
                </td>
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
    
    </table>

</div>

<script>
$(document).ready(function () {
    var $elements = $('.printThis');
    var index = 0;

    function openNext() {
        if (index < $elements.length) {
            var url = $elements.eq(index).attr('href');
            window.open(url, '_blank');
            index++;
            setTimeout(openNext, 1000); // delay between prints
        } else {
            // Optional: redirect after all tabs are opened
            // window.location.href = 'your-return-url-here';
        }
    }

    openNext();
});


</script>
</body>
</html>