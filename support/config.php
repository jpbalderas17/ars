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


function record_movement($reimbursement_id,$action,$notes=""){
	global $con;
	try {
		$con->myQuery("INSERT reimbursement_movement(reimbursement_id,action_time,action,notes) VALUES(:reimbursement_id,NOW(),:action,:notes)",array("reimbursement_id"=>$reimbursement_id,"action"=>$action,"notes"=>$notes));
		return true;
	} catch (Exception $e) {
		return false;
	}
	
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