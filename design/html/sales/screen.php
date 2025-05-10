<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sales Screen | <?php echo $data['companyName']; ?></title>

<link href="<?php echo _CSS; ?>salesscreen.css" rel="stylesheet" type="text/css">

<link href="<?php echo _CSS; ?>jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo _JS; ?>jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="<?php echo _JS; ?>keyboard.js"></script>
<link href="<?php echo _FONTS; ?>fa/css/all.css" rel="stylesheet" type="text/css">
<link href="<?php echo _FONTS; ?>fa/css/all.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo _CSS; ?>keyboard.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
<base href="<?php echo _SERVER; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link id="page_favicon" href="<?php echo _IMAGES; ?>fav.png" rel="icon" type="image/x-icon" />

</head>

<body>

<div id="keyboard_main">
    
    <div id="keyboard_main_in">
    
        <input type="hidden" id="keyboard_id" />
        
        <div id="keyboard_main_alp">
        
            <a rel="Q">Q</a>
            <a rel="W">W</a>
            <a rel="E">E</a>
            <a rel="R">R</a>
            <a rel="T">T</a>
            <a rel="Y">Y</a>
            <a rel="U">U</a>
            <a rel="I">I</a>
            <a rel="O">O</a>
            <a rel="P">P</a>
            <a rel="A">A</a>
            <a rel="S">S</a>
            <a rel="D">D</a>
            <a rel="F">F</a>
            <a rel="G">G</a>
            <a rel="H">H</a>
            <a rel="J">J</a>
            <a rel="K">K</a>
            <a rel="L">L</a>
            <a rel="~">~</a>
            <a rel="Z">Z</a>
            <a rel="X">X</a>
            <a rel="C">C</a>
            <a rel="V">V</a>
            <a rel="B">B</a>
            <a rel="N">N</a>
            <a rel="M">M</a>
            <a rel="`">`</a>
            <a rel="@">@</a>
            <a rel="&">& </a>
            <a rel=" " id="keyboardspace"></a>
            <a rel="d" id="del">DEL</a>
            <a rel="c" id="clr">CLR</a>
            <a id="keyboard_change" rel="dochange">Enter</a>
        
        </div>
        
        <div id="keyboard_main_num">
               
            <a rel="7">7</a>
            <a rel="8">8</a>
            <a rel="9">9</a>
            <a rel="4">4</a>
            <a rel="5">5</a>
            <a rel="6">6</a>
            <a rel="1">1</a>
            <a rel="2">2</a>
            <a rel="3">3</a>
            <a rel="0">0</a>
            <a rel="00">00</a>
            <a rel=".">.</a>
        
        </div>
        
        <a id="keyboard_main_enter" rel="dochange">Enter</a>
    
    </div>

</div>


<?php if($data['shift_status']){ ?>
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
        
        	<a id="header_user" href="">Hello <?php echo $data['loggedUserName']; ?></a>
        	<a id="header_logout" href="<?php echo $data['logOut']; ?>">Log Out</a>
        
        </div>
    
    </div>

</header>

<div class="container">
    <div id="left_menu">
        
        <ul>
        
            <li><a id="suspend">SUSPEND</a></li>
            <li><a class="open_popup_form" data-url="<?php echo $data['sales_return_url']; ?>" data-formsizeclass="popup_form_in_size_large">RETURN</a></li>
            <li><a class="open_popup_form" data-url="<?php echo $data['cashout_url']; ?>" data-formsizeclass="popup_form_in_size_small">CASHOUT</a></li>
            <li><a href="<?php echo $data['report_url']; ?>" target="_blank">REPORT</a></li>
            <li><a id="end_shift">SHIFT END</a></li>
            <li><a>DRAWER</a></li>
            <li><a <?php echo $data['reprint_url']; ?>>RE PRINT</a></li>
        
        </ul>
    
    </div>
    
    <div id="center_cart">
    
        <div id="center_cart_r_icso">
        
            <div id="item_search">
            
                <input type="text" id="itemSearchInput" placeholder="Barcode/Item Name" autofocus class="okeyboard">
            
            </div>
        
            <div id="customer">
            
                <label for="pendinginvoicedd">Customer:</label>
                <input type="text" id="customerSearchInput" placeholder="Customer Name" value="<?php echo $data['customer_name']; ?>" class="okeyboard">
                <a class="btn btn-primary open_popup_form" data-url="<?php echo $data['customer_create_url']; ?>" data-width="1024" data-height="440">+</a>
            
            </div>
        
            <div id="salesrep">
                
                <label for="salesrepdd">Sales Rep:</label>
                <select id="salesrepdd">
                
                    <option value="">Sales Rep:</option>
                    <?php
                    foreach($data['salesRepDD'] as $cat){
                    ?>
                    <option value="<?php echo $cat['rep_id']; ?>" <?php if($cat['rep_id']==$data['sales_rep_id']){ echo 'selected'; } ?>><?php echo $cat['name']; ?></option>
                    <?php } ?>
                
                </select>
            
            </div>
            
            <div id="pendinginvoice">
            
                <label for="pendinginvoicedd">Pending Invoice:</label>
                <select id="pendinginvoicedd">
                
                    <?php
                    
                    foreach($data['pendingINVDD'] as $cat){
                    ?>
                    <option value="<?php echo $cat['invoice_id']; ?>" <?php if($cat['invoice_id']==$data['invoiceId']){ echo 'selected'; } ?>><?php echo $defCls->docNo('PINV',$cat['invoice_id']); ?></option>
                    <?php } ?>
                
                </select>
            
            
            </div>
        
        </div>
        
        
        <div id="center_cart_r_items">
        
        
            <table class="table">
            
                <thead>
                
                    <tr>
                    
                        <td class="hcart_item_no">No</td>
                        <td class="hcart_item_itemname">Item Name</td>
                        <td class="hcart_item_price">Price</td>
                        <td class="hcart_item_amount">Amount</td>
                        <td class="hcart_item_dsc">Dsc%</td>
                        <td class="hcart_item_uprice">Unit Price</td>
                        <td class="hcart_item_qty">Qty</td>
                        <td class="hcart_item_total">Total</td>
                        <td class="hcart_item_action">Action</td>
                    
                    </tr>
                
                </thead>
            </table>
            
            <div id="center_cart_r_items_list">
            
                <table class="table">  
                    
                    <tbody id="center_cart_r_items_added">
                    
                        <?php
                        
                        foreach($data['cartItems'] as $ci){
                            
                              echo $SalesScreenQuery->itemHTML($ci); 
                        }
                        
                        ?>
                    
                    </tbody>
                
                
                </table>
            
                <table class="table" id="center_cart_r_payments_table">
                
                    <thead>
                    
                        <tr>
                        
                            <td colspan="2" class="text-center">Payments</td>
                        
                        </tr>
                    
                    </thead>
                    
                    <tbody id="center_cart_r_payments_added">
                    
                        <?php
                        foreach($data['cartPayments'] as $cp)
                        {
                            ?>
                            
                            <tr id="paymentRow<?php echo $cp['paymentId']; ?>">
                        
                                <td class="center_cart_r_payments_added_l"><input type="text" disabled value="<?php echo $cp['type']; ?>"></td>
                                <td><input type="text" class="paidamt" disabled value="<?php echo $cp['amount']; ?>"></td>
                                <td><a class="btn btn-danger pmremove" data-id="<?php echo $cp['paymentId']; ?>">X</a></td>
                            
                            </tr>
                            
                            
                        <?php } ?>
                       
                    </tbody>
                    
                    <tfoot id="center_cart_r_payments_added_foot">
                    
                    
                        <tr>
                    
                            <td class="center_cart_r_payments_added_l"><input type="text" disabled value="Balance Due"></td>
                            <td><input type="text" id="dueAmount" disabled value=""></td>
                            <td style="width:55px"></td>
                        
                        </tr>
                    
                    
                        <tr>
                    
                            <td class="center_cart_r_payments_added_l"><input type="text" disabled value="Cash Balance"></td>
                            <td><input type="text" id="balanceAmount" disabled value=""></td>
                            <td style="width:55px"></td>
                        
                        </tr>
                    
                    </tfoot>
                
                
                </table>
        
            </div>
        
        </div>
        
        <div id="center_cart_r_items_total">
        
            <div id="center_cart_r_items_total_l">
            
                <div id="center_cart_r_items_total_l_one">
                
                    
                    <table class="table">
                    
                        <tr>
                        
                            <td><label for="">Loyalty Points:</label> <input type="text" placeholder="0.00" value="<?php echo $data['loyaltyPoints']; ?>" id="loyaltyPointsTotal" disabled></td>
                            <td><label for="">Outstanding:</label> <input type="text" placeholder="0.00" disabled value="<?php echo $data['customerOutstanding']; ?>" id="totalOutstanding" disabled></td>
                            <td><label for="lineTotalDiscount">Line Discount:</label> <input type="text" placeholder="0.00" disabled id="lineTotalDiscount"></td>
                            <td id="gsdtd">
                                <label for="discount_type">Discount:</label>
                                <select id="discount_type">
                                
                                    <option value="P" <?php if($data['discount_type']=='P'){ echo 'selected'; } ?>>P%</option>
                                    <option value="F" <?php if($data['discount_type']=='F'){ echo 'selected'; } ?>>F</option>
                                
                                </select>
                                <input type="text" id="discount_value" placeholder="0.00" value="<?php echo $data['discount_value']; ?>" class="okeyboard">
                            </td>
                        
                        </tr>
                    
                    </table>
                
                
                </div>
            
                <textarea id="comments" class="okeyboard"><?php echo $data['comments']; ?></textarea>
            
            </div>
        
            <div id="center_cart_r_items_total_r">
            
                <table class="table">
                
                    <tr>
                    
                        <td>Sub Total:</td>
                        <td class="text-right" id="subTotal"><?php echo $data['total_sale']; ?></td>
                    
                    </tr>
                
                    <tr>
                    
                        <td>Discount:</td>
                        <td class="text-right">-<span id="discounAmount"><?php echo $data['discount_amount']; ?></span></td>
                    
                    </tr>
                
                    <tr>
                    
                        <td class="big">Net Total:</td>
                        <td class="big text-right" id="netTotal"><?php echo $data['net_total']; ?></td>
                    
                    </tr>
                
                </table>
            
            </div>
        
        </div>
        
        
    </div>
    
    <div id="right">
    
        <div id="right_cat">
        
            <h2>Cateogries</h2>
            
            <div id="right_cat_ul">
            
                <ul>
                
                    <?php
                    foreach($data['categoryList'] as $c)
                    {
                    ?>
                    <li><a class="open_popup_form" data-url="<?php echo $c['url']; ?>" data-formsizeclass="popup_form_in_size_large"><?php echo $c['name']; ?></a></li>
                    <?php } ?>
                
                </ul>
            
            </div>
        
        
        </div>
        
        
        <div id="right_payment">
        
            <div id="right_payment_mode">
                <div id="inner_payment_mode_m">
                    <div id="inner_payment_mode">
                        <a data-type="CASH" class="a_active payment_mode">Cash</a>
                        <a data-type="CARD" class="payment_mode">Card</a>
                        <a data-type="RETURN" class="payment_mode">Return</a>
                        <a data-type="GIFT CARD" class="payment_mode">Gift Card</a>
                        <a data-type="LOYALTY" class="payment_mode">LOYALTY</a>
                        <a data-type="CREDIT" class="payment_mode">CREDIT</a>
                        <a data-type="CHEQUE" class="payment_mode">CHEQUE</a>
                    </div>
                </div>
                <a id="getmorePaymentMode">&gt;</a>
            </div>
    
    
    
    
        
            <div id="right_payment_quick">
            
                <div id="right_payment_quick_cash" class="right_payment_quick_cls" style="display:block;">
                
                    
                </div>
                
            
                <div id="right_payment_quick_card" class="right_payment_quick_cls">
                
                    <select id="cardPaymentOption">
                    
                        <?php
                        
                        foreach($data['cardPayments'] as $cp){
                        ?>
                        <option value="<?php echo $cp['accountId']; ?>"><?php echo $cp['name']; ?></option>
                        <?php } ?>
                    </select>
                
                </div>
                
            
                <div id="right_payment_quick_return" class="right_payment_quick_cls">
                
                    <table class="table">
                    
                        <tr>
                        
                            <td><input type="text" id="right_payment_quick_return_no" placeholder="Return No." class="okeyboard"></td>
                            <td><button id="right_payment_quick_return_validate">VALIDATE</button></td>
                            <td><input type="text" disabled id="right_payment_quick_return_balance" placeholder="0.00"></td>
                        
                        </tr>
                    
                    </table>
                    
                </div>
                
            
                <div id="right_payment_quick_giftcard" class="right_payment_quick_cls">
                
                
                    <table class="table">
                    
                        <tr>
                        
                            <td><input type="text" id="right_payment_quick_giftcard_no" placeholder="Gift Card No." class="okeyboard"></td>
                            <td><button id="right_payment_quick_giftcard_no_validate">VALIDATE</button></td>
                            <td><input type="text" disabled id="right_payment_quick_giftcard_no_balance" placeholder="0.00"></td>
                        
                        </tr>
                    
                    </table>
                
                </div>
                
            
                <div id="right_payment_quick_loyalty" class="right_payment_quick_cls">
                
                    <input type="text" id="right_payment_quick_loyalty_no" placeholder="LY">
                
                </div>
                
            
                <div id="right_payment_quick_credit" class="right_payment_quick_cls">
                
                    <input type="text" id="right_payment_quick_credit_date" class="dateField" placeholder="Due Date" class="okeyboard">
                
                </div>
                
            
                <div id="right_payment_quick_cheque" class="right_payment_quick_cls">
                
                    <input type="text" id="right_payment_quick_cheque_bank" placeholder="Bank Code" class="okeyboard">
                    <input type="text" id="right_payment_quick_cheque_date" class="dateField" placeholder="Date" class="okeyboard">
                    <input type="text" id="right_payment_quick_cheque_no" placeholder="Cheque No" class="okeyboard">
                
                </div>
                
                
            </div>
            
            <div id="right_payment_add_pmnt">
            
                <input type="text" id="paymentAmt" placeholder="0.00" class="okeyboard">
                
                <a id="addPaymentBtn">Add</a>
                
                <a class="complete" id="completeInv">Complete</a>
            
            </div>
        
        </div>
    
    </div>
</div>
<?php } ?>
<div id="shift_modal" <?php if($data['shift_status']){ echo 'style="display:none"'; } ?>>

	<div id="shift_modal_in">
    
    	<div id="shift_modal_in_msg">
        
        	<i class="fa-light fa-power-off"></i>	
            
            <h2>Shift Start</h2>
            <p>We found the following error. Please fix it and retry.</p>
            
        </div>
        
        <select id="shiftPoint">
            
            <option value="">- Choose -</option>
            <?php
			foreach($data['cashierPoints'] as $cat){
			?>
            <option value="<?php echo $cat['cashierpoint_id']; ?>"><?php echo $cat['name']; ?></option>
            <?php } ?>
        
        </select>
        
        <a id="shift_modal_start" accesskey="x">Start</a>
    
    </div>

</div>

<div id="popup_form">


	<div id="popup_form_in">
    
    	<a id="popup_form_in_close">X</a>
    
    	<div id="popup_form_in_form">
        
        
        </div>
    
    </div>

</div>


<div id="modal_loading"><div id="modal_loading_in"><div id="modal_loading_in_icon"></div></div></div>

<div id="modal">

	<div id="modal_in">
    
    	<div id="modal_in_error">
        
        	<i class="fa-solid fa-brake-warning"></i>
            
            <h2>Error Found</h2>
            <p>We found the following error. Please fix it and retry.</p>
            
        </div>
        
        <ul>
        
        </ul>
        
        <a id="modal_in_close" accesskey="x">Close</a>
    
    </div>

</div>


<div id="modal_success">

	<div id="modal_success_in">
    
    	<div id="modal_success_in_error">
        
    		<i class="fa-solid fa-shield-check"></i>
            
            <h2>Successfully Completed!!!</h2>
            <p></p>
            
        </div>
        
        <a id="modal_success_in_close" accesskey="x">Close</a>
    
    </div>

</div>

<script type="text/javascript" src="<?php echo _JS; ?>core/salesscreen.js"></script>

</body>
</html>
