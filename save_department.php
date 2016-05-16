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
		//Validate form inputs
		$inputs=$_POST;
		$inputs=array_map('trim', $inputs);
		$errors="";
		if (empty($inputs['name'])){
			$errors.="Enter a department name. <br/>";
		}

		if (empty($inputs['code'])){
			$errors.="Enter a department code. <br/>";
		}
		else{
			if(empty($inputs['id'])){
				$available_code=$con->myQuery("SELECT code FROM departments WHERE code=? AND is_deleted=0",array($inputs['code']))->fetch(PDO::FETCH_ASSOC);
				if(!empty($available_code)){
					$errors.="Department code is not available.";
				}
			}
			else{
				$available_code=$con->myQuery("SELECT code FROM departments WHERE code=? AND id <>? AND is_deleted=0",array($inputs['code'],$inputs['id']))->fetch(PDO::FETCH_ASSOC);
				if(!empty($available_code)){
					$errors.="Department code is not available.";
				}
			}
		}



		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("departments.php");
			}
			else{
				redirect("departments.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO departments(name,code) VALUES(:name,:code)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE departments SET name=:name,code=:code WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("departments.php");
			die();
		}
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>