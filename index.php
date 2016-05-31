<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	makeHead();
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class='page-header text-center text-brand'>
            Dashboard
          </h1>
        </section>
        <div class='col-md-12'>
            <?php
                Alert();
            ?>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-brand">
                        <div class="inner">
                          <h3>&nbsp;</h3>
                          <p class=''>&nbsp;</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-file-text"></i>
                        </div>
                        <a href="create_reimbursement.php" class="small-box-footer">
                          Create Reimbursement Request <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-brand">
                        <div class="inner">
                          <h3>&nbsp;</h3>
                          <p class=''>&nbsp;</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-key"></i>
                        </div>
                        <a href="change_password.php" class="small-box-footer">
                          Change Password <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-brand">
                        <div class="inner">
                          <h3>&nbsp;</h3>
                          <p class=''>&nbsp;</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-question-circle"></i>
                        </div>
                        <a href="change_secret_password.php" class="small-box-footer">
                          Change Secret Question <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <?php

                    if(!empty($returned_count) || !empty($draft_count)):
                ?>
                    <div class='col-md-12'>
                        <h3>
                            Your Reimbursements
                        </h3>
                    </div>
                    <div class='col-md-10 col-md-offset-1'>
                        <?php
                            if(!empty($returned_count)):
                        ?>
                        <div class='col-md-6'>
                            <div class="info-box">
                                <span class="info-box-icon bg-brand"><i class="ion ion-document-text" style='color:white'></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Returned Reimbursements</span>
                                  <span class="info-box-number"><?php echo $returned_count;?></span>
                                </div><!-- /.info-box-content -->
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                        <?php
                            if(!empty($draft_count)):
                        ?>
                        <div class='col-md-6'>
                            <div class="info-box">
                                <span class="info-box-icon bg-brand"><i class="ion ion-document-text"  style='color:white'></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Draft Reimbursements</span>
                                  <span class="info-box-number"><?php echo $draft_count;?></span>
                                </div><!-- /.info-box-content -->
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                <?php
                    endif;
                ?>

                <?php

                    if(!empty($approval_count) || !empty($audit_count)):
                ?>
                    <div class='col-md-12'>
                        <h3>
                            For Approval
                        </h3>
                    </div>
                    <div class='col-md-10 col-md-offset-1'>
                        <?php
                            if(!empty($approval_count)):
                        ?>
                            <div class='col-md-6'>
                                <div class="info-box">
                                    <span class="info-box-icon bg-brand"><i class="ion ion-document-text" style='color:white'></i></span>
                                    <div class="info-box-content">
                                      <span class="info-box-text">Reimbursements For Approval</span>
                                      <span class="info-box-number"><?php echo $approval_count;?></span>
                                    </div><!-- /.info-box-content -->
                                </div>
                            </div>
                        <?php
                            endif;
                        ?>
                        <?php
                            if(!empty($audit_count)):
                        ?>
                            <div class='col-md-6'>
                                <div class="info-box">
                                    <span class="info-box-icon bg-brand"><i class="ion ion-document-text"  style='color:white'></i></span>
                                    <div class="info-box-content">
                                      <span class="info-box-text">Reimbursemnts For Audit</span>
                                      <span class="info-box-number"><?php echo $audit_count;?></span>
                                    </div><!-- /.info-box-content -->
                                </div>
                            </div>
                        <?php
                            endif;
                        ?>
                    </div>
                <?php
                    endif;
                ?>
            </div>
           
            
        </section><!-- /.content -->
  </div>

<?php
    Modal();
	makeFoot();
?>