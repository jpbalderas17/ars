<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!AllowUser(array(1))){
		redirect("index.php");
	}

	if(!empty($_POST)){
		try {
			$inputs=$_POST;
			$return_page=!empty($_POST['return_page'])?$_POST['return_page']:'index.php';
			unset($inputs['return_page']);
			$con->myQuery("UPDATE reimbursements SET status='Approved' WHERE id=?",array($inputs['id']));
			record_movement($inputs['id'],"Approved");
			#email

			$user_id=$con->myQuery("SELECT user_id FROM reimbursements WHERE id=?",array($inputs['id']))->fetchColumn();
			$requestor=get_user_details($user_id);
			$doer=get_user_details($_SESSION[WEBAPP]['user']['id']);

			$email_settings=getEmailSettings();
            $header="You Request has been Approved";
            $current_date=new DateTime();
            $claim_date=GetClaimDate($current_date->format("Y-m-d"));
            $message="Hi {$requestor['first_name']},<br/> Your request has been approved by {$doer['last_name']} {$doer['first_name']}. You can claim this reimbursement on {$claim_date}. For more details please login to the Spark Global Tech Systems Inc Automated Reimbursement System.";
            $message=email_template($header,$message);
            emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array('johnpaul.balderas@sparkglobaltech.com')),"Approved Reimbursement Request",$message,$email_settings['host'],$email_settings['port']);

			Alert("Request Approved","success");
			redirect($return_page);
			die;
		} catch (Exception $e) {
			Alert("An Error Occured. Please try again.","danger");
			var_dump($e->getMessage());
			die;
			redirect($return_page);
		}
		

	}
	// var_dump($_POST);
	// die;
	redirect('index.php');
?>