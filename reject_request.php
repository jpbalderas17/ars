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
			$con->myQuery("UPDATE reimbursements SET status='Rejected' WHERE id=?",array($inputs['id']));
			record_movement($inputs['id'],"Rejected",$inputs['reason']);
			#email

			Alert("Request Rejected","success");
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