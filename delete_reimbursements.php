<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST)){
		try {
			$inputs=$_POST;
			$return_page=!empty($_POST['return_page'])?$_POST['return_page']:'index.php';
			unset($inputs['return_page']);
			$user_id=$con->myQuery("SELECT user_id FROM reimbursements WHERE id=? AND is_deleted=0",array($inputs['id']))->fetchColumn();
			if($user_id<>$_SESSION[WEBAPP]['user']['id']){
				Modal("Invalid reimbursements");
				redirect($return_page);
				die();
			}else{
				$con->myQuery("UPDATE reimbursements SET is_deleted=1 WHERE id=?",array($inputs['id']));
				Alert("Save Successful","success");
				redirect($return_page);
				die();
			}
			die;
		} catch (Exception $e) {
			Alert("An Error Occured. Please try again.","danger");
			redirect($return_page);
			die;
		}
		

	}

	redirect('index.php');
?>