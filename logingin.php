<?php
	require_once 'support/config.php';
	// var_dump($_POST);
	// die;
	if(!empty($_POST)){

		$user=$con->myQuery("SELECT first_name,middle_name,last_name,id,location_id,user_type_id as user_type,location_id,is_login,is_active,password_question  FROM users WHERE BINARY username=? AND BINARY password=? AND is_deleted=0",array($_POST['username'],encryptIt($_POST['password'])))->fetch(PDO::FETCH_ASSOC);

		if(!empty($_SESSION[WEBAPP]['attempt_no']) && $_SESSION[WEBAPP]['attempt_no']>1){
			Alert("Maximum login attempts achieved. <br/>Your account will be deactivated. </br> Contact your system administrator to retreive your password.","danger");
			UNSET($_SESSION[WEBAPP]['attempt_no']);
			$con->myQuery("UPDATE users SET is_active=0 WHERE username=?",array($_POST['username']));
			redirect("frmlogin.php");
			die;
		}
		if(empty($user)){
			Alert("Invalid Username/Password","danger");
			redirect('frmlogin.php');
			if(!empty($_SESSION[WEBAPP]['attempt_no'])){
				// setcookie("attempt_no",$_SESSION[WEBAPP]['attempt_no']+1,time()+(3600));
				$_SESSION[WEBAPP]['attempt_no']+=1;
			}
			else{
				$_SESSION[WEBAPP]['attempt_no']=1;
			}
		}
		else{
			if($user['is_login']==0){
				if($user['is_active']==1){
					UNSET($_SESSION[WEBAPP]['attempt_no']);
					$con->myQuery("UPDATE users SET is_login=1 WHERE id=?",array($user['id']));
					$user['password_question']=!empty($user['password_question']);
					$_SESSION[WEBAPP]['user']=$user;
					refresh_activity($_SESSION[WEBAPP]['user']['id']);
					// var_dump($user['password_question']);
					// die;
					if(!$user['password_question']){
						redirect("first_login.php");
					}else{
						redirect("index.php");
					}
					die;
				}
				else{
					Alert("This account is currently deactivated.","danger");
					redirect("frmlogin.php");
					die;
				}
				
			}
			else{
				Alert("This account is currently already logged in.","danger");
				redirect("frmlogin.php");
				die;
			}
		}
		die;
	}
	else{
		redirect('frmlogin.php');
		die();
	}
	redirect('frmlogin.php');
?>