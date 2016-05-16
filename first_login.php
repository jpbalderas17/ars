<?php
	require_once("support/config.php");

  if(!isLoggedIn()){
    redirect("frmlogin.php");
    die();
  }

  if($_SESSION[WEBAPP]['user']['password_question']){
    redirect("index.php");
    die;
  }

  // $username=$con->myQuery("SELECT username FROM users WHERE id=?",array($_SESSION[WEBAPP]['user']['id']))->fetchColumn();
	makeHead("Login");
?>
    <div class="login-box">
      <div class='login-box-header bg-green'><h4>SGTSI ASSET Management System</h4></div>
      <div class="login-box-body">
        <?php
          Alert();
        ?>
        <h4 class="login-box-msg text-green">You don't have a secret question set.<br/> Please enter a question and it's answer to be used for password retrieval.</h4>
        <form action="secret_question.php?from=login" method="post">
          <div class="form-group has-feedback">
            <span class="fa fa-question form-control-feedback" style='left:0px'></span>
            <input type="text" class="form-control" placeholder="Enter secret question" name='password_question' autofocus="" style="padding-left: 42.5px;padding-right: 0px">
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Enter Answer" name='password_answer' style="padding-left: 42.5px;padding-right: 0px">
            <span class="fa fa-check form-control-feedback" style='left:0px'></span>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-success btn-block btn-flat">Save</button>
            </div><!-- /.col -->
          </div>
        </form>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
<?php
  Modal();
	makeFoot();
?>