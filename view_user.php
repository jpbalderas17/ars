<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,title,department,id FROM qry_users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Users Selected");
            redirect("user.php");
            die();
        }
    }
    //$asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
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
                    <h3 class="page-header text-center text-brand"><?php echo htmlspecialchars($asset['name'])?></h3>
                </div>
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='row'>
                            <div class='col-md-12'>
                            <?php
                                if($_SESSION[WEBAPP]['user']['id']==$asset['id']):
                            ?>
                            <div class='row'>
                                <div class='col-md-12'>
                                <a href='change_secret_password.php' class='btn btn-brand btn-flat'>Change Secret Question</a>
                                </div>
                            </div>
                            <?php
                                endif;
                            ?>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Employee Number: </strong>
                                    <em><?php echo htmlspecialchars($asset['employee_no'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Email Address: </strong>
                                    <em><?php echo htmlspecialchars($asset['email'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Contact Number: </strong>
                                    <em><?php echo htmlspecialchars($asset['contact_no'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Department: </strong>
                                    <em><?php echo htmlspecialchars($asset['department'])?></em>
                                </div>
                            </div>
                           
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