<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="index.php"?"active":"";?>">
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="frm_change_pass.php"?"active":"";?>">
              <a href="frm_change_pass.php">
                <i class="fa fa-key"></i> <span>Change Password</span>
              </a>
            </li>
             <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "report_asset.php",
            "reimbursements_audit.php",
            "report_asset_maintenance.php",
            "consumables_report.php",
            "consumable_activity_report.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>My Reimbursement</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset.php"?"active":"";?>">
                    <a href="report_asset.php"><i class="fa fa-circle-o"></i><span>Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_audit.php"?"active":"";?>">
                    <a href="reimbursements_audit.php"><i class="fa fa-circle-o"></i><span>Returned Reimbursements</span></a>
                </li>
              </ul>
            </li>
            <?php
              if(AllowUser(array(1,2))):
            ?>
             <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "report_asset.php",
            "reimbursements_audit.php",
            "report_asset_maintenance.php",
            "consumables_report.php",
            "consumable_activity_report.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Reimbursement</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <?php
                  if(AllowUser(array(1))):
                ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset.php"?"active":"";?>">
                    <a href="report_asset.php"><i class="fa fa-circle-o"></i><span>Approval</span></a>
                </li>
                <?php
                  endif;
                ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_audit.php"?"active":"";?>">
                    <a href="reimbursements_audit.php"><i class="fa fa-circle-o"></i><span>Audit</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset_maintenance.php"?"active":"";?>">
                    <a href="report_asset_maintenance.php"><i class="fa fa-circle-o"></i><span>History</span></a>
                </li>
              </ul>
            </li>

            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "report_asset.php",
            "report_asset_activity.php",
            "report_asset_maintenance.php",
            "consumables_report.php",
            "consumable_activity_report.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Report Generation</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset.php"?"active":"";?>">
                    <a href="report_asset.php"><i class="fa fa-circle-o"></i><span>Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_asset_activity.php"?"active":"";?>">
                    <a href="report_asset_activity.php"><i class="fa fa-circle-o"></i><span>Reimbursement History</span></a>
                </li>
              </ul>
            </li>
            <?php
              endif;
            ?>
            <?php
              if(AllowUser(array(1))):
            ?>
            <li class='header'>ADMINISTRATOR MENU</li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "asset_status_labels.php",
            "user.php",
            "departments.php",
            "depreciations.php",
            "locations.php",
            "maintenance_types.php",
            "manufacturers.php",
            "settings.php"
            )))?"active":"";?>'>
              <a href=''><i class="fa fa-cubes"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="user.php"?"active":"";?>">
                  <a href="user.php">
                    <i class="fa fa-users"></i> <span>Users</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="settings.php"?"active":"";?>">
                  <a href="settings.php">
                    <i class="fa fa-gear"></i> <span>Settings</span>
                  </a>
                </li>
                <!-- <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="audit_log.php"?"active":"";?>">
                  <a href="audit_log.php">
                    <i class="fa fa-list"></i> <span>Audit Log</span>
                  </a>
                </li> -->

                <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
                "asset_status_labels.php",
                "departments.php",
                "depreciations.php",
                "locations.php",
                "maintenance_types.php",
                "manufacturers.php")))?"active":"";?>'>
                  <a href="#">
                    <i class="fa fa-sort-alpha-asc"></i>
                    <span>Metadata</span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>

                  <ul class='treeview-menu'>
                    <li class='<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="departments.php"?"active":"";?>'><a href="departments.php"><i class="fa fa-building-o fa-fw"></i> <span>Departments</span></a>
                    </li>

                  </ul>
                </li>
                
              </ul>
            </li>
            <?php
              endif;
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>