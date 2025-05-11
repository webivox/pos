<?php

class SuppliersTransactionDebitnotesConnector {

    public function index() {
		
		global $defCls;
		global $sessionCls;
		global $firewallCls;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionDebitnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data = [];
			
			$data['titleTag'] 	= 'Supplier Debit Notes | '.$defCls->master('companyName');
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['create_url'] 	= $defCls->genURL("suppliers/transaction_debitnotes/create");
			$data['load_table_url'] = $defCls->genURL('suppliers/transaction_debitnotes/load');
			
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			require_once _HTML."common/header.php";
			require_once _HTML."common/menu.php";
			require_once _HTML."suppliers/transaction_debitnotes.php";
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionDebitnotesQuery;
		
		
		$data = [];
		
		if($firewallCls->verifyUser())
		{
			////////////////
			
			if(isset($_REQUEST['search_no'])){
				$search_no=$db->request('search_no');
				$search_no=str_replace('SDN-','',$search_no);
				$search_no=ltrim($search_no,'SDN-');
			}
			else{ $search_no=''; }
			
			if(isset($_REQUEST['search_date_from'])){ $search_date_from=$db->request('search_date_from'); }
			else{ $search_date_from=''; }
			
			if(isset($_REQUEST['search_date_to'])){ $search_date_to=$db->request('search_date_to'); }
			else{ $search_date_to=''; }
			
			if(isset($_REQUEST['search_supplier_id'])){ $search_supplier_id=$db->request('search_supplier_id'); }
			else{ $search_supplier_id=''; }
			
			if($pageno=$db->request('pageno')){ $pageno=$db->request('pageno'); }
			else{ $pageno = 1; }
			/////////////
			
			$sql=" WHERE supplier_id!=0";
			
			if($search_no){ $sql.=" AND debit_note_id='".$search_no."'"; }
			if($search_date_from){ $sql.=" AND added_date>='".$dateCls->dateToDB($search_date_from)."'"; }
			if($search_date_to){ $sql.=" AND added_date<='".$dateCls->dateToDB($search_date_to)."'"; }
			if($search_supplier_id){ $sql.=" AND supplier_id='".$search_supplier_id."'"; }
			
			///////////
	
			$per_page=$defCls->master('per_page_results');
			$pagination = $SuppliersTransactionDebitnotesQuery->getPagination($sql,$pageno);
			
			$sql.="  ORDER BY debit_note_id DESC";
			$sql.=" LIMIT ".$per_page." OFFSET ".$pagination['limit_start'];
			
			$debitnotes = $SuppliersTransactionDebitnotesQuery->gets($sql);
			
			$data['debitnotes'] = array();
			
			foreach($debitnotes as $cat)
			{
				$data['debitnotes'][] = array(
										'debit_note_id' => $cat['debit_note_id'],
										'debit_note_no' => $defCls->docNo('SDN-',$cat['debit_note_id']),
										'added_date' => $dateCls->showDate($cat['added_date']),
										'supplier_id' => $SuppliersMasterSuppliersQuery->data($cat['supplier_id'],'name'),
										'details' => $defCls->showText($cat['details']),
										'amount' => $defCls->money($cat['amount']),
										'updateURL' => $defCls->genURL('suppliers/transaction_debitnotes/edit/'.$cat['debit_note_id']),
										'printURL' => $defCls->genURL('suppliers/transaction_debitnotes/printView/'.$cat['debit_note_id'])
											);
			}
			
			$data['showing_text'] = 'Showing '.$pagination['limit_start'].' to '.count($debitnotes).' of '.$pagination['total'].' entries';
			$data['pagination_html'] = $pagination['html'];
	
			$this_required_file = _HTML.'suppliers/transaction_debitnotes_table.php';
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionDebitnotesQuery;
		global $SystemMasterLocationsQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			
			$data['companyName'] 	= $defCls->master('companyName');
			$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
			$data['form_url'] 	= _SERVER."suppliers/transaction_debitnotes/create";
			
			$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
			
			$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
			$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
			
			$data['debit_note_no'] = 'New';
			
			if(isset($_REQUEST['supplier_id']))
			{
				$data['supplier_id'] = $db->request('supplier_id');
				$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
				$data['outstanding'] = $defCls->num($closing_balance);
			}
			else{ $data['supplier_id'] = ''; $data['outstanding'] = '0.00'; }
			
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
				if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
				if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
				if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
				if($data['amount']>$data['outstanding']){ $error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++; }
				if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
				
				
				if(!$error_no)
				{
					
					$createdId = $SuppliersTransactionDebitnotesQuery->create($data);
					
					$transaction_no = $defCls->docNo('SDN-',$createdId);
					$firewallCls->addLog("Supplier Debit note Created: ".$transaction_no);
					
					$debitNoteInfo = $SuppliersTransactionDebitnotesQuery->get($createdId);
					
					
					////Supplier transactipn update
					$supplierData = [];
					$supplierData['added_date'] = $debitNoteInfo['added_date'];
					$supplierData['supplier_id'] = $data['supplier_id'];
					$supplierData['reference_id'] = $createdId;
					$supplierData['transaction_type'] = 'SDN';
					$supplierData['debit'] = 0;
					$supplierData['credit'] = $debitNoteInfo['amount'];
					$supplierData['remarks'] = $transaction_no;
					
					$SuppliersMasterSuppliersQuery->transactionAdd($supplierData);
					
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
	
				$this_required_file = _HTML.'suppliers/transaction_debitnotes_form.php';
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SystemMasterLocationsQuery;
		global $SuppliersTransactionDebitnotesQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$getDebitNoteInfo = $SuppliersTransactionDebitnotesQuery->get($id);
			
			if($getDebitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
				
				$data['form_url'] 	= _SERVER."suppliers/transaction_debitnotes/edit/".$id;
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				$data['debit_note_id'] = $getDebitNoteInfo['debit_note_id'];
			
				$data['location_list'] = $SystemMasterLocationsQuery->gets("ORDER BY name ASC");
				$data['supplier_list'] = $SuppliersMasterSuppliersQuery->gets("ORDER BY name ASC");
					
				$data['debit_note_no'] = $defCls->docNo('SDN-',$getDebitNoteInfo['debit_note_id']);
				
				if(isset($_REQUEST['location_id'])){ $data['location_id'] = $db->request('location_id'); }
				else{ $data['location_id'] = $getDebitNoteInfo['location_id']; }
				
				if(isset($_REQUEST['supplier_id']))
				{
					$data['supplier_id'] = $db->request('supplier_id');
					$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				else
				{
					$data['supplier_id'] = $getDebitNoteInfo['supplier_id'];
					$closing_balance = $SuppliersMasterSuppliersQuery->data($data['supplier_id'],'closing_balance');
					$data['outstanding'] = $defCls->num($closing_balance);
				}
				
				if(isset($_REQUEST['added_date'])){ $data['added_date'] = $db->request('added_date'); }
				else{ $data['added_date'] = $dateCls->showDate($getDebitNoteInfo['added_date']); }
				
				if(isset($_REQUEST['amount'])){ $data['amount'] = $db->request('amount'); }
				else{ $data['amount'] = $defCls->num($getDebitNoteInfo['amount']); }
				
				if(isset($_REQUEST['details'])){ $data['details'] = $db->request('details'); }
				else{ $data['details'] = $getDebitNoteInfo['details']; }
				

				
				if(($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					
					if(!$SystemMasterLocationsQuery->has($data['location_id'])){ $error_msg[]="You must choose a location"; $error_no++; }
					if(!$SuppliersMasterSuppliersQuery->has($data['supplier_id'])){ $error_msg[]="You must choose a supplier"; $error_no++; }
					if(!$data['added_date']){ $error_msg[]="You must enter added date"; $error_no++; }
					if(!$data['amount']){ $error_msg[]="You must enter amount"; $error_no++; }
					if($data['amount']>$data['outstanding']+$getDebitNoteInfo['amount'])
					{
						$error_msg[]="You can't enter an amount higher than the outstanding amount!"; $error_no++;
					}
					
					if(strlen($data['details'])<5){ $error_msg[]="You must enter details (min 5)"; $error_no++; }
					
						
					if(!$error_no)
					{
						
						$SuppliersTransactionDebitnotesQuery->edit($data);
						$transaction_no = $defCls->docNo('SDN-',$id);
						$firewallCls->addLog("Supplier Debit Note Updated: ".$transaction_no);
						
						
						$debitNoteInfo = $SuppliersTransactionDebitnotesQuery->get($id);
						
						////Supplier transactipn update
						$supplierData = [];
						$supplierData['added_date'] = $debitNoteInfo['added_date'];
						$supplierData['supplier_id'] = $debitNoteInfo['supplier_id'];
						$supplierData['reference_id'] = $debitNoteInfo['debit_note_id'];
						$supplierData['transaction_type'] = 'SDN';
						$supplierData['debit'] = 0;
						$supplierData['credit'] = $debitNoteInfo['amount'];
						$supplierData['remarks'] = $transaction_no;
						
						$SuppliersMasterSuppliersQuery->transactionEdit($supplierData);
						
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
		
					$this_required_file = _HTML.'suppliers/transaction_debitnotes_form.php';
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
				$error_msg[]="Invalid debitnote Id"; $error_no++;
					
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
		global $SuppliersMasterSuppliersQuery;
		global $SystemMasterUsersQuery;
		global $SuppliersTransactionDebitnotesQuery;
		global $SystemMasterLocationsQuery;
		global $AccountsMasterAccountsQuery;
		global $accountsls;
		global $AccountsTransactionChequeQuery;
		
		
		$data = [];
		$error_no = 0;
		$error_msg = [];
		
		if($firewallCls->verifyUser())
		{
			$debitNoteInfo = $SuppliersTransactionDebitnotesQuery->get($id);
			
			if($debitNoteInfo)
			{
			
				$data['companyName'] 	= $defCls->master('companyName');
				$data['logo'] 			= _UPLOADS.$defCls->master('logo');
			
				$data['title_tag'] = 'Supplier Debit Note Print | '.$dateCls->todayDate('d-m-Y H:i:s').' | '.$data['companyName'];
				
				$userInfo = $SystemMasterUsersQuery->get($sessionCls->load('signedUserId'));
				
				
				$data['print_by_n_date'] = 'Print By: '.$SystemMasterUsersQuery->data($sessionCls->load('signedUserId'),'name').' | Printed On: '.$dateCls->todayDate('d-m-Y H:i:s');
				
				$data['debit_note_id'] = $debitNoteInfo['debit_note_id'];
					
				$data['debit_note_no'] = $defCls->docNo('SDN-',$debitNoteInfo['debit_note_id']);
				
				$data['added_date'] = $dateCls->showDate($debitNoteInfo['added_date']);
				
				$data['location_id'] = $SystemMasterLocationsQuery->data($debitNoteInfo['location_id'],'name');
				
				$data['supplier_id'] = $SuppliersMasterSuppliersQuery->data($debitNoteInfo['supplier_id'],'name');
				
				$data['amount'] = $defCls->money($debitNoteInfo['amount']);
				
				$data['details'] = $defCls->showText($debitNoteInfo['details']);
				
				$data['user'] = $SystemMasterUsersQuery->data($debitNoteInfo['user_id'],'name');

				$this_required_file = _HTML.'suppliers/transaction_debitnotes_print.php';
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
				$error_msg[]="Invalid debit_note Id"; $error_no++;
					
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
