<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }

	makeHead("Login");
?>
    <div class="login-box">
      <div class='login-box-header bg-brand'><h4>SGTSI Automated Reimbursement System</h4></div>
      <div class="login-box-body">
        <?php
          Alert();
        ?>
        <h4 class="login-box-msg text-brand">Login to your Account</h4>
        <form action="logingin.php" method="post">
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-user form-control-feedback" style='left:0px'></span>
            <input type="text" class="form-control " placeholder="Username" name='username' autofocus="" style="padding-left: 42.5px;padding-right: 0px" required="">
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name='password' style="padding-left: 42.5px;padding-right: 0px" required="">
            <span class="glyphicon glyphicon-lock form-control-feedback" style='left:0px'></span>
          </div>
          <div class="row">
            <div class="col-xs-12 text-center">
              <button type="submit" class="btn btn-brand btn-block btn-flat">Login</button>
              <br/>
              <a href='forgot_password.php'>Forgot Password</a>
            </div><!-- /.col -->
          </div>
        </form>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

<?php
  Modal();
	makeFoot();
?>