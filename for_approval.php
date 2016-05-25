<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}
	if(!empty($_POST)){
		try {
			$inputs=$_POST;
			unset($inputs['dataTables_length']);
			// var_dump($inputs);
			// die();
			$for_audit=$con->myQuery("SELECT id FROM reimbursements WHERE status='For Audit' AND id=?",array($inputs['id']))->fetchColumn();
			if(empty($for_audit)){
				Alert("Invalid request selected.","danger");
				redirect("reimbursements_audit.php");
				die;
			}
			$con->myQuery("UPDATE reimbursements SET status='For Approval' WHERE id=?",array($inputs['id']));
			record_movement($inputs['id'],"Audited");
			#email

			Alert("Request Audited","success");
			redirect("reimbursements_audit.php");
			die;
		} catch (Exception $e) {
			Alert("An Error Occured. Please try again.","danger");
			redirect("reimbursements_audit.php");
			die;
		}
		

	}
	// var_dump($_POST);
	// die;
	redirect('index.php');
?>