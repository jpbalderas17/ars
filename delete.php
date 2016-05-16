<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if(empty($_GET['id']) || empty($_GET['t'])){
		redirect('index.php');
		die;
	}
	else
	{

		$table="";
		switch ($_GET['t']) {
			case 'u':
				$table="users";
				$page="user.php";
				break;
			case 'dep':
				$table="departments";
				$page="departments.php";
				break;
			default:
				redirect("index.php");
				break;
		}
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>