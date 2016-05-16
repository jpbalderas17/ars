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
  //if(!empty($_GET['id'])){
        //$organization=$con->myQuery("SELECT id,org_name,reg_name,trade_name,tin_num,tel_num,phone_num,email,address,industry,rating,org_type,annual_revenue,assigned_to,description,contact_id FROM organizations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

        //if(empty($organization)){
            //Alert("Invalid asset selected.");
            //Modal("Invalid Organization Selected");
            //redirect("organizations.php");
            //die();
        //}
        //var_dump($organization);
        //die;
    //}
    
if(!empty($_GET['id'])){
        $organization=$con->myQuery("SELECT id,user_type_id,first_name,middle_name,last_name,username,password,email,contact_no, security_question, security_answer from users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($organization)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid user selected");
            redirect("users.php");
            die();
        }
    }
    //$org_industries=$con->myQuery("SELECT id,name FROM org_industry")->fetchAll(PDO::FETCH_ASSOC);
    //$org_ratings=$con->myQuery("SELECT id,name FROM org_ratings")->fetchAll(PDO::FETCH_ASSOC);
    //$org_types=$con->myQuery("SELECT id,name FROM org_types")->fetchAll(PDO::FETCH_ASSOC);
    //$contact=$con->myQuery("SELECT id,CONCAT(fname,' ',lname) as name from contacts")->fetchAll(PDO::FETCH_ASSOC);
    //$user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);

    //$department=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $getExpenseClassifications=$con->myQuery("SELECT id,name FROM expense_classifications WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $getTaxTypes=$con->myQuery("SELECT id,name FROM tax_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("Audit Reimbursement");
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
          <h1 class='page-header text-center text-brand'>
            Audit Reimbursement
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
                	<div class='col-sm-12 col-md-12'>
                        <form class='form-horizontal' method='POST' action='create_users.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($organization)?$organization['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Name of Payee</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["payee_name"]:"" ?></label>
                                    </div>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Transaction*</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["description"]:"" ?></label>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Amount</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["amount"]:"" ?></label>
                                    </div>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Expense*</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["expense_type"]:"" ?></label>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> OR Number</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["or_number"]:"" ?></label>
                                    </div>
                                    <label class='col-sm-12 col-md-3 control-label'> Invoice Number</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["invoice_number"]:"" ?></label>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Cost Center</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["cost_center"]:"" ?></label>
                                    </div>
                                    <label class='col-sm-12 col-md-3 control-label'> Attachment</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <label class='col-sm-12 col-md-2 control-label'>
                                        <?php echo !empty($organization)?$organization["attachment"]:"" ?></label>
                                    </div>
                                </div>


                                
                                    

                                    <div class="box box-primary">
                                    <div class="box-body">
                                    <div class="row">
                                    <div class='form-group'>
                                    <div class='col-sm-12 col-md-12'>
                                    <label class='col-sm-12 col-md-3 control-label'> Reimbursement Type*</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='expense_type' id='expense_type' data-placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> style='width:100%' required>
                                                <!--<option value="Services">Services</option>
                                                <option value="Goods">Goods</option>
                                                <option value="Goods/Services">Goods/Services</option>-->
                                                    <?php
                                                        echo makeOptions($getExpenseClassifications,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>
                                    <label class='col-sm-12 col-md-3 control-label'> Tax Type*</label>
                                    <div class='col-sm-12 col-md-3'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='expense_type' id='expense_type' data-placeholder="Select Tax Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> style='width:100%' required>
                                                <!--<option value="Services">Services</option>
                                                <option value="Goods">Goods</option>
                                                <option value="Goods/Services">Goods/Services</option>-->
                                                    <?php
                                                        echo makeOptions($getTaxTypes,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>        
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-brand btn-flat'> <span class='fa fa-check'></span> Save</button>
                                        <a href='users.php' class='btn btn-default btn-flat'>Cancel</a>
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
</script>

<?php
	makeFoot();
?>