<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign In | <?php echo $data['companyName']; ?></title>
<link href="<?php echo _CSS; ?>style.css" rel="stylesheet" type="text/css">
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

<header id="header">

	<div class="container">
    
    	<div id="logo">
        
        	<img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>">
        
        </div>
        
        <div id="header_left">
        
        	
        
        </div>
        
		<?php
		date_default_timezone_set('Asia/Colombo'); // Set timezone
		$serverTime = date('d-m-Y H:i:s'); // Format: dd-mm-yyyy hh:mm:ss
		?>
        <script>
			// Get PHP time and parse it manually
			let serverTimeStr = "<?php echo $serverTime; ?>"; // Get PHP time string
			let parts = serverTimeStr.split(/[- :]/); // Split by dash, space, and colon
			let serverTime = new Date(parts[2], parts[1] - 1, parts[0], parts[3], parts[4], parts[5]); // Convert to JS date
	
			function updateTime() {
				serverTime.setSeconds(serverTime.getSeconds() + 1); // Increase time by 1 second
	
				let day = String(serverTime.getDate()).padStart(2, '0');
				let month = String(serverTime.getMonth() + 1).padStart(2, '0'); // Months are 0-based
				let year = serverTime.getFullYear();
				let hours = String(serverTime.getHours()).padStart(2, '0');
				let minutes = String(serverTime.getMinutes()).padStart(2, '0');
				let seconds = String(serverTime.getSeconds()).padStart(2, '0');
	
				let formattedTime = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
				document.getElementById("header_left").innerText = formattedTime;
			}
	
			setInterval(updateTime, 1000);
			updateTime(); // Call immediately
        </script>
        
        <div id="header_right">
        
        	<a id="header_user" href="">Hello <?php echo $defCls->showText($SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name')); ?></a>
        	<a id="header_logout" href="<?php echo $defCls->genURL('secure/signout'); ?>">Log Out</a>
        
        </div>
    
    </div>

</header>

