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
		if (empty($inputs['category_type_id'])){
			$errors.="Select a category type. <br/>";
		}
		if (empty($inputs['name'])){
			$errors.="Enter a category name. <br/>";
		}



		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_categories.php");
			}
			else{
				redirect("frm_categories.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO categories(name,category_type_id) VALUES(:name,:category_type_id)",$inputs);
			}
			else{
				//Update
				
				$con->myQuery("UPDATE categories SET name=:name,category_type_id=:category_type_id WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("categories.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>