<?php
require_once("../support/config.php"); 
if(!isLoggedIn()){
		toLogin();
		die();
	}
if(!empty($_GET['d_id'])){
	$users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0 AND department_id=?",array($_GET['d_id']))->fetchAll(PDO::FETCH_ASSOC);
}
else{
	$users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
}
echo makeOptions($users);
?>