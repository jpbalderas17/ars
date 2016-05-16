<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
 
   
    //$asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
    $data=$con->myQuery("SELECT password_question,password_answer FROM users WHERE id=?",array($_SESSION[WEBAPP]['user']['id']))->fetch(PDO::FETCH_ASSOC);
	makeHead("Users");
?>
<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <section class='content'>
        <div class="row">
            <div class="col-lg-12">
                    <h3 class="page-header text-center text-green">Secret Question</h3>
                </div>
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                   <div class='row'>
                       <div class='col-sm-12 col-md-8 col-md-offset-2'>
                            <form class='form-horizontal' method='POST' action='secret_question.php?from=form' enctype="multipart/form-data" onsubmit='return validate(this)'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Secret Question*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='password_question' placeholder='Enter secret question' value='<?php echo !empty($data)?$data['password_question']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Answer*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='password_answer' placeholder='Enter Answer' value='<?php echo !empty($data)?$data['password_answer']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='view_user.php?id=<?php echo $_SESSION[WEBAPP]['user']['id']?>' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($asset) && !empty($asset['id'])?'Are you sure you want to cancel the modification of the secret question?':'Are you sure you want to cancel the modification of the secret question?';?>')">Cancel</a>
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

<?php
Modal();
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#assets").DataTable({
            "scrollX":"true"
        });
        $("#consumables").DataTable({
               "scrollX":"true"
        });
    });
</script>
<?php
	makeFoot();
?>