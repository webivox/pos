<?php

class SuppliersMasterSuppliersConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Suppliers | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("suppliers/master_suppliers/create");
			$data['load_table_url'] = $defCls->genURL('suppliers/master_suppliers/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."suppliers/master_suppliers.php";
			require_once _HTML."common/footer.php";
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	public function load()
	{
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_contact_person'])){ $search_contact_person=$db->request('search_contact_person'); }
			else{ $search_contact_person=''; }
			
			if(isset($_REQUEST['search_phone_number'])){ $search_phone_number=$db->request('search_phone_number'); }
			else{ $search_phone_number=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE supplier_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_contact_person){ $sql.=" AND contact_person LIKE '%$search_contact_person%'"; }
			if($search_phone_number){ $sql.=" AND phone_number LIKE '%$search_phone_number%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SuppliersMasterSuppliersQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$suppliers = $SuppliersMasterSuppliersQuery->gets($sql);
			
			$data['suppliers'] = array();
			
			foreach($suppliers as $cat)
			{
				$data['suppliers'][] = array(
										'supplier_id' => $cat['supplier_id'],
										'name' => $cat['name'],
										'contact_person' => $cat['contact_person'],
										'phone_number' => $cat['phone_number'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('suppliers/master_suppliers/edit/'.$cat['supplier_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($suppliers).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'suppliers/master_suppliers_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	
	
	public function autoComplete()
	{	
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$term = $db->request('term');
			
			$sql = "WHERE name LIKE \"%".$term."%\"";
	
			$supplierInfo = $SuppliersMasterSuppliersQuery->gets($sql);
		
			foreach($supplierInfo as $itm)
			{
				
				$json[]=array(
						'value'=> $itm['supplier_id'],
						'label'=> $itm['name']
							);
			}
		}
		
		echo json_encode($json);
	
	}

    public function create() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."suppliers/master_suppliers/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
				
			if(isset($_REQUEST['contact_person'])){ $data['contact_person'] = $db->request('contact_person');}
			else{ $data['contact_person'] = ''; }
				
			if(isset($_REQUEST['phone_number'])){ $data['phone_number'] = $db->request('phone_number');}
			else{ $data['phone_number'] = ''; }
				
			if(isset($_REQUEST['email'])){ $data['email'] = $db->request('email');}
			else{ $data['email'] = ''; }
				
			if(isset($_REQUEST['address'])){ $data['address'] = $db->request('address');}
			else{ $data['address'] = ''; }
				
			if(isset($_REQUEST['city'])){ $data['city'] = $db->request('city');}
			else{ $data['city'] = ''; }
				
			if(isset($_REQUEST['state'])){ $data['state'] = $db->request('state');}
			else{ $data['state'] = ''; }
				
			if(isset($_REQUEST['country'])){ $data['country'] = $db->request('country');}
			else{ $data['country'] = ''; }
				
			if(isset($_REQUEST['payment_terms'])){ $data['payment_terms'] = $db->request('payment_terms');}
			else{ $data['payment_terms'] = ''; }
				
			if(isset($_REQUEST['bank_details'])){ $data['bank_details'] = $db->request('bank_details');}
			else{ $data['bank_details'] = ''; }
				
			if(isset($_REQUEST['tax_number'])){ $data['tax_number'] = $db->request('tax_number');}
			else{ $data['tax_number'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countSuppliersByName = $SuppliersMasterSuppliersQuery->gets("WHERE name='".$data['name']."'");
				$countSuppliersByName = count($countSuppliersByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countSuppliersByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SuppliersMasterSuppliersQuery->create($data);
					$firewallCls->addLog("Supplier Created: ".$data['name']);
					
					$json['success']=true;
					$json['success_msg']="Sucessfully Created";

					
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
	
				$this_required_file = _HTML.'suppliers/master_suppliers_form.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}			
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
	

    public function edit() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SuppliersMasterSuppliersQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getSupplierInfo = $SuppliersMasterSuppliersQuery->get($id);
			
			if($getSupplierInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."suppliers/master_suppliers/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['supplier_id'] = $getSupplierInfo['supplier_id'];
					
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getSupplierInfo['name']; }
					
				if(isset($_REQUEST['contact_person'])){ $data['contact_person'] = $db->request('contact_person');}
				else{ $data['contact_person'] = $getSupplierInfo['contact_person']; }
					
				if(isset($_REQUEST['phone_number'])){ $data['phone_number'] = $db->request('phone_number');}
				else{ $data['phone_number'] =  $getSupplierInfo['phone_number']; }
					
				if(isset($_REQUEST['email'])){ $data['email'] = $db->request('email');}
				else{ $data['email'] =  $getSupplierInfo['email']; }
					
				if(isset($_REQUEST['address'])){ $data['address'] = $db->request('address');}
				else{ $data['address'] =  $getSupplierInfo['address']; }
					
				if(isset($_REQUEST['city'])){ $data['city'] = $db->request('city');}
				else{ $data['city'] =  $getSupplierInfo['city']; }
					
				if(isset($_REQUEST['state'])){ $data['state'] = $db->request('state');}
				else{ $data['state'] =  $getSupplierInfo['state']; }
					
				if(isset($_REQUEST['country'])){ $data['country'] = $db->request('country');}
				else{ $data['country'] =  $getSupplierInfo['country']; }
					
				if(isset($_REQUEST['payment_terms'])){ $data['payment_terms'] = $db->request('payment_terms');}
				else{ $data['payment_terms'] =  $getSupplierInfo['payment_terms']; }
					
				if(isset($_REQUEST['bank_details'])){ $data['bank_details'] = $db->request('bank_details');}
				else{ $data['bank_details'] =  $getSupplierInfo['bank_details']; }
					
				if(isset($_REQUEST['tax_number'])){ $data['tax_number'] = $db->request('tax_number');}
				else{ $data['tax_number'] =  $getSupplierInfo['tax_number']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = 0; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getSupplierInfo['name'])
					{
						$countSuppliersByName = $SuppliersMasterSuppliersQuery->gets("WHERE name='".$data['name']."'");
						$countSuppliersByName = count($countSuppliersByName);
						
						if($countSuppliersByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$SuppliersMasterSuppliersQuery->edit($data);
						$firewallCls->addLog("Suppliers Updated: ".$data['name']);
						
						$json['success']=true;
						$json['success_msg']="Sucessfully Updated";
						
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
		
					$this_required_file = _HTML.'suppliers/master_suppliers_form.php';
					if (!file_exists($this_required_file)) {
						error_log("File not found: ".$this_required_file);
						die('File not found:'.$this_required_file);
					}
					else {
		
						require_once($this_required_file);
						
					}
				}	
			}
			else
			{
				$error_msg[]="Invalid supplier Id"; $error_no++;
					
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
		}
		else
		{
			header("location:"._SERVER);
		}
		
	}
	
}
