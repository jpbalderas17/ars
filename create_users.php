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
		//Validate form inputs
		$inputs=$_POST;
		$inputs=array_map('trim', $inputs);
		$inputs['contact_no']=str_replace("_", "", $inputs['contact_no']);
		$errors="";

		if (empty($inputs['user_type_id'])){
			$errors.="Select user type. <br/>";
		}
		if (empty($inputs['first_name'])){
			$errors.="Enter first name. <br/>";
		}
		if (empty($inputs['middle_name'])){
			$errors.="Enter middle name. <br/>";
		}
		if (empty($inputs['last_name'])){
			$errors.="Enter last name. <br/>";
		}
		if (empty($inputs['username'])){
			$errors.="Enter username. <br/>";
		}
		if (empty($inputs['password'])){
			$errors.="Enter password. <br/>";	
		}
		else{
			if($inputs['password']<>$inputs['confirm_password']){
				$errors="Password and confirm password does not match<br/>";
			}

			// die;
			$password_regex="/^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/";
			preg_match($password_regex, $inputs['password'], $is_valid, PREG_OFFSET_CAPTURE);
			if(!empty($is_valid)){
				$errors.="Password should contain the ff:<br/>";
				$errors.="One Integer<br>";
				$errors.="One character<br>";
				$errors.="One Uppercase character<br>";
				$errors.="One Special Character<br>";
				
			}
			unset($is_valid);
			// var_dump($is_valid);
		}
		// die;
		if (empty($inputs['email'])){
			$errors.="Enter email address. <br/>";
		}
		else{
			if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
			    // invalid emailaddress
				$errors.="Invalid email adress.<br/>";
				// die('error');
			}
		}
		// die;
		if (empty($inputs['employee_no'])){
			$errors.="Enter employee number. <br/>";
		}

		if (empty($inputs['title'])){
			$errors.="Enter position. <br/>";
		}

		if (empty($inputs['contact_no'])){
			$errors.="Enter contact no. <br/>";
		}
		else{
			if(strlen($inputs['contact_no'])!=11){
				$errors.="Invalid contact no.<br/>";
			}
		}

		if (empty($inputs['department_id'])){
			$errors.="Select Department. <br/>";
		}

		$uname=$con->myQuery("SELECT id,lcase(username) FROM users WHERE is_deleted=0 and username=?",array(strtolower($inputs['username'])))->fetch(PDO::FETCH_ASSOC);

		if(!empty($uname)){
			if(empty($inputs['id'])){
				$errors.="Entered Username is not available.";
			}
			elseif(!empty($inputs['id']) && $uname['id']<>$inputs['id']){
				$errors.="Entered Username is not available.";
			}
		}

		if($errors!=""){
			$inputs['password']=encryptIt($inputs['password']);
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_users.php");
			}
			else{
				redirect("frm_users.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			try {
				$inputs['password']=encryptIt($inputs['password']);
				unset($inputs['confirm_password']);
				if(empty($inputs['id'])){
					//Insert
					// $inputs=$_POST;
					unset($inputs['id']);
					// var_dump($inputs);
					//$inputs['name']=$_POST['name'];
					$con->myQuery("INSERT INTO users(user_type_id,first_name,middle_name,last_name,username, password,email,employee_no,contact_no,title,department_id) VALUES(:user_type_id,:first_name,:middle_name,:last_name,:username,:password, :email,:employee_no,:contact_no,:title,:department_id)",$inputs);
					//Alert("Save succesful","success");
					$message="Save succesful";
				}
				else{				
					//Update
					
					$con->myQuery("UPDATE users SET user_type_id=:user_type_id,first_name=:first_name,middle_name=:middle_name,last_name=:last_name,username=:username,password=:password,email=:email,employee_no=:employee_no,contact_no=:contact_no,title=:title,department_id=:department_id WHERE id=:id",$inputs);
					//Alert("Update succesful","success");
					$message="Update succesful";
				}
				Alert($message,"success");
			} catch (Exception $e) {
				// var_dump($e);
				die;
			}
			
			// die;
			redirect("user.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>