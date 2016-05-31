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
            "reimbursements_drafts.php",
            "reimbursements_all.php",
            "reimbursements_approved.php",
            "reimbursements_cancelled.php",
            "returned_reimbursements.php",
            "rejected_reimbursements.php"
            )))?"active":"";?>'>
                <?php
                  $returned_count=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` WHERE  status='Returned' AND is_deleted=0 AND user_id=?",array($_SESSION[WEBAPP]['user']['id']))->fetchColumn();
                  $draft_count=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` WHERE  status='Draft' AND is_deleted=0 AND user_id=?",array($_SESSION[WEBAPP]['user']['id']))->fetchColumn();
                  $my_reimbursement_count=$returned_count+$draft_count;
                ?>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>My Reimbursement <?php echo !empty($my_reimbursement_count)?"<span class='label bg-brand'>{$my_reimbursement_count}</span>":'';?></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_all.php"?"active":"";?>">
                    <a href="reimbursements_all.php"><i class="fa fa-circle-o"></i><span>Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_approved.php"?"active":"";?>">
                    <a href="reimbursements_approved.php"><i class="fa fa-circle-o"></i><span>Approved Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_drafts.php"?"active":"";?>">
                    <a href="reimbursements_drafts.php"><i class="fa fa-circle-o"></i><span>Draft Reimbursements <?php echo !empty($draft_count)?"<span class='label bg-brand'>{$draft_count}</span>":'';?></span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="returned_reimbursements.php"?"active":"";?>">
                    <a href="returned_reimbursements.php"><i class="fa fa-circle-o"></i><span>Returned Reimbursements <?php echo !empty($returned_count)?"<span class='label bg-brand'>{$returned_count}</span>":'';?></span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="rejected_reimbursements.php"?"active":"";?>">
                    <a href="rejected_reimbursements.php"><i class="fa fa-circle-o"></i><span>Rejected Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_cancelled.php"?"active":"";?>">
                    <a href="reimbursements_cancelled.php"><i class="fa fa-circle-o"></i><span>Cancelled Reimbursements</span></a>
                </li>
              </ul>
            </li>
            <?php
              if(AllowUser(array(1,2))):
                $audit_count=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` WHERE  status='For Audit' AND is_deleted=0 ")->fetchColumn();
                $approval_count=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` WHERE  status='For Approval' AND is_deleted=0 ")->fetchColumn();
                $reimbursement_count=$audit_count+$approval_count;
            ?>
             <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "reimbursements_approval.php",
            "reimbursements_audit.php",
            "report_asset_maintenance.php",
            "consumables_report.php",
            "consumable_activity_report.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Reimbursement <?php echo !empty($reimbursement_count)?"<span class='label bg-brand'>{$reimbursement_count}</span>":'';?></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <?php
                  if(AllowUser(array(1))):
                ?>
                  <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_approval.php"?"active":"";?>">
                      <a href="reimbursements_approval.php"><i class="fa fa-circle-o"></i><span>Approval <?php echo !empty($approval_count)?"<span class='label bg-brand'>{$approval_count}</span>":'';?></span></a>
                  </li>
                <?php
                  endif;
                ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reimbursements_audit.php"?"active":"";?>">
                    <a href="reimbursements_audit.php"><i class="fa fa-circle-o"></i><span>Audit <?php echo !empty($audit_count)?"<span class='label bg-brand'>{$audit_count}</span>":'';?></span></a>
                </li>
              </ul>
            </li>

            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array(
            "report_asset.php",
            "report_asset_activity.php",
            "report_asset_maintenance.php",
            "report_reimbursements.php",
            "report_reimbursements_history.php"
            )))?"active":"";?>'>
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Report Generation</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_reimbursements.php"?"active":"";?>">
                    <a href="report_reimbursements.php"><i class="fa fa-circle-o"></i><span>Reimbursements</span></a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="report_reimbursements_history.php"?"active":"";?>">
                    <a href="report_reimbursements_history.php"><i class="fa fa-circle-o"></i><span>Reimbursement History</span></a>
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