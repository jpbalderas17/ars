<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
 
    if(!AllowUser(array(1))){
        redirect("index.php");
        die;
    }
    //$asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
    $data=$con->myQuery("SELECT * FROM settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	makeHead("Settings");
?>
<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <section class='content'>
        <div class="row">
            <div class="col-lg-12">
                    <h3 class="page-header text-center text-brand">Settings</h3>
                </div>
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                   <div class='row'>
                       <div class='col-sm-12 col-md-8 col-md-offset-2'>
                            <form class='form-horizontal' method='POST' action='save_settings.php' enctype="multipart/form-data" onsubmit='return validate(this)'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Default Password*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='default_password' placeholder='Enter secret question' value='<?php echo !empty($data)?$data['default_password']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-flat btn-brand'> <span class='fa fa-check'></span> Save</button>
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