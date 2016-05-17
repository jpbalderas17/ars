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
			$required_fieds=array(
			"email_username"=>"Enter Username. <br/>",
			"email_password"=>"Enter Password. <br/>",
			"email_host"=>"Enter Host. <br/>",
			"email_port"=>"Enter Port"
			);
		
			$errors="";

			foreach ($required_fieds as $key => $value) {
				if(empty($inputs[$key])){
					$errors.=$value;
				}else{
					#CUSTOM VALIDATION
				}
			}

			if($errors!=""){
				Alert("You have the following errors: <br/>".$errors,"danger");
				redirect("settings.php");
				die;
			}
			else{
				$inputs['email_password']=encryptIt($inputs['email_password']);
				Alert("Update successful.","success");
				$con->myQuery("UPDATE settings SET default_password=:default_password,email_username=:email_username,email_password=:email_password,email_host=:email_host,email_port=:email_port",$inputs);
				redirect("settings.php");
				die;
			}
		}


		redirect("index.php");


?>