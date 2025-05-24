<div id="right">

	<div id="filter_head">
    
    	<h1>Setup</h1>
    
    </div>
    
    <div id="filter_content">
        
		<form method="post" id="saveForm" data-url="<?php echo $data['update_url']; ?>">
    
    	<h3>General Setting</h3>

        <div class="col_1">
            <label for="companyName">Company Name</label>
            <input type="text" name="companyName" id="companyName" placeholder="Company Name" value="<?php echo $formData['companyName']; ?>">
        </div>
        
        <div class="col_1">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $formData['address']; ?>">
        </div>
        
        <div class="col_4">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $formData['email']; ?>">
        </div>
        
        <div class="col_4">
            <label for="telephone1">Telephone 1</label>
            <input type="text" name="telephone1" id="telephone1" placeholder="Telephone 1" value="<?php echo $formData['telephone1']; ?>">
        </div>
        
        <div class="col_4">
            <label for="telephone2">Telephone 2</label>
            <input type="text" name="telephone2" id="telephone2" placeholder="Telephone 2" value="<?php echo $formData['telephone2']; ?>">
        </div>
        
        <div class="col_4">
            <label for="telephone3">Telephone 3</label>
            <input type="text" name="telephone3" id="telephone3" placeholder="Telephone 3" value="<?php echo $formData['telephone3']; ?>">
        </div>
        
        <div class="col_4">
            <label for="telephone4">Telephone 4</label>
            <input type="text" name="telephone4" id="telephone4" placeholder="Telephone 4" value="<?php echo $formData['telephone4']; ?>">
        </div>
        
        <div class="col_4">
            <label for="telephone5">Telephone 5</label>
            <input type="text" name="telephone5" id="telephone5" placeholder="Telephone 5" value="<?php echo $formData['telephone5']; ?>">
        </div>
        
        <div class="col_4">
            <label for="website">Website</label>
            <input type="text" name="website" id="website" placeholder="Website" value="<?php echo $formData['website']; ?>">
        </div>
        
        <div class="col_4">
            <label for="main_store">Main Store</label>
            <input type="text" name="main_store" id="main_store" placeholder="Main Store" value="<?php echo $formData['main_store']; ?>">
        </div>
        
        <h3>Invoice Setting</h3>
        
        <div class="col_2">
            <label for="invoice_no">Invoice Number Start From</label>
            <input type="text" name="invoice_no" id="invoice_no" placeholder="Invoice Number Start From" value="<?php echo $formData['invoice_no']; ?>">
        </div>
        
        <div class="col_2">
            <label for="invoice_logo_print">Print Logo on Invoice</label>
            <select name="invoice_logo_print" id="invoice_logo_print">
            
                <option value="1" <?php if($formData['invoice_logo_print']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['invoice_logo_print']==0){ echo 'selected'; } ?>>No</option>
            
            </select>
        </div>
        
        <div class="col_2">
            <label for="invoicePrint">Invoice Print</label>
            <select name="invoicePrint" id="invoicePrint">
            
                <option value="POS" <?php if($formData['invoicePrint']=='POS'){ echo 'selected'; } ?>>POS</option>
                <option value="A5P" <?php if($formData['invoicePrint']=='A5P'){ echo 'selected'; } ?>>A5 Portrait</option>
                <option value="A5L" <?php if($formData['invoicePrint']=='A5L'){ echo 'selected'; } ?>>A5 Landscape</option>
            
            </select>
        </div>
        
        <div class="col_2">
            <label for="invoice_header">Invoice Header Text</label>
            <textarea name="invoice_header" id="invoice_header" placeholder="Header Text" style="height:85px"><?php echo $formData['invoice_header']; ?></textarea>
        </div>
        
        <div class="col_2">
            <label for="invoice_footer">Invoice Footer Text</label>
            <textarea name="invoice_footer" id="invoice_footer" placeholder="Footer Text" style="height:85px"><?php echo $formData['invoice_footer']; ?></textarea>
        </div>
        
        <h3>Return Setting</h3>
        
        <div class="col_2">
            <label for="return_print_header">Return Header Text</label>
            <textarea name="return_print_header" id="return_print_header" placeholder="Header Text" style="height:85px"><?php echo $formData['return_print_header']; ?></textarea>
        </div>
        
        <div class="col_2">
            <label for="return_print_footer">Return Footer Text</label>
            <textarea name="return_print_footer" id="return_print_footer" placeholder="Footer Text" style="height:85px"><?php echo $formData['return_print_footer']; ?></textarea>
        </div>
        
        <h3>Loyalty Program Setting</h3>

        <div class="col_4">
            <label for="loyalty_points">Loyalty Point Ratio (e.g., Rs.100 = 1)</label>
            <input type="text" name="loyalty_points" id="loyalty_points" placeholder="100=1" value="<?php echo $formData['loyalty_points']; ?>">
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_cash">Earn Points for Cash Payment</label>
            <select name="loyalty_points_cash" id="loyalty_points_cash">
                <option value="1" <?php if($formData['loyalty_points_cash']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_cash']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_card">Earn Points for Card Payment</label>
            <select name="loyalty_points_card" id="loyalty_points_card">
                <option value="1" <?php if($formData['loyalty_points_card']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_card']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_cheque">Earn Points for Cheque Payment</label>
            <select name="loyalty_points_cheque" id="loyalty_points_cheque">
                <option value="1" <?php if($formData['loyalty_points_cheque']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_cheque']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_credit">Earn Points for Credit Payment</label>
            <select name="loyalty_points_credit" id="loyalty_points_credit">
                <option value="1" <?php if($formData['loyalty_points_credit']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_credit']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_gift_card">Earn Points for Gift Card Payment</label>
            <select name="loyalty_points_gift_card" id="loyalty_points_gift_card">
                <option value="1" <?php if($formData['loyalty_points_gift_card']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_gift_card']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_loyalty">Earn Points for Loyalty Payment</label>
            <select name="loyalty_points_loyalty" id="loyalty_points_loyalty">
                <option value="1" <?php if($formData['loyalty_points_loyalty']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_loyalty']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        
        <div class="col_4">
            <label for="loyalty_points_return">Earn Points for Return Payment</label>
            <select name="loyalty_points_return" id="loyalty_points_return">
                <option value="1" <?php if($formData['loyalty_points_return']==1){ echo 'selected'; } ?>>Yes</option>
                <option value="0" <?php if($formData['loyalty_points_return']==0){ echo 'selected'; } ?>>No</option>
            </select>
        </div>


        
        <h3>Barcode Setting</h3>
        
        <div class="col_4">
            <label for="barcode_no_start">Barcode Prefix (e.g., OM)</label>
            <input type="text" name="barcode_no_start" id="barcode_no_start" placeholder="Prefix (e.g., OM)" value="<?php echo $formData['barcode_no_start']; ?>">
        </div>

        
        <h3>Image Setting</h3>
        
        <div class="col_2 uploadimage text-center">
            
            
            <!-- Logo Input -->
             <div id="logoInput" class="upload-area">
                <div class="upload-title">Upload Logo</div>
                <input type="file" accept="image/*" id="logoFile" name="logo">
                <img id="logoPreview" class="preview-img" src="<?php echo _UPLOADS.$oldLogoName; ?>" alt="Logo Preview">
              </div>

  
        </div>
       
        
        <div class="col_2 uploadimage text-center">
            
             <div id="invoiceLogoInput" class="upload-area">
                <div class="upload-title">Upload Invoice Logo</div>
                <input type="file" accept="image/*" id="invoiceLogoFile" name="invoice_logo">
                <img id="invoiceLogoPreview" class="preview-img" src="<?php echo _UPLOADS.$oldInvoiceLogoName; ?>" alt="Invoice Logo Preview">
              </div>

            
        </div>
        
        <script>
    function setupUploadArea(areaId, inputId, previewId) {
      const area = document.getElementById(areaId);
      const fileInput = document.getElementById(inputId);
      const preview = document.getElementById(previewId);

      area.addEventListener('click', () => fileInput.click());

      fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
          displayImage(fileInput.files[0], preview);
        }
      });

      area.addEventListener('dragover', e => {
        e.preventDefault();
        area.classList.add('dragover');
      });

      area.addEventListener('dragleave', () => {
        area.classList.remove('dragover');
      });

      area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
          displayImage(file, preview);
        }
      });
    }

    function displayImage(file, previewElement) {
      const reader = new FileReader();
      reader.onload = e => {
        previewElement.src = e.target.result;
        previewElement.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }

    // Setup both upload areas
    setupUploadArea('logoInput', 'logoFile', 'logoPreview');
    setupUploadArea('invoiceLogoInput', 'invoiceLogoFile', 'invoiceLogoPreview');
  </script>
        
        <style>
		.upload-area {
			border: 2px dashed #ccc;
			background:#FFF;
			border-radius:20px;
			padding: 20px;
			margin: 0px auto;
			cursor: pointer;
			text-align:center;
			position: relative;
		}
		
		.upload-area.dragover {
			background-color: #eef;
			border-color: #333;
		}
		
		.upload-title {
			margin-top: 5px; margin-bottom:15px;
		}
		
		input[type="file"] {
			display: none;
		}
		
		.preview-img {
			max-width:90%; height:85px;
			margin: 0px auto;
		}
		</style>
        
        
        <div class="col_1">
        
        	
        <button form="saveForm" type="submit" id="saveFormBtn">Save Now</button>
        
        </div>
        
        </form>
    
    </div>

</div>





<div id="popup_form">


	<div id="popup_form_in">
    
    	<a id="popup_form_in_close">X</a>
    
    	<div id="popup_form_in_form">
        
        
        </div>
    
    </div>

</div>


<div id="popup_form_sub">


	<div id="popup_form_in_sub">
    
    	<a id="popup_form_in_close_sub">X</a>
    
    	<div id="popup_form_in_form_sub">
        
        
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