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
        
            <td colspan="12">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="12" id="sales_report_head">
            
            	<h1>Inventory Transfer Note Listing Report</h1>
                <h3>Filter: <?php echo $data['filter_heading']; ?></h3>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
    
        
        </thead>
        
        <tbody>
        
        	<?php
			foreach($data['rows'] as $r)
			{
				
				if($r['rnh_row']){
				
					if($r['currentRNId'])
					{
					?>
                    
                     <tr class="grey">
        
                        <td colspan="1"><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="3"></td>
                        <td class="text-right"><?php echo $r['totalQty']; ?></td>
                   
                    </tr>
					
					<?php } ?>
                <tr class="dgrey">
        
                    <td colspan="1">No</td>
                    <td colspan="1">Date</td>
                    <td colspan="1">Location From</td>
                    <td colspan="1">Location To</td>
                    <td colspan="1">User</td>
                
                </tr>
                        
                <tr>
                
                	<td colspan="1"><?php echo $r['rn_no']; ?></td>
                	<td><?php echo $r['added_date']; ?></td>
                	<td colspan="1"><?php echo $r['location_from']; ?></td>
                	<td colspan="1"><?php echo $r['location_to']; ?></td>
                	<td colspan="1"><?php echo $r['user']; ?></td>
                
                </tr>
                
                
    
                <tr class="grey">
                
                    <td>Item No</td>
                    <td colspan="3">Item Name</td>
                    <td class="text-right">Qty</td>
                
                
                </tr>
                
                
                <?php
				}
				else{
				?>
					<tr>
					
						<td><?php echo $r['item_id']; ?></td>
						<td colspan="3"><?php echo $r['item_name']; ?></td>
						<td class="text-right"><?php echo $r['qty']; ?></td>
					   
					</tr>
           <?php } if ($r === end($data['rows'])) { ?>
			
					<tr class="grey">
        
                        <td colspan="1"><?php echo $r['totalNoOfItem']; ?></td>
                        <td colspan="3"></td>
                        <td class="text-right"><?php echo $r['totalQty']; ?></td>
                   
                    </tr>
			
			
			<?php	}} ?>
    
    
    	</tbody>
        
    </table>

</div>

</body>
</html>