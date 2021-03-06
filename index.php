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
            <?php
                #TRANSACTION AND FILING
                /*
                $date='2014-01-01';
                $input_date=new DateTime($date);
                $last_year=new DateTime($date);
                $next_year=new DateTime($date);
                $last_year->sub(new DateInterval('P1Y'));
                $next_year->add(new DateInterval('P1Y'));

                $date_validations=$con->myQuery("SELECT * FROM date_validations")->fetchAll(PDO::FETCH_ASSOC);

                // echo "<pre>";
                // print_r($date_validations);
                // echo "</pre>";
                
                echo $date;

                foreach ($date_validations as $row) {
                    $cut_off_start=new DateTime($row['cut_off_start']);
                    $cut_off_end=new DateTime($row['cut_off_end']);

                    if($row['id']==25){
                        $cut_off_end->setDate($next_year->format("Y"),$cut_off_end->format("m"),$cut_off_end->format("d"));                        
                    }
                    else{
                        $cut_off_end->setDate($input_date->format("Y"),$cut_off_end->format("m"),$cut_off_end->format("d"));
                    }
                    
                    if($row['id']==1){

                        $cut_off_start->setDate($last_year->format("Y"),$cut_off_start->format("m"),$cut_off_start->format("d"));
                    }
                    else{
                        $cut_off_start->setDate($input_date->format("Y"),$cut_off_start->format("m"),$cut_off_start->format("d"));
                    }

                    if($input_date >= $cut_off_start && $input_date <= $cut_off_end){
                    // // print_r($date_validations);
                        echo "<h1>In Here</h1>";
                        echo "<pre>";
                        print_r($cut_off_start);
                        print_r($cut_off_end);
                        echo "</pre>";
                    }
                    else{
                        // echo "<pre>";
                        // print_r($cut_off_start);
                        // print_r($cut_off_end);
                        // echo "</pre>";   
                    }
                }
                //*/
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