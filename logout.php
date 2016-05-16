<?php
	require_once 'support/config.php';
	if(isLoggedIn()){
	$con->myQuery("UPDATE users SET is_login=0 WHERE id=?",array($_SESSION[WEBAPP]['user']['id']));
	session_destroy();

	}
	redirect('frmlogin.php');
?>