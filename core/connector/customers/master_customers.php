<?php

class CustomersMasterCustomersConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Customer Report | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("customers/master_customers/create");
			$data['load_table_url'] = $defCls->genURL('customers/master_customers/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/master_customers.php";
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
		global $CustomersMasterCustomersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_phone_number'])){ $search_phone_number=$db->request('search_phone_number'); }
			else{ $search_phone_number=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_phone_number){ $sql.=" AND phone_number LIKE '%$search_phone_number%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $CustomersMasterCustomersQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$customers = $CustomersMasterCustomersQuery->gets($sql);
			
			$data['customers'] = array();
			
			foreach($customers as $cat)
			{
				
				$customer_group = 'NONE';
				
				if($CustomersMasterCustomergroupsQuery->data($cat['customer_group_id'],'name')){ 
					$customer_group = $CustomersMasterCustomergroupsQuery->data($cat['customer_group_id'],'name');
				}
				
				$data['customers'][] = array(
										'customer_id' => $cat['customer_id'],
										'name' => $cat['name'],
										'phone_number' => $cat['phone_number'],
										'customer_group' => $customer_group,
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('customers/master_customers/edit/'.$cat['customer_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($customers).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'customers/master_customers_table.php';
			if (!file_exists($this_required_file)) {
				error_log("File not found: ".$this_required_file);
				die('File not found:'.$this_required_file);
			}
			else {
	
				require_once($this_required_file);
				
			}
		}
	}
	

    public function create() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."customers/master_customers/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['customer_group_list'] = $CustomersMasterCustomergroupsQuery->gets("ORDER BY name ASC");
				
			if(isset($_REQUEST['customer_group_id'])){ $data['customer_group_id'] = $db->request('customer_group_id'); }
			else{ $data['customer_group_id'] = ''; }
			
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name'); }
			else{ $data['name'] = ''; }
			
			if(isset($_REQUEST['phone_number'])){ $data['phone_number'] = $db->request('phone_number'); }
			else{ $data['phone_number'] = ''; }
			
			if(isset($_REQUEST['email'])){ $data['email'] = $db->request('email'); }
			else{ $data['email'] = ''; }
			
			if(isset($_REQUEST['address'])){ $data['address'] = $db->request('address'); }
			else{ $data['address'] = ''; }
			
			if(isset($_REQUEST['credit_limit'])){ $data['credit_limit'] = $db->request('credit_limit'); }
			else{ $data['credit_limit'] = ''; }
			
			if(isset($_REQUEST['settlement_days'])){ $data['settlement_days'] = $db->request('settlement_days'); }
			else{ $data['settlement_days'] = ''; }
			
			if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
			else{ $data['remarks'] = ''; }
			
			if(isset($_REQUEST['card_no'])){ $data['card_no'] = $db->request('card_no'); }
			else{ $data['card_no'] = ''; }

			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countCustomersByPhoneNo = $CustomersMasterCustomersQuery->gets("WHERE phone_number='".$data['phone_number']."'");
				$countCustomersByPhoneNo = count($countCustomersByPhoneNo);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if(strlen($data['phone_number'])<10){ $error_msg[]="Phone no must be 10 digits"; $error_no++; }
				if($countCustomersByPhoneNo){ $error_msg[]="The phone number already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$CustomersMasterCustomersQuery->create($data);
					$firewallCls->addLog("Customer Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'customers/master_customers_form.php';
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
		global $CustomersMasterCustomersQuery;
		global $CustomersMasterCustomergroupsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getCustomerInfo = $CustomersMasterCustomersQuery->get($id);
			
			if($getCustomerInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."customers/master_customers/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['customer_id'] = $getCustomerInfo['customer_id'];
			
				$data['customer_group_list'] = $CustomersMasterCustomergroupsQuery->gets("ORDER BY name ASC");
					
				if(isset($_REQUEST['customer_group_id'])){ $data['customer_group_id'] = $db->request('customer_group_id'); }
				else{ $data['customer_group_id'] = $getCustomerInfo['customer_group_id']; }
				
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name'); }
				else{ $data['name'] = $getCustomerInfo['name']; }
				
				if(isset($_REQUEST['phone_number'])){ $data['phone_number'] = $db->request('phone_number'); }
				else{ $data['phone_number'] = $getCustomerInfo['phone_number']; }
				
				if(isset($_REQUEST['email'])){ $data['email'] = $db->request('email'); }
				else{ $data['email'] = $getCustomerInfo['email']; }
				
				if(isset($_REQUEST['address'])){ $data['address'] = $db->request('address'); }
				else{ $data['address'] = $getCustomerInfo['address']; }
				
				if(isset($_REQUEST['credit_limit'])){ $data['credit_limit'] = $db->request('credit_limit'); }
				else{ $data['credit_limit'] = $getCustomerInfo['credit_limit']; }
				
				if(isset($_REQUEST['settlement_days'])){ $data['settlement_days'] = $db->request('settlement_days'); }
				else{ $data['settlement_days'] = $getCustomerInfo['settlement_days']; }
				
				if(isset($_REQUEST['remarks'])){ $data['remarks'] = $db->request('remarks'); }
				else{ $data['remarks'] = $getCustomerInfo['remarks']; }
				
				if(isset($_REQUEST['card_no'])){ $data['card_no'] = $db->request('card_no'); }
				else{ $data['card_no'] = $getCustomerInfo['card_no']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status'); }
				else{ $data['status'] = $getCustomerInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					if(strlen($data['phone_number'])<10){ $error_msg[]="Phone no must be 10 digits"; $error_no++; }
					
					if($db->request('phone_number')!==$getCustomerInfo['phone_number'])
					{
						$countCustomersByPhoneNo = $CustomersMasterCustomersQuery->gets("WHERE phone_number='".$data['phone_number']."'");
						$countCustomersByPhoneNo = count($countCustomersByPhoneNo);
						
						if($countCustomersByPhoneNo){ $error_msg[]="The phone number already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$CustomersMasterCustomersQuery->edit($data);
						$firewallCls->addLog("Customers Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'customers/master_customers_form.php';
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
				$error_msg[]="Invalid customer Id"; $error_no++;
					
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
	public function autoComplete()
	{	
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $SystemMasterUsersQuery;
		global $CustomersMasterCustomersQuery;
		
		
		$data = [];
		$json = [];
		
		if($firewallCls->verifyUser())
		{
			
			$term = $db->request('term');
			
			$sql = "WHERE name LIKE \"%".$term."%\" OR phone_number LIKE \"%".$term."%\"";
	
			$customerInfo = $CustomersMasterCustomersQuery->gets($sql);
		
			foreach($customerInfo as $itm)
			{
				
				$json[]=array(
						'value'=> $itm['customer_id'],
						'label'=> $itm['name']. ' ('.$itm['phone_number'].')',
						'points'=> $defCls->num($itm['loyalty_points'])
							);
			}
		}
		
		echo json_encode($json);
	
	}
	
}
