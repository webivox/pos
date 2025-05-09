<?php

class InventoryMasterCategoryConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Inventory Category | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("inventory/master_category/create");
			$data['load_table_url'] = $defCls->genURL('inventory/master_category/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			$data['parent_category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0");
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."inventory/master_category.php";
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
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
	
			if(isset($_REQUEST['search_parent_category'])){ $search_parent_category=$db->request('search_parent_category'); }
			else{ $search_parent_category=''; }
			
			if(isset($_REQUEST['search_name'])){ $search_name=$db->request('search_name'); }
			else{ $search_name=''; }
			
			if(isset($_REQUEST['search_status'])){ $search_status=$db->request('search_status'); }
			else{ $search_status=''; }
			
			if(isset($_REQUEST['pageno'])){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE category_id!=0";
			
			if($search_parent_category){ $sql.=" AND parent_category_id='".$search_parent_category."'"; }
			if($search_name){ $sql.=" AND name LIKE '%$search_name%'"; }
			if($search_status){ $sql.=" AND status='".$search_status."'"; }
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $InventoryMasterCategoryQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY CASE WHEN parent_category_id = 0 THEN category_id ELSE parent_category_id END, category_id";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$categories = $InventoryMasterCategoryQuery->gets($sql);
			
			$data['category'] = array();
			
			foreach($categories as $cat)
			{
				$data['category'][] = array(
										'category_id' => $cat['category_id'],
										'parent_category_id' => $cat['parent_category_id'],
										'name' => $cat['name'],
										'status' => $defCls->getMasterStatus($cat['status']),
										'updateURL' => $defCls->genURL('inventory/master_category/edit/'.$cat['category_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($categories).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'inventory/master_category_table.php';
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
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."inventory/master_category/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['parent_category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0");
			
			if(isset($_REQUEST['parent_category_id'])){ $data['parent_category_id'] = $db->request('parent_category_id');}
			else{ $data['parent_category_id'] = 0; }
				
			if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
			else{ $data['name'] = ''; }
			
			if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
			else{ $data['status'] = 0; }
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				$countCategoriesByName = $InventoryMasterCategoryQuery->gets("WHERE name='".$data['name']."'");
				$countCategoriesByName = count($countCategoriesByName);
				
				if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
				if($countCategoriesByName){ $error_msg[]="The name already exists"; $error_no++; }
				
				if(!$error_no)
				{
					
					$InventoryMasterCategoryQuery->create($data);
					$firewallCls->addLog("Category Created: ".$data['name']);
					
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
	
				$this_required_file = _HTML.'inventory/master_category_form.php';
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
		global $InventoryMasterCategoryQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getCategoryInfo = $InventoryMasterCategoryQuery->get($id);
			
			if($getCategoryInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."inventory/master_category/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['parent_category_list'] = $InventoryMasterCategoryQuery->gets("WHERE parent_category_id=0");
				
				$data['category_id'] = $getCategoryInfo['category_id'];
				
				if(isset($_REQUEST['parent_category_id'])){ $data['parent_category_id'] = $db->request('parent_category_id');}
				else{ $data['parent_category_id'] = $getCategoryInfo['parent_category_id']; }
					
				if(isset($_REQUEST['name'])){ $data['name'] = $db->request('name');}
				else{ $data['name'] = $getCategoryInfo['name']; }
				
				if(isset($_REQUEST['status'])){ $data['status'] = $db->request('status');}
				else{ $data['status'] = $getCategoryInfo['status']; }
				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(strlen($data['name'])<3){ $error_msg[]="Name must be minimum 3 letters"; $error_no++; }
					
					if(isset($_REQUEST['name')!==$getCategoryInfo['name'])
					{
						$countCategoriesByName = $InventoryMasterCategoryQuery->gets("WHERE name='".$data['name']."'");
						$countCategoriesByName = count($countCategoriesByName);
						
						if($countCategoriesByName){ $error_msg[]="The name already exists"; $error_no++; }
					}
					
					if(!$error_no)
					{
						
						$InventoryMasterCategoryQuery->edit($data);
						$firewallCls->addLog("Category Updated: ".$data['name']);
						
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
		
					$this_required_file = _HTML.'inventory/master_category_form.php';
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
				$error_msg[]="Invalid category Id"; $error_no++;
					
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
