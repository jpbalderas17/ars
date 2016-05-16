<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	// var_dump($_POST);
	// var_dump($_FILES);
if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if(!empty($_POST)){
		if(empty($_POST['asset_id'])){
			redirect("index.php");
			die();
		}

		if(empty($_FILES['file']['name'])){
			Alert("No file selected.","danger");
			redirect("view_assets.php?id=".urlencode($_POST['asset_id']));
			die();
		}
		$inputs['image']=$_FILES['file']['name'];
		$inputs['notes']=$_POST['notes'];
		$inputs['item_id']=$_POST['asset_id'];
		$inputs['user_id']=$_SESSION[WEBAPP]['user']['id'];

		
		$con->myQuery("INSERT INTO files(image,notes,category_types,item_id,user_id,date_added) VALUES(:image,:notes,1,:item_id,:user_id,NOW())",$inputs);
		$file_id=$con->lastInsertId();
		$filename=$file_id.getFileExtension($_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],"uploads/".$filename);
		$con->myQuery("UPDATE files SET file_name=? WHERE id=?",array($filename,$file_id));
		Alert("File Added","success");
		redirect("view_asset.php?id=".$inputs['item_id']);
		die();
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>