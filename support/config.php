<?php
	session_start();
	date_default_timezone_set("Asia/Manila");
	define("WEBAPP", 'Automated Reimbursement System');
	//$_SESSION[WEBAPP]=array();
	function __autoload($class)
	{
		require_once 'class.'.$class.'.php';
	}
	function redirect($url)
	{
		header("location:".$url);
	}

	function jsredirect($url)
	{
		echo "<script>window.history.back()</script>";
		echo "<a href='{$url}'>Click here if you are not redirected.</a>";
	}

	function getFileExtension($filename){
		return substr($filename, strrpos($filename,"."));
	}

	function format_date($date_string)
	{
		$date=new DateTime($date_string);
		return $date->format("Y-m-d");
	}
	function inputmask_format_date($date_string){
		$date=new DateTime($date_string);
		return $date->format("m/d/Y");	
	}

	function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}

// ENCRYPTOR
	function encryptIt( $q ) {
	    $cryptKey  = 'JPB0rGtIn5UB1xG03efyCp';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
	}
	function decryptIt( $q ) {
	    $cryptKey  = 'JPB0rGtIn5UB1xG03efyCp';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}
//End Encryptor
/* User FUNCTIONS */
	function isLoggedIn()
	{
		if(empty($_SESSION[WEBAPP]['user']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function toLogin($url=NULL)
	{
		if(empty($url))
		{
			Alert('Please Log in to Continue',"danger");
			header("location: frmlogin.php");
		}
		else{
			header("location: ".$url);
		}
	}
	function Login($user)
	{
		$_SESSION[WEBAPP]['user']=$user;
	}
	function AllowUser($user_type_id){
	if(array_search($_SESSION[WEBAPP]['user']['user_type'], $user_type_id)!==FALSE){
		return true;
	}
	return false;
	}

	function refresh_activity($user_id)
	{
		global $con;
		$con->myQuery("UPDATE users SET last_activity=NOW() WHERE id=?",array($user_id));
	}
	function is_active($user_id)
	{
		global $con;
		$last_activity=$con->myQuery("SELECT last_activity FROM users  WHERE id=?",array($user_id))->fetchColumn();
		$inactive_time=60*3;
		$inactive_time=60*60;

		// echo strtotime($last_activity)."<br/>";
		// echo time();
		if(time()-strtotime($last_activity) > $inactive_time){
			return false;
		}

		return true;
	}

	function user_is_active($user_id)
	{
		global $con;
		$last_activity=$con->myQuery("SELECT is_active FROM users  WHERE id=?",array($user_id))->fetchColumn();
		if(!empty($last_activity)){
			return true;
		}
		else{
			return false;
		}
	}
/* End User FUnctions */
//HTML Helpers
	function makeHead($pageTitle=WEBAPP,$level=0)
	{
		require_once str_repeat('../',$level).'template/head.php';
		unset($pageTitle);
	}
	function makeFoot($level=0)
	{
		require_once 'template/foot.php';
	}

	function makeOptions($array,$placeholder="",$checked_value=NULL){
		$options="";
		// if(!empty($placeholder)){
			$options.="<option value=''>{$placeholder}</option>";
		// }
		foreach ($array as $row) {
			list($value,$display) = array_values($row);
				if($checked_value!=NULL && $checked_value==$value){

					$options.="<option value='".htmlspecialchars($value)."' checked>".htmlspecialchars($display)."</option>";
				}
				else
				{
					$options.="<option value='".htmlspecialchars($value)."'>".htmlspecialchars($display)."</option>";
				}
		}
		return $options;
	}
//END HTML Helpers
/* BOOTSTRAP Helpers */
	function Modal($content=NULL,$title="Alert")
	{
		if(!empty($content))
		{
			$_SESSION[WEBAPP]['Modal']=array("Content"=>$content,"Title"=>$title);
		}
		else
		{
			if(!empty($_SESSION[WEBAPP]['Modal']))
			{
				include_once 'template/modal.php';
				unset($_SESSION[WEBAPP]['Modal']);
			}
		}
	}
	function Alert($content=NULL,$type="info")
	{
		if(!empty($content))
		{
			$_SESSION[WEBAPP]['Alert']=array("Content"=>$content,"Type"=>$type);
		}
		else
		{
			if(!empty($_SESSION[WEBAPP]['Alert']))
			{
				include_once 'template/alert.php';
				unset($_SESSION[WEBAPP]['Alert']);
			}
		}
	}
	function createAlert($content='',$type='info')
	{
		echo "<div class='alert alert-{$type}' role='alert'>{$content}</div>";
	}
/* End BOOTSTRAP Helpers */

/* SPECIFIC TO WEBAPP */

function emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465){
	require_once "Mail.php"; 
	$headers=array(
			'From'=>$from,
			'To'=>$to,
			'Subject'=>$subject,
			'MIME-Version' => 1,
    		'Content-type' => 'text/html;charset=iso-8859-1'
		);
	$smtp=Mail::factory('smtp', array(
	        'host' => $host,
	        'port' => $port,
	        'auth' => true,
	        'username' => $username,
	        'password' => $password
	    ));
	//echo $to;
	$mail = $smtp->send($to, $headers, $body);
	if (PEAR::isError($mail))
	{	
		var_dump( $mail->getMessage());
	    return false;
	} else
	{
	    return true;
	}
}

function getEmailSettings(){
	global $con;
	return $con->myQuery("SELECT email_username as username,email_password as password,email_host as host,email_port as port FROM settings  LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}

function email_template($header,$message){
return <<<html
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Spark Global Tech Systems Inc. HRIS</title>


	<style type="text/css">
	img {
	max-width: 100%;
	}
	body {
	-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
	}
	body {
	background-color: #f6f6f6;
	}
	@media only screen and (max-width: 640px) {
	  body {
	    padding: 0 !important;
	  }
	  h1 {
	    font-weight: 800 !important; margin: 20px 0 5px !important;
	  }
	  h2 {
	    font-weight: 800 !important; margin: 20px 0 5px !important;
	  }
	  h3 {
	    font-weight: 800 !important; margin: 20px 0 5px !important;
	  }
	  h4 {
	    font-weight: 800 !important; margin: 20px 0 5px !important;
	  }
	  h1 {
	    font-size: 22px !important;
	  }
	  h2 {
	    font-size: 18px !important;
	  }
	  h3 {
	    font-size: 16px !important;
	  }
	  .container {
	    padding: 0 !important; width: 100% !important;
	  }
	  .content {
	    padding: 0 !important;
	  }
	  .content-wrap {
	    padding: 10px !important;
	  }
	  .invoice {
	    width: 100% !important;
	  }
	}
	</style>
	</head>

	<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

	<table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
			<td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
				<div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
					<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="alert alert-warning" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #348EDA; margin: 0; padding: 20px;" align="center" bgcolor="#348EDA" valign="top">
								{$header}
							</td>
						</tr><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											{$message}
										</td>
									</tr><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										</td>
									</tr>
									</table>
									</td>
						</tr></table><div class="footer" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
			</td>
			<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
		</tr></table></body>
	</html>
html;
}

function get_user_details($user_id){
	global $con;
	try {
		return $con->myQuery("SELECT * FROM users WHERE id=?",array($user_id))->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		return false;
	}
}

function record_movement($reimbursement_id,$action,$notes=""){
	global $con;
	try {
		$con->myQuery("INSERT reimbursement_movement(reimbursement_id,action_time,action,notes,user_id) VALUES(:reimbursement_id,NOW(),:action,:notes,:user_id)",
			array("reimbursement_id"=>$reimbursement_id,"action"=>$action,"notes"=>$notes,"user_id"=>$_SESSION[WEBAPP]['user']['id']));
		return true;
	} catch (Exception $e) {
		return false;
	}
	
}
function GetClaimDate($approval_date)
{
    #Approval
	//*
	$date=$approval_date;
	$input_date=new DateTime($date);
	$last_year=new DateTime($date);
	$next_year=new DateTime($date);
	$last_year->sub(new DateInterval('P1Y'));
	$next_year->add(new DateInterval('P1Y'));

	// echo "<pre>";
	// print_r($date_validations);
	// echo "</pre>";
	$current_date=new DateTime();
	$day=$input_date->format("d");
	$month=$input_date->format("m");
	// echo $input_date->format('Y-m-d')."<br/>";



	if(($day >= 1 && $day <= 4) || ($day >= 20 && $day <= 31)){
	    if(($day >= 20 && $day <= 31)){
	        $input_date->add(new DateInterval("P1M"));
	        // echo ($month+1)."-05";
	        $input_date->setDate($input_date->format("Y"),$input_date->format("m"),"05");
	        // echo $input_date->format("Y-m-d");
	    }
	    else{
	        $input_date->setDate($input_date->format("Y"),$input_date->format("m"),"05");
	    }
	}
	elseif(($day >= 5 && $day <= 19)){
	    $input_date->setDate($input_date->format("Y"),$input_date->format("m"),"20");
	}

	return $input_date->format("m/d/Y");

	//*/
}


/* END SPECIFIC TO WEBAPP */

	$con=new myPDO('automated_reimbursement','root','');	
	if(isLoggedIn()){
		if(!user_is_active($_SESSION[WEBAPP]['user']['id'])){
			refresh_activity($_SESSION[WEBAPP]['user']['id']);
			session_destroy();
			session_start();
			Alert("Your account has been deactivated.","danger");
			redirect('frmlogin.php');
			die;
		}
		if(is_active($_SESSION[WEBAPP]['user']['id'])){

			refresh_activity($_SESSION[WEBAPP]['user']['id']);
		}
		else{
			//echo 'You have been inactive.';
			// die;
			refresh_activity($_SESSION[WEBAPP]['user']['id']);
			// die;
			$con->myQuery("UPDATE users SET is_login=0 WHERE id=?",array($_SESSION[WEBAPP]['user']['id']));
			session_destroy();
			session_start();
			Alert("You have been inactive for 3 minutes and have been logged out.","danger");
			redirect('frmlogin.php');
			die;
		}
	}
?>