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
        
            <td colspan="19">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="19" id="sales_report_head">
            
            	<h1>Stock Listing Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
    
    	<tr>
        
        	<td>No</td>
        	<td>Name</td>
        	<td>Barcode</td>
        	<td>Barcode Name</td>
        	<td>Category</td>
        	<td>Brand</td>
        	<td>Unit</td>
        	<td class="text-right">Supplier</td>
        	<td class="text-right">Selling Price</td>
        	<td class="text-right">Min Sell Price</td>
        	<td class="text-right">Cost</td>
        	<td class="text-right">Re Order Qty</td>
        	<td class="text-right">Order Qty</td>
        	<td class="text-right">Min Qty</td>
        	<td>Status</td>
        	<td class="text-right">In</td>
        	<td class="text-right">Out</td>
        	<td class="text-right">Available</td>
        	<td class="text-right">Value</td>
            
           
        
        
        </tr>
        
        </thead>
        
        <tbody>
       
        	<?php
			foreach($data['rows'] as $r)
			{
				?>
            <tr>
            
                 <td><?php echo $r['no']; ?></td>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo $r['barcode']; ?></td>
                <td><?php echo $r['barcode_name']; ?></td>
                <td><?php echo $r['category_id']; ?></td>
                <td><?php echo $r['brand_id']; ?></td>
                <td><?php echo $r['unit_id']; ?></td>
                <td><?php echo $r['supplier_id']; ?></td>
                <td class="text-right"><?php echo $r['selling_price']; ?></td>
                <td class="text-right"><?php echo $r['minimum_selling_price']; ?></td>
                <td class="text-right"><?php echo $r['cost']; ?></td>
                <td class="text-right"><?php echo $r['re_order_qty']; ?></td>
                <td class="text-right"><?php echo $r['order_qty']; ?></td>
                <td><?php echo $r['minimum_qty']; ?></td>
                <td><?php echo $r['status']; ?></td>
                <td class="text-right"><?php echo $r['in']; ?></td>
                <td class="text-right"><?php echo $r['out']; ?></td>
                <td class="text-right"><?php echo $r['available']; ?></td>
                <td class="text-right"><?php echo $r['value']; ?></td>
            
            </tr>
            <?php } ?>
    
    
    	</tbody>
        
        <tfoot>
        
          
          	<td colspan="15">Total</td>
            <td class="text-right"><?php echo $data['totalIn']; ?></td>
            <td class="text-right"><?php echo $data['totalOut']; ?></td>
            <td class="text-right"><?php echo $data['totalAvailable']; ?></td>
            <td class="text-right"><?php echo $data['totalValue']; ?></td>
          
        
        
        </tfoot>
    
    </table>

</div>

</body>
</html>