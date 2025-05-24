<?php

class SystemSetupConnector {

    public function index() {
		
		
		global $db;
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemSetupQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			$data = [];
			$error_no = 0;
			$error_msg = [];
			
			$data['titleTag'] 	= 'Setup | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['update_url'] 	= $defCls->genURL("system/setup/");
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			

			$formData = [];
			// General Settings
			$formData['companyName'] = isset($_REQUEST['companyName']) ? $db->request('companyName') : $defCls->master('companyName');
			$formData['address'] = isset($_REQUEST['address']) ? $db->request('address') : $defCls->master('address');
			$formData['email'] = isset($_REQUEST['email']) ? $db->request('email') : $defCls->master('email');
			$formData['telephone1'] = isset($_REQUEST['telephone1']) ? $db->request('telephone1') : $defCls->master('telephone1');
			$formData['telephone2'] = isset($_REQUEST['telephone2']) ? $db->request('telephone2') : $defCls->master('telephone2');
			$formData['telephone3'] = isset($_REQUEST['telephone3']) ? $db->request('telephone3') : $defCls->master('telephone3');
			$formData['telephone4'] = isset($_REQUEST['telephone4']) ? $db->request('telephone4') : $defCls->master('telephone4');
			$formData['telephone5'] = isset($_REQUEST['telephone5']) ? $db->request('telephone5') : $defCls->master('telephone5');
			$formData['website'] = isset($_REQUEST['website']) ? $db->request('website') : $defCls->master('website');
			$formData['main_store'] = isset($_REQUEST['main_store']) ? $db->request('main_store') : $defCls->master('main_store');
			
			// Invoice Settings
			$formData['invoice_no'] = isset($_REQUEST['invoice_no']) ? $db->request('invoice_no') : $defCls->master('invoice_no');
			$formData['invoice_header'] = isset($_REQUEST['invoice_header']) ? $db->request('invoice_header') : $defCls->master('invoice_header');
			$formData['invoice_footer'] = isset($_REQUEST['invoice_footer']) ? $db->request('invoice_footer') : $defCls->master('invoice_footer');
			$formData['invoice_logo_print'] = isset($_REQUEST['invoice_logo_print']) ? $db->request('invoice_logo_print') : $defCls->master('invoice_logo_print');
			$formData['invoicePrint'] = isset($_REQUEST['invoicePrint']) ? $db->request('invoicePrint') : $defCls->master('invoicePrint');
			
			// Return Settings
			$formData['return_print_header'] = isset($_REQUEST['return_print_header']) ? $db->request('return_print_header') : $defCls->master('return_print_header');
			$formData['return_print_footer'] = isset($_REQUEST['return_print_footer']) ? $db->request('return_print_footer') : $defCls->master('return_print_footer');
			
			// Loyalty Settings
			$formData['loyalty_points'] = isset($_REQUEST['loyalty_points']) ? $db->request('loyalty_points') : $defCls->master('loyalty_points');
			$formData['loyalty_points_cash'] = isset($_REQUEST['loyalty_points_cash']) ? $db->request('loyalty_points_cash') : $defCls->master('loyalty_points_cash');
			$formData['loyalty_points_card'] = isset($_REQUEST['loyalty_points_card']) ? $db->request('loyalty_points_card') : $defCls->master('loyalty_points_card');
			$formData['loyalty_points_cheque'] = isset($_REQUEST['loyalty_points_cheque']) ? $db->request('loyalty_points_cheque') : $defCls->master('loyalty_points_cheque');
			$formData['loyalty_points_credit'] = isset($_REQUEST['loyalty_points_credit']) ? $db->request('loyalty_points_credit') : $defCls->master('loyalty_points_credit');
			$formData['loyalty_points_gift_card'] = isset($_REQUEST['loyalty_points_gift_card']) ? $db->request('loyalty_points_gift_card') : $defCls->master('loyalty_points_gift_card');
			$formData['loyalty_points_loyalty'] = isset($_REQUEST['loyalty_points_loyalty']) ? $db->request('loyalty_points_loyalty') : $defCls->master('loyalty_points_loyalty');
			$formData['loyalty_points_return'] = isset($_REQUEST['loyalty_points_return']) ? $db->request('loyalty_points_return') : $defCls->master('loyalty_points_return');

			
			// Images
			if (isset($_FILES['logo']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
				
				$logoFileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
				
				$fileSizeBytes = $_FILES['logo']['size'];
				$fileSizeKB = round($fileSizeBytes / 1024, 2);
			
				// Optional: Validate allowed extensions
				$allowed = ['jpg', 'jpeg', 'png', 'gif'];
				if (!in_array($logoFileExt, $allowed)) {
					$error_msg[]="Invalid logo file format!"; $error_no++;
				}
				
				if($fileSizeKB>512){ $error_msg[]="Invalid logo file size! (512KB Allowed)"; $error_no++; }
			
				$logoNewName = 'logo-' . time() . '.' . $logoFileExt;
				$logoNewNameDb = 'company/'.$logoNewName;
				
			}
			else{ $logoNewNameDb = $defCls->master('logo'); }
			
			$formData['logo'] = $logoNewNameDb;
			$oldLogoName = $defCls->master('logo');
			
			///
			
			
			
			// Images
			if (isset($_FILES['invoice_logo']) && is_uploaded_file($_FILES['invoice_logo']['tmp_name'])) {
				
				$invoiceLogoFileExt = strtolower(pathinfo($_FILES['invoice_logo']['name'], PATHINFO_EXTENSION));
				
				$fileSizeBytes = $_FILES['invoice_logo']['size'];
				$fileSizeKB = round($fileSizeBytes / 1024, 2);
				
				// Optional: Validate allowed extensions
				$allowed = ['jpg', 'jpeg', 'png', 'gif'];
				if (!in_array($invoiceLogoFileExt, $allowed)) {
					$error_msg[]="Invalid invoice logo file format!"; $error_no++;
				}
				
				if($fileSizeKB>512){ $error_msg[]="Invalid invoice logo file size! (512KB Allowed)"; $error_no++; }
			
				$invoiceLogoNewName = 'invoice_logo-' . time() . '.' . $invoiceLogoFileExt;
				$invoiceLogoNewNameDb = 'company/'.$invoiceLogoNewName;
				
			}
			else{ $invoiceLogoNewNameDb = $defCls->master('invoice_logo'); }
			
			$formData['invoice_logo'] = $invoiceLogoNewNameDb;
			$oldInvoiceLogoName = $defCls->master('invoice_logo');

			
			// Barcode Setting
			$formData['barcode_no_start'] = isset($_REQUEST['barcode_no_start']) ? $db->request('barcode_no_start') : $defCls->master('barcode_no_start');

			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$error_no)
				{
					
					if($SystemSetupQuery->update($formData))
					{
						if(isset($logoNewName))
						{
							unlink(_MAIN.'ux/uploads/'.$oldLogoName);
							move_uploaded_file($_FILES['logo']['tmp_name'], _MAIN.'ux/uploads/company/'.$logoNewName);
						}
						if(isset($invoiceLogoNewName))
						{
							unlink(_MAIN.'ux/uploads/'.$oldInvoiceLogoName);
							move_uploaded_file($_FILES['invoice_logo']['tmp_name'], _MAIN.'ux/uploads/company/'.$invoiceLogoNewName);
						}
					}
					
					$firewallCls->addLog("Setup Update");
					
					$json['success']=true;
					$json['success_msg']="Successfully Updated. Refresh your page to see the changes!";

					
				}
				if($error_no)
				{
					
					$error_msg_list='';
					foreach($error_msg as $e)
					{
						if($e)
						{
							$error_msg_list.='<li>'.$e.'</li>';
						}
					}
					$json['error']=true;
					$json['error_msg']=$error_msg_list;
				}
				echo json_encode($json);
				
			}
			else
			{
			
				require_once _HTML."common/header.php";
				require_once _HTML."common/menu.php";
				require_once _HTML."system/setup.php";
				require_once _HTML."common/footer.php";
				
			}
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	
}
