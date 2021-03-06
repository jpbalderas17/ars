<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }


  if(!empty($_POST['step'])){
    $inputs=$_POST;
    $inputs=array_map('trim', $inputs);
    switch ($_POST['step']) {
      case '1':
        $user=$con->myQuery("SELECT password_question,is_active,username,is_login FROM users WHERE BINARY username=? AND is_deleted=0 LIMIT 1",array($_POST['username']))->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)){
          if(empty($user['password_question'])){
            Alert("You don't have a question set.","danger");
            redirect("frmlogin.php");
            die;
          }

          if($user['is_active']==0){
            Alert("Your account is currently deactivated.","danger");
            redirect("frmlogin.php");
            die;
          }

          if($user['is_login']==1){
            Alert("Your account is currently logged in.","danger");
            redirect("frmlogin.php");
            die;
          }

          $step=2;
        }
        else{
          $step=1;
          Alert("Account does not exist.","danger");
        }
        break;

       case '2':
        $user=$con->myQuery("SELECT id,password_question,is_active,username,password_answer,is_login FROM users WHERE BINARY username=? AND  is_deleted=0 LIMIT 1",array($inputs['username']))->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)){
          if($user['is_active']==0){
            Alert("Your account is currently deactivated.","danger");
            redirect("frmlogin.php");
            die;
          }

          if($user['is_login']==1){
            Alert("Your account is currently logged in.","danger");
            redirect("frmlogin.php");
            die;
          }

          $has_error=false;

          if(empty($inputs['answer'])){
            Alert("Please enter an answer.","danger");
            $has_error=true;
            // redirect("frmlogin.php");
            // die;
          }
          else{
            if($user['password_answer']!=$inputs['answer']){
            Alert("Invalid answer.","danger");
              $has_error=true;
            }
          }

          if($has_error===false){
            //update to default password
            $default_password=$con->myQuery("SELECT default_password FROM settings LIMIT 1")->fetchColumn();
            $con->myQuery("UPDATE users set password=? WHERE id=?",array(encryptIt($default_password),$user['id']));
            Alert("Your password has been reset.","success");
            redirect("frmlogin.php");
            die;
          }

          $step=2;
        }
        else{
          $step=1;
          Alert("Account does not exist.","danger");
        }
        break;
      
      default:
        redirect("index.php");
        break;
    }
    
  }
  else{
    $step=1;
  }

	makeHead("Login");
?>
    <div class="login-box">
      <div class='login-box-header bg-brand'><h4>SGTSI ASSET Management System</h4></div>
      <div class="login-box-body">
        <?php
          Alert();
        ?>
        <?php
          if($step==1):
        ?>
          <h4 class="login-box-msg text-brand">Enter your username</h4>
          <form action="forgot_password.php" method="post">
            <input type='hidden' name='step' value='1'>
            <div class="form-group has-feedback">
              <span class="glyphicon glyphicon-user form-control-feedback" style='left:0px'></span>
              <input type="text" class="form-control" placeholder="Username" name='username' autofocus="" style="padding-left: 42.5px;padding-right: 0px" required>
            </div>
            <div class="row">
              <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-brand btn-block btn-flat">Continue</button>
                <a href='frmlogin.php' class="btn btn-brand btn-block btn-flat">Back to Login</a>
              </div><!-- /.col -->
            </div>
          </form>
        <?php
          elseif($step==2):
        ?>
          <h4 class="login-box-msg text-brand">Enter the answer to the question</h4>
          <form action="forgot_password.php" method="post">
            <input type='hidden' name='step' value='<?php echo $step; ?>'>
            <input type='hidden' name='username' value='<?php echo $user['username']; ?>'>

            <div class="form-group has-feedback">
              <p><?php echo htmlspecialchars($user['password_question']); ?></p>
            </div>

            <div class="form-group has-feedback">
              <span class="fa fa-check-o " ></span>
              <input type="text" class="form-control" placeholder="Answer" name='answer' autofocus="" style="" required>
            </div>
            <div class="row">
              <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-brand btn-block btn-flat">Continue</button>
                <a href='frmlogin.php' class="btn btn-brand btn-block btn-flat">Back to Login</a>
              </div><!-- /.col -->
            </div>
          </form>
        <?php
          endif;
        ?>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

<?php
  Modal();
	makeFoot();
?>