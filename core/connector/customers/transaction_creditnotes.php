<?php

class CustomersTransactionCreditnotesConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsCreditnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Customer Credit Notes | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("customers/transaction_creditnotes/create");
			$data['load_table_url'] = $defCls->genURL('customers/transaction_creditnotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."customers/transaction_creditnotes.php";
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
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsCreditnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('CCN-','',$search_no);
				$search_no=ltrim($search_no,'CCN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_customer_id'])){ $search_customer_id=$db->request('search_customer_id'); }
			else{ $search_customer_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE customer_id!=0";
			
			if($search_no){ $sql.=" AND credit_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_customer_id){ $sql.=" AND customer_id='".$search_customer_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $CustomersTransactionsCreditnotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY credit_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$creditnotes = $CustomersTransactionsCreditnotesQuery->gets($sql);
			
			$data['creditnotes'] = array();
			
			foreach($creditnotes as $cat)
			{
				$data['creditnotes'][] = array(
										'credit_note_id' => $cat['credit_note_id'],
										'credit_note_no' => $defCls->docNo('CCN-',$cat['credit_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'customer_id' => $CustomersMasterCustomersQuery->data($cat['customer_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('customers/transaction_creditnotes/edit/'.$cat['credit_note_id']),
										'printURL' => $defCls->genURL('customers/transaction_creditnotes/printView/'.$cat['credit_note_id']),
										'deleteURL' => $defCls->genURL('customers/transaction_creditnotes/delete/'.$cat['credit_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($creditnotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'customers/transaction_creditnotes_table.php';
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
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsCreditnotesQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."customers/transaction_creditnotes/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
			
			$data['credit_note_no'] = 'New';
			
			if(isset($_REQUEST['customer_id']))
			{
				$data['customer_id'] = $db->request('customer_id');
				$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
				$data['outstanding'] = $defCls->num($closing_balance);
			}
			else{ $data['customer_id'] = ''; $data['outstanding'] = '0.00'; }
			
			if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
			else{ $data['location_id'] = ''; }
			
			if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
			else{ $data['added_date'] = $dateCls->todayDate('d-m-Y'); }
			
			if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
			else{ $data['amount'] = 0; }
			
			if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
			else{ $data['details'] = ''; }

			
			$data['user_id'] = $userInfo['user_id'];
			
			
			if(($_SERVER['REQUEST_METHOD'] == 'POST'))
			{
				
				if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
				if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if($data['amount']>$data['outstanding']){ $error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $CustomersTransactionsCreditnotesQuery->create($data);
					
					$transaction_no = $defCls->docNo('CCN-',$createdId);
					$firewallCls->addLog("Customer Credit note Created: ".$transaction_no);
					
					$creditNoteInfo = $CustomersTransactionsCreditnotesQuery->get($createdId);
					
					
					////Customer transactipn update
					$customerData = [];
					$customerData['added_date'] = $creditNoteInfo['added_date'];
					$customerData['customer_id'] = $data['customer_id'];
					$customerData['reference_id'] = $createdId;
					$customerData['transaction_type'] = 'CCN';
					$customerData['debit'] = 0;
					$customerData['credit'] = $creditNoteInfo['amount'];
					$customerData['remarks'] = $transaction_no;
					
					$CustomersMasterCustomersQuery->transactionAdd($customerData);
					
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
	
				$this_required_file = _HTML.'customers/transaction_creditnotes_form.php';
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
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsCreditnotesQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getCreditNoteInfo = $CustomersTransactionsCreditnotesQuery->get($id);
			
			if($getCreditNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."customers/transaction_creditnotes/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['credit_note_id'] = $getCreditNoteInfo['credit_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['customer_list'] = $CustomersMasterCustomersQuery->gets("ORDER BY name ASC");
					
				$data['credit_note_no'] = $defCls->docNo('CCN-',$getCreditNoteInfo['credit_note_id']);
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getCreditNoteInfo['location_id']; }
				
				if(isset($_REQUEST['customer_id']))
				{
					$data['customer_id'] = $db->request('customer_id');
					$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				else
				{
					$data['customer_id'] = $getCreditNoteInfo['customer_id'];
					$closing_balance = $CustomersMasterCustomersQuery->data($data['customer_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getCreditNoteInfo['added_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($getCreditNoteInfo['amount']); }
				
				if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $getCreditNoteInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$CustomersMasterCustomersQuery->has($data['customer_id'])){ $error_msg[]="You must choose a customer"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if($data['amount']>$data['outstanding']+$getCreditNoteInfo['amount'])
					{
						$error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++;
					}
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$CustomersTransactionsCreditnotesQuery->edit($data);
						$transaction_no = $defCls->docNo('CCN-',$id);
						$firewallCls->addLog("Customer Credit Note Updated: ".$transaction_no);
						
						
						$creditNoteInfo = $CustomersTransactionsCreditnotesQuery->get($id);
						
						////Customer transactipn update
						$customerData = [];
						$customerData['added_date'] = $creditNoteInfo['added_date'];
						$customerData['customer_id'] = $creditNoteInfo['customer_id'];
						$customerData['reference_id'] = $creditNoteInfo['credit_note_id'];
						$customerData['transaction_type'] = 'CCN';
						$customerData['debit'] = 0;
						$customerData['credit'] = $creditNoteInfo['amount'];
						$customerData['remarks'] = $transaction_no;
						
						$CustomersMasterCustomersQuery->transactionEdit($customerData);
						
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
		
					$this_required_file = _HTML.'customers/transaction_creditnotes_form.php';
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
				$error_msg[]="Invalid creditnote Id"; $error_no++;
					
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
	
	
	
	

    public function printView() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $db;
		global $id;
		global $dateCls;
		global $CustomersMasterCustomersQuery;
		global $SystemMasterUsersQuery;
		global $CustomersTransactionsCreditnotesQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$creditNoteInfo = $CustomersTransactionsCreditnotesQuery->get($id);
			
			if($creditNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Customer Credit Note Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['credit_note_id'] = $creditNoteInfo['credit_note_id'];
					
				$data['credit_note_no'] = $defCls->docNo('CCN-',$creditNoteInfo['credit_note_id']);
				
				$data['added_date'] = $dateCls->showDate($creditNoteInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($creditNoteInfo['location_id'],'name');
				
				$data['customer_id'] = $CustomersMasterCustomersQuery->data($creditNoteInfo['customer_id'],'name');
				
				$data['amount'] = $defCls->money($creditNoteInfo['amount']);
				
				$data['details'] = $defCls->showText($creditNoteInfo['details']);
				
				$data['user'] = $SystemMasterUsersQuery->data($creditNoteInfo['user_id'],'name');

				$this_required_file = _HTML.'customers/transaction_creditnotes_print.php';
				if (!file_exists($this_required_file)) {
					error_log("File not found: ".$this_required_file);
					die('File not found:'.$this_required_file);
				}
				else {
	
					require_once($this_required_file);
					
				}
			}
			else
			{
				$error_msg[]="Invalid credit_note Id"; $error_no++;
					
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
		global $CustomersTransactionsCreditnotesQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getInfo = $CustomersTransactionsCreditnotesQuery->get($id);
			
			if($getInfo)
			{
				$doNo = $defCls->docNo('CCN-',$getInfo['credit_note_id']);;
				
				$deleteValue = $CustomersTransactionsCreditnotesQuery->delete($id);
				
				if($deleteValue=='deleted')
				{
					$firewallCls->addLog("Customer credit note Deleted: ".$doNo);
				
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
					$error_msg[]="An error occurred while attempting to delete the customer credit note!"; $error_no++;
				}	
			}
			else
			{
				$error_msg[]="Invalid customer credit note Id"; $error_no++;
				
				
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
