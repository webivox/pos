<?php

class SystemMasterLocationsConnector {

    public function index() {
		
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Locations | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("system/master_locations/create");
			$data['load_table_url'] = $defCls->genURL('system/master_locations/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."system/master_locations.php";
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
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE location_id!=0";
			
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SystemMasterLocationsQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY name ASC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$locations = $SystemMasterLocationsQuery->gets($sql);
			
			$data['locations'] = array();
			
			foreach($locations as $cat)
			{
				$data['locations'][] = array(
										'location_id' => $cat['location_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('system/master_locations/edit/'.$cat['location_id']),
										'deleteURL' => $defCls->genURL('system/master_locations/delete/'.$cat['location_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($locations).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'system/master_locations_table.php';
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
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$term = $db->request('term');
			
			$sql = "WHERE name LIKE \"%".$term."%\"";
	
			$locationInfo = $SystemMasterLocationsQuery->gets($sql);
		
			foreach($locationInfo as $itm)
			{
				
				$json[]=array(
						'value'=> $itm['location_id'],
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
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."system/master_locations/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
			if(isset($_REQUEST['name'])){ 
				$data['name'] = $db->request('name');
			} else { 
				$data['name'] = ''; 
			}
			
			if(isset($_REQUEST['address'])){ 
				$data['address'] = $db->request('address');
			} else { 
				$data['address'] = ''; 
			}
			
			if(isset($_REQUEST['phone_number'])){ 
				$data['phone_number'] = $db->request('phone_number');
			} else { 
				$data['phone_number'] = ''; 
			}
			
			if(isset($_REQUEST['email'])){ 
				$data['email'] = $db->request('email');
			} else { 
				$data['email'] = ''; 
			}
			
			if(isset($_REQUEST['invoice_no_start'])){ 
				$data['invoice_no_start'] = $db->request('invoice_no_start');
			} else { 
				$data['invoice_no_start'] = ''; 
			}
			
			if(isset($_REQUEST['status'])){ 
				$data['status'] = $db->request('status');
			}
			elseif(isset($_REQUEST['status'])){ $data['status'] = 0; }
			else { 
				$data['status'] = 0; 
			}

			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countLocationsByName = $SystemMasterLocationsQuery->gets("WHERE name='".$data['name']."'");
				$countLocationsByName = count($countLocationsByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countLocationsByName){ $error_msg[]="The name already exists"; $error_no++; }
				if(strlen($data['phone_number'])<10){ $error_msg[]="Phone number must be minimum 3 letters"; $error_no++; }
				if(strlen($data['invoice_no_start'])<2){ $error_msg[]="Invoice no must be minimum 2 letters"; $error_no++; }
				
				if(!$error_no)
				{
					
					$SystemMasterLocationsQuery->create($data);
					$firewallCls->addLog("Location Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'system/master_locations_form.php';
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
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getLocationInfo = $SystemMasterLocationsQuery->get($id);
			
			if($getLocationInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."system/master_locations/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['location_id'] = $getLocationInfo['location_id'];
					
				if(isset($_REQUEST['name'])){ 
					$data['name'] = $db->request('name');
				} else { 
					$data['name'] = $getLocationInfo['name']; 
				}
				
				if(isset($_REQUEST['manager_id'])){ 
					$data['manager_id'] = $db->request('manager_id');
				} else { 
					$data['manager_id'] = $getLocationInfo['manager_id']; 
				}
				
				if(isset($_REQUEST['address'])){ 
					$data['address'] = $db->request('address');
				} else { 
					$data['address'] = $getLocationInfo['address']; 
				}
				
				if(isset($_REQUEST['phone_number'])){ 
					$data['phone_number'] = $db->request('phone_number');
				} else { 
					$data['phone_number'] = $getLocationInfo['phone_number']; 
				}
				
				if(isset($_REQUEST['email'])){ 
					$data['email'] = $db->request('email');
				} else { 
					$data['email'] = $getLocationInfo['email']; 
				}
				
				if(isset($_REQUEST['invoice_no_start'])){ 
					$data['invoice_no_start'] = $db->request('invoice_no_start');
				} else { 
					$data['invoice_no_start'] = $getLocationInfo['invoice_no_start']; 
				}
				
				if(isset($_REQUEST['status'])){ 
					$data['status'] = $db->request('status');
				}
				else { 
					$data['status'] = $getLocationInfo['status']; 
				}
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if($db->request('name')!==$getLocationInfo['name'])
					{
						$countLocationsByName = $SystemMasterLocationsQuery->gets("WHERE name='".$data['name']."'");
						$countLocationsByName = count($countLocationsByName);
						
						if($countLocationsByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					if(strlen($data['phone_number'])<10){ $error_msg[]="Phone number must be minimum 3 letters"; $error_no++; }
					if(strlen($data['invoice_no_start'])<2){ $error_msg[]="Invoice no must be minimum 2 letters"; $error_no++; }
					
					if(!$error_no)
					{
						
						$SystemMasterLocationsQuery->edit($data);
						$firewallCls->addLog("Locations Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'system/master_locations_form.php';
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
				$error_msg[]="Invalid location Id"; $error_no++;
					
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
	
	
	
	
	

    public function delete() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getLocationInfo = $SystemMasterLocationsQuery->get($id);
			
			if($getLocationInfo)
			{
				$locationName = $getLocationInfo['name'];
				
				$deleteValue = $SystemMasterLocationsQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("Locations Deleted: ".$locationName);
				
					$json['success']=true;
					$json['success_msg']="Sucessfully Updated";
				
				}
				elseif(is_array($deleteValue))
				{
					foreach($deleteValue as $v)
					{
						$error_msg[]=$v; $error_no++;
					}
					
				}
				else
				{
					$error_msg[]="An error occurred while attempting to delete the location!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid location Id"; $error_no++;
				
				
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
			header("location:"._SERVER);
		}
		
	}
	
}
