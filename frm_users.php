<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }

if(!AllowUser(array(1))){
        redirect("index.php");
    }

    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT id,user_type_id,first_name,middle_name,last_name,username,password,email,contact_no,employee_no,title,department_id from users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid user selected");
            redirect("users.php");
            die();
        }
    }
    
    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($asset)){
            $old_asset=$asset;
        }
        $asset=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_asset)){
            $asset['id']=$old_asset['id'];
        }
    }

    $department=$con->myQuery("SELECT id,CONCAT(name,' (',code,')')as name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $user_type=$con->myQuery("SELECT id,name FROM user_types ")->fetchAll(PDO::FETCH_ASSOC);                                      

    makeHead("Users");
?>

<?php
     require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Users Form</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='row'>
                        <div class='col-sm-12 col-md-8 col-md-offset-2'>
                            <form class='form-horizontal' method='POST' action='create_users.php' enctype="multipart/form-data" onsubmit="return validate()">
                                <input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> User Type*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='user_type_id' data-placeholder='Select User Type' <?php echo!(empty($asset))?"data-selected='".$asset['user_type_id']."'":NULL ?> required>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> First name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter First Name' name='first_name' value='<?php echo !empty($asset)?$asset['first_name']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Middle name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Middle Name' name='middle_name' value='<?php echo !empty($asset)?$asset['middle_name']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Last name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Last Name' name='last_name' value='<?php echo !empty($asset)?$asset['last_name']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Username*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Username' name='username' value='<?php echo !empty($asset)?$asset['username']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Password*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='password' class='form-control' placeholder='Enter Password' name='password' value='<?php echo !empty($asset)?decryptIt($asset['password']):"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Confirm Password*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='password' class='form-control' placeholder='Confirm Password' name='confirm_password' value='<?php echo !empty($asset)?decryptIt($asset['password']):"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='email' class='form-control' placeholder='Enter Email Address' name='email' value='<?php echo !empty($asset)?$asset['email']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Employee Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' id='employee_no' placeholder='Enter Employee Number' name='employee_no' value='<?php echo !empty($asset)?$asset['employee_no']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Position*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Position' name='title' value='<?php echo !empty($asset)?$asset['title']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Department*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='department_id' data-placeholder='Select Department' <?php echo!(empty($asset))?"data-selected='".$asset['department_id']."'":NULL ?> required>
                                                    <?php
                                                    echo makeOptions($department);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='departments.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Contact Number' name='contact_no' value='<?php echo !empty($asset)?$asset['contact_no']:"" ?>' id='contact_no' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='user.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($asset['id'])?'Are you sure you want to cancel the modification of this user?':'Are you sure you want to cancel the creation of the new user?';?>')">Cancel</a>
                                        <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>
                                
                            </form>
                        </div>
                    </div>

                </div>
            </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#employee_no").inputmask("Regex",{
            "regex":"[0-9\-]*"
        });
        $("#contact_no").inputmask("99999999999");
    });

    function validate() {
        $("input[name='password']").val();
        $("input[name='confirm_password']").val();
        return true;

    }
</script>
<?php
if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
    // $asset=$_SESSION[WEBAPP]['frm_inputs'];
    // var_dump($asset);
    unset($_SESSION[WEBAPP]['frm_inputs']);
}

Modal();
?>
<?php
    makeFoot();
?>