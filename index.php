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
          <h1 class='page-header text-center text-green'>
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
                <div class="col-lg-3 col-md-6">
                        <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-barcode fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'></div>
                                    <div>Total Assets</div>
                                </div>
                            </div>
                        </div>
                        <a href="assets.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-barcode fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'></div>
                                    <div>Assets Available</div>
                                </div>
                            </div>
                        </div>
                        <a href="assets.php?status=Deployable">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tint fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'></div>
                                    <div>Consumables</div>
                                </div>
                            </div>
                        </div>
                        <a href="consumables.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-globe fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'></div>
                                    <div>Locations</div>
                                </div>
                            </div>
                        </div>
                        <a href="locations.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
           
            
        </section><!-- /.content -->
  </div>

<?php
    Modal();
	makeFoot();
?>