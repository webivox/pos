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
<script>
window.onload = function () {
    window.print();
}
</script>
</head>

<body>

<div id="report_cont">

    <table class="table">
    
    	<thead>
    
        <tr>
        
            <td colspan="8">
            
            	<div id="logo"><img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>"></div>
            
            </td>
        
        </tr>
    
        <tr>
        
            <td colspan="8" id="sales_report_head">
            
            	<h1>Supplier Debit Note</h1>
                <h3><?php echo $data['print_by_n_date']; ?></h3>
            
            </td>
        
        </tr>
        
        </thead>
        
        <tbody>
    
    
            <tr>
            
                <td>Date</td>
                <td><?php echo $data['added_date']; ?></td>
                
                <td>Debit Note No</td>
                <td><?php echo $data['debit_note_no']; ?></td>
                
                <td>Location</td>
                <td><?php echo $data['location_id']; ?></td>
                
             </tr>
             <tr>
                
                <td>Supplier Name</td>
                <td colspan="3"><?php echo $data['supplier_id']; ?></td>
                
                <td>Amount</td>
                <td><?php echo $data['amount']; ?></td>
             
                
             </tr>
             <tr>
                
                <td>Details</td>
                <td colspan="3"><?php echo $data['details']; ?></td>
                
                <td>User</td>
                <td><?php echo $data['user']; ?></td>
            
            </tr>
       
        
             <tr>
                
                <td colspan="3" class="text-center"><br><Br><br>--------------------------<br>Authorized By</td>
                
                <td colspan="3" class="text-center"><br><Br><br>--------------------------<br>Received By</td>
            
            </tr>
    
    
    	</tbody>
    
    </table>

</div>

</body>
</html>