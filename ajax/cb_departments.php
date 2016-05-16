<?php
require_once("../support/config.php"); 
if(!isLoggedIn()){
		toLogin();
		die();
	}
$departments=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
//echo json_encode(array('items'=>$departments));
echo json_encode($departments);
?>
