<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign In | <?php echo $data['companyName']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo _CSS; ?>signin.css">
<script src="<?php echo _JS; ?>jquery-3.7.1.min.js"></script>
<script src="<?php echo _JS; ?>signin.js"></script>
</head>
<body>

	<div id="login_form">
    
    	<div id="logo">
        
        	<img src="<?php echo $data['logo']; ?>" alt="<?php echo $data['companyName']; ?>">
        
        </div>
        
        <h1><?php echo $data['companyName']; ?></h1>
        
        
        <form method="post" data-url="<?php echo $data['formUrl']; ?>" id="signinForm">
        
            <input type="text" min="5" max="5" placeholder="Username" id="txtUserPos" name="txtUserPos" autofocus autocomplete="off">
            
            <input type="password" min="8" placeholder="Password" id="txtPassPos" name="txtPassPos">
            
            <button type="submit" id="signinFormBtn">Sign In</button>
    
    	</form>
    
    </div>


<div id="modal">

    <div id="modal_in">
    
        <div id="modal_in_error">
        
            <i class="fa-solid fa-brake-warning"></i>
            
            <h2>Error Found</h2>
            <p>We found the following error. Please fix it and retry.</p>
            
        </div>
        
        <ul></ul>
        
        <a id="modal_in_close" accesskey="x">Close</a>
    
    </div>

</div>


</body>
</html>
