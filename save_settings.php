<?php
	require_once 'support/config.php';
		
		if(!isLoggedIn()){
			toLogin();
			die();
		}
	if(!AllowUser(array(1))){
	        redirect("index.php");
	    }

	    // var_dump($_POST);
	    // die;
		if(!empty($_POST)){
			$errors="";
			$inputs=$_POST;
			$inputs=array_map('trim', $inputs);
			if (empty($inputs['default_password'])){
			$errors.="Enter default password. <br/>";	
			}
			else{
				$password_regex="/^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/";
				preg_match($password_regex, $inputs['default_password'], $is_valid, PREG_OFFSET_CAPTURE);
				if(!empty($is_valid)){
					$errors.="Password should contain the ff:<br/>";
					$errors.="One Integer<br>";
					$errors.="One character<br>";
					$errors.="One Uppercase character<br>";
					$errors.="One Special Character<br>";
					

				}
				// var_dump($is_valid);
			}

			if($errors!=""){
				Alert("You have the following errors: <br/>".$errors,"danger");
				redirect("settings.php");
				die;
			}
			else{
				Alert("Update successful.","success");
				$con->myQuery("UPDATE settings SET default_password=?",array($inputs['default_password']));
				redirect("settings.php");
				die;
			}
		}


		redirect("index.php");


?>