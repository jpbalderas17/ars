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
			$return_page=!empty($_POST['return_page'])?$_POST['return_page']:'index.php';
			unset($inputs['return_page']);
			$con->myQuery("UPDATE reimbursements SET status='Returned' WHERE id=?",array($inputs['id']));
			record_movement($inputs['id'],"Returned To Sender",$inputs['reason']);
			#email

			Alert("Request Returned","success");
			redirect($return_page);
			die;
		} catch (Exception $e) {
			Alert("An Error Occured. Please try again.","danger");
			redirect($return_page);
			die;
		}
		

	}
	// var_dump($_POST);
	// die;
	redirect('index.php');
?>