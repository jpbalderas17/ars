<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$organization="";
    
if(!empty($_GET['id'])){
        //$organization=$con->myQuery("SELECT id,user_type_id,first_name,middle_name,last_name,username,password,email,contact_no, security_question, security_answer from users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        //if(empty($organization)){
            //Alert("Invalid consumables selected.");
            //  Modal("Invalid user selected");
            //redirect("users.php");
            //die();
        //}
    }
    //$org_industries=$con->myQuery("SELECT id,name FROM org_industry")->fetchAll(PDO::FETCH_ASSOC);
    //$org_ratings=$con->myQuery("SELECT id,name FROM org_ratings")->fetchAll(PDO::FETCH_ASSOC);
    //$org_types=$con->myQuery("SELECT id,name FROM org_types")->fetchAll(PDO::FETCH_ASSOC);
    //$contact=$con->myQuery("SELECT id,CONCAT(fname,' ',lname) as name from contacts")->fetchAll(PDO::FETCH_ASSOC);
    //$user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);

    //$department=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$location=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$user_type=$con->myQuery("SELECT id,name FROM user_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("Reimbursement Creation");
?>
<script type="application/javascript">

  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }

</script>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Reimbursement Creation
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='create_users.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($organization)?$organization['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Name of Payee*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="payee_name" placeholder="Enter First Name" value="<?php echo !empty($organization)?$organization["first_name"]:"" ?>" required>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Transaction*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='expense_type' id='expense_type' data-placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> required>
                                                <option value="1">Services</option>
                                                <option value="2">Goods</option>
                                                <option value="3">Goods/Services</option>
                                                    <?php
                                                        //echo makeOptions($user_type,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Expense*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class="form-control" name="description" placeholder="Enter Middle name" value="<?php echo !empty($organization)?$organization["middle_name"]:"" ?>" required></textarea>
                                        
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Amount*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='amount' placeholder='Enter Username' value='<?php echo !empty($organization)?$organization['username']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> OR Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='or_number' placeholder='Enter Username' value='<?php echo !empty($organization)?$organization['username']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group' id='invoicediv' style="display: none;">
                                    <label class='col-sm-12 col-md-3 control-label'> Invoice Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='invoice_number' placeholder='Enter Password' value='<?php echo !empty($organization)?htmlspecialchars(decryptIt($organization['password'])):''; ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Cost Centers*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='cost_center' placeholder='Enter Email Address' value='<?php echo !empty($organization)?$organization['email']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Attachment*</label>
                                    <div class='col-sm-12 col-md-9'>
                                         <input type='file' multiple="" name='file' class="filestyle" data-classButton="" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Select Files">
                                    </div>
                                </div>

                                <!-- <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Person</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='c_num' placeholder='Enter Contact Person' value='<?php echo !empty($organization)?$organization['c_num']:"" ?>'>
                                    </div>
                                </div> -->

                                
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                        <a href='users.php' class='btn btn-default'>Cancel</a>
                                    </div>
                                    
                                </div>                        
                        </form>
                      </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });

 $(document).ready(function(){
    $('#expense_type').on('change', function() {
      if ( this.value == '2')
      //.....................^.......
      {
        $("#invoicediv").show();
      }
      else
      {
        $("#invoicediv").hide();
      }
    });
});
</script>

<?php
	makeFoot();
?>