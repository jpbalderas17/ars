<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    // if(!AllowUser(array(2))){
    //     redirect("index.php");
    // }

  $emp_id=$_SESSION[WEBAPP]['user']['id'];

  //$data=$con->myQuery("SELECT password FROM users WHERE is_deleted=0 AND id=? LIMIT 1",array($emp_id))->fetch(PDO::FETCH_ASSOC);

	makeHead("Change Password");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">

        <div class="content-header ">
          <h1 class="page-header text-brand text-center">
            Change Password
          </h1>
        </div>
          <!-- Main row -->
          <br/>
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="row">
                  <div class='col-md-12'>
                  
                    <form class='form-horizontal' name='frm_pass' action='save_password.php' method='POST' onsubmit='return validate(this)'>

                      <input type='hidden' name='emp_id' value='<?php echo !empty($emp_id)?$emp_id:''; ?>'>
                        <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">Current Password *</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" id="cur_password" placeholder="Current Password" name='cur_password' value='' required>
                          </div>
                        </div>
                     

                        <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">New Password *</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" placeholder="New Password" name='password' value='' required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">Confirm New Password *</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" id="con_password" placeholder="Confirm Password" name='con_password' value='' required>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-9 col-md-offset-2 ">
                            <button type='submit' class='btn btn-brand btn-flat'>Save </button>
                            <a href='index.php' class='btn btn-default btn-flat'>Cancel</a>
                          </div>
                        </div>
                    </form> 
                  </div>
                  </div><!-- /.row -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>


  <script type='text/javascript'>
    function validate(frm) 
    {
      var js_new_pass = document.forms["frm_pass"]["password"].value;
      var js_confirm_pass = document.forms["frm_pass"]["con_password"].value;

      if (js_new_pass !== js_confirm_pass) 
      {
        alert("Retry Confirm Password.");
        return false;
      }
      // if (checkPassword(js_new_pass)==false)
      // {
      //   alert("Password should consist of atleast 1 Capital Letter and atleast 1 Number.");
      //   return false;
      // }
      return true;
    }

    // function checkPassword(pwd)
    // {
    //   var letterSmall = /[a-z]/;
    //   var letterCap = /[A-Z]/; 
    //   var number = /[0-9]/;
    //   var valid = number.test(pwd) && letterCap.test(pwd) && letterSmall.test(pwd); 
    //   return valid;
    // }

  </script>

<?php
	makeFoot();
?>