<?php
	require_once 'support/config.php';

	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!empty($_POST))
	if(empty($_GET['from'])){
		$redirect="index.php";
		$error_redirect="index.php";
	}
	else{
		switch ($_GET['from']) {
			case 'login':
				$redirect='index.php';
				$error_redirect="first_login.php";
				break;
			case 'form':
				$redirect='change_secret_password.php';
				$error_redirect="change_secret_password.php";
				break;
		}
		$inputs=$_POST;
		$inputs=array_map('trim', $inputs);

		$errors="";

		if (empty($inputs['password_question'])){
			$errors.="Enter secret question. <br/>";
		}

		if (empty($inputs['password_answer'])){
			$errors.="Enter answer. <br/>";
		}

		if($errors!=""){
			
			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect($error_redirect);
			die;
		}
		else{
			$inputs['id']=$_SESSION[WEBAPP]['user']['id'];
			$con->myQuery("UPDATE users SET password_answer=:password_answer,password_question=:password_question WHERE id=:id",$inputs);
			Alert("Secret question and answer saved.","success");
			redirect($redirect);
		}

		die;
	}
?>