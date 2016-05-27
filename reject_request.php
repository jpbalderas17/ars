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

			$requestor=get_user_details($user_id);
			$doer=get_user_details($_SESSION[WEBAPP]['user']['id']);

			$email_settings=getEmailSettings();

            $header="You Request has been Rejected";
            $message="Hi {$requestor['first_name']},<br/> Your request has been rejected by {$doer['last_name']} {$doer['first_name']}. Reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc Automated Reimbursement System.";
            $message=email_template($header,$message);
            emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array('johnpaul.balderas@sparkglobaltech.com')),"Returned Reimbursement Request",$message,$email_settings['host'],$email_settings['port']);

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