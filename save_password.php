<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    //if(!AllowUser(array(2))){
    //    redirect("index.php");
    //}

// var_dump($_POST);
// die;

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$inputs=array_map('trim', $inputs);

		$employee_user=$con->myQuery("SELECT password FROM users WHERE is_deleted=0 and id=?",array($inputs['emp_id']));
		//$uname=$con->myQuery("SELECT id,lcase(username) FROM users WHERE is_deleted=0 and username=?",array(strtolower($inputs['username'])));

		$errors="";

		if($inputs['password']!=$inputs['con_password']){
			$errors.="Reenter password.";
		}

		if (empty($inputs['password'])){
			$errors.="Enter password. <br/>";	
		}
		else{
			$password_regex="/^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/";
			preg_match($password_regex, $inputs['password'], $is_valid, PREG_OFFSET_CAPTURE);
			if(!empty($is_valid)){
				$errors.="Password should contain the ff:<br/>";
				$errors.="One Integer<br>";
				$errors.="One character<br>";
				$errors.="One Uppercase character<br>";
				$errors.="One Special Character<br>";
			}
			// var_dump($is_valid);
		}
		
		if($errors!="")
		{
			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("frm_change_pass.php");
			die;
		}
		else{
			unset($inputs['con_password']);
			$inputs['password']=encryptIt($inputs['password']);
			//var_dump($inputs);
			//die();
			$con->myQuery("UPDATE users SET password=? WHERE id=? ",array($inputs['password'],$_SESSION[WEBAPP]['user']['id']));

			// die;
			Alert("Save succesful","success");
			redirect("frm_change_pass.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>