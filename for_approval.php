<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}
	// var_dump($_POST);
	// die;
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
			$con->myQuery("UPDATE reimbursements SET status='For Approval',expense_classification_id=?,tax_type_id=? WHERE id=?",array($inputs['expense_type_id'],$inputs['tax_type_id'],$inputs['id']));
			// die;
			record_movement($inputs['id'],"Audited");
			#email
			$user_id=$con->myQuery("SELECT user_id FROM reimbursements WHERE id=?",array($inputs['id']))->fetchColumn();
			$requestor=get_user_details($user_id);
			$doer=get_user_details($_SESSION[WEBAPP]['user']['id']);

			$email_settings=getEmailSettings();

			$receivers=$con->myQuery("SELECT first_name,middle_name,last_name,email FROM users WHERE user_type_id=1 AND is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

			$header="A New Request is For Approval";

			foreach ($receivers as $receiver) {
	            $message="Hi {$receiver['first_name']},<br/> A new request is for approval. For more details please login to the Spark Global Tech Systems Inc Automated Reimbursement System.";
	            $message=email_template($header,$message);

	            emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array('johnpaul.balderas@sparkglobaltech.com')),"New Request For Approval",$message,$email_settings['host'],$email_settings['port']);
			}

            $header="You Request has been Audited";
            $message="Hi {$requestor['first_name']},<br/> Your request has been audited by {$doer['last_name']} {$doer['first_name']} and is currently for approval. For more details please login to the Spark Global Tech Systems Inc Automated Reimbursement System.";

            $message=email_template($header,$message);

            emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array('johnpaul.balderas@sparkglobaltech.com')),"Your Request has been Audited",$message,$email_settings['host'],$email_settings['port']);

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