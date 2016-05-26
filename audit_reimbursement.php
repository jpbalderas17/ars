<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

	$reimbursement="";
    
    if(!empty($_GET['id'])){
        $reimbursement=$con->myQuery("SELECT * FROM vw_reimbursements WHERE id=? AND status='For Audit'",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($reimbursement)){
            Modal("Invalid reimbursement.");
            redirect("reimbursements_audit.php");
            die;
        }
        else{
            $getAttachments=$con->myQuery("SELECT
            file_name,
            DATE_FORMAT(date_added, '%m/%d/%Y') as date_added,
            file_location,
            id
            from files
            where is_deleted=0
            and reimbursement_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

            
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
                        <form class='form-horizontal' method='POST' action='for_approval.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($reimbursement)?$reimbursement['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-2 control-label'> Name of Payee</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <?php echo !empty($reimbursement)?$reimbursement["payee"]:"" ?>
                                    </div>
                                    <label class='col-sm-12 col-md-2 control-label'> Description of Transaction</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <?php 
                                            if(!empty($reimbursement['goods_services'])){
                                                switch ($reimbursement['goods_services']) {
                                                    case '1':
                                                        echo "Services";
                                                        break;
                                                    case '2':
                                                        echo "Goods";
                                                        break;
                                                    case '3':
                                                        echo "Goods and Services";
                                                        break;
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-2 control-label'> Amount</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <?php echo !empty($reimbursement)?number_format($reimbursement["amount"],2):"" ?>
                                    </div>
                                    <label class='col-sm-12 col-md-2 control-label'> Description of Expense</label>
                                    <div class='col-sm-12 col-md-4'>
                                        
                                        <?php echo !empty($reimbursement)?$reimbursement["description"]:"" ?>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-2 control-label'> OR Number</label>
                                    <div class='col-sm-12 col-md-4'>
                                        
                                        <?php echo !empty($reimbursement)?$reimbursement["or_number"]:"" ?>
                                    </div>
                                    <label class='col-sm-12 col-md-2 control-label'> Cost Center</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <?php echo !empty($reimbursement)?$reimbursement["department"]:"" ?>
                                    </div>
                                </div>
                                <?php
                                    if(!empty($reimbursement['goods_services']) && $reimbursement['goods_services']==2):
                                ?>
                                <div class='form-group'>
                                <label class='col-sm-12 col-md-2 control-label'> Invoice Number</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <?php echo !empty($reimbursement)?$reimbursement["invoice_number"]:"" ?>
                                    </div>
                                    
                                </div>
                                <?php
                                    endif;
                                ?>
                                <div class='form-group'>
                                    <div class='form-group' id='tblAttachments' >
                                <div class='col-sm-12 col-md-12 text-center'>
                             <h4 class='col-sm-12 col-md-12 text-brand'>Attachments</h4>  
                                    <div class='dataTable_wrapper '>
                                        <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                            <thead>
                                                <tr>
                                                    <tr>    
                                                        <th class='date-td text-center'>File Name</th>
                                                        <th class='date-td text-center'>Date Uploaded</th>
                                                        <th class='text-center'>Action</th>
                                                    </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                    <?php
                                                        if(!empty($getAttachments)):   
                                                        foreach ($getAttachments as $value): 
                                                    ?>
                                                <tr>
                                                    <?php
                                                            
                                                                foreach($value as $key=> $data):
                                                                if($key=='file_location'):
                                                                elseif($key=='id'):
                                                    ?>
                                                
                                                    <td>
                                                        <a class='btn btn-brand btn-flat' href='frm_documents.php?id=<?php echo $data;?>'><span class='fa fa-download'></span></a>
                                                    </td>

                                                    <?php
                                                            else:
                                                    ?>

                                                    <td>
                                                                <?php
                                                                    echo htmlspecialchars($data);
                                                                ?>
                                                    </td>
                                                
                                                    <?php
                                                            endif;
                                                            endforeach;
                                                            
                                                    ?>
                                                </tr>
                                                    <?php
                                                            endforeach;
                                                            endif;
                                                    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                </div>
                                    <div class="box box-primary">
                                    <div class="box-body">
                                    <div class="row">
                                    <div class='form-group'>
                                    <div class='col-sm-12 col-md-12'>
                                    <label class='col-sm-12 col-md-2 control-label'> Reimbursement Type</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='expense_type_id' id='expense_type_id' data-placeholder="Select Reimbursement Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> style='width:100%' required>
                                                <!--<option value="Services">Services</option>
                                                <option value="Goods">Goods</option>
                                                <option value="Goods/Services">Goods/Services</option>-->
                                                    <?php
                                                        echo makeOptions($getExpenseClassifications,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>
                                    <label class='col-sm-12 col-md-2 control-label'> Tax Type</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='tax_type_id' id='tax_type_id' data-placeholder="Select Tax Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> style='width:100%' required>
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
                                    <div class='col-sm-12 col-md-12 text-center '>
                                        <button type='submit' class='btn btn-brand btn-flat'> <span class='fa fa-check'></span> Save</button>
                                        <a href='reimbursements_audit.php' class='btn btn-default btn-flat'>Cancel</a>
                                    </div>
                                    
                                </div>                        
                                </form>                                
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
        $('#dataTables').DataTable({
            "searching":false
        });
      });
</script>

<?php
	makeFoot();
?>