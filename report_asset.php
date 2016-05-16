<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }
    $departments=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Asset Reports");
?>
<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>
        Asset Reports
        </h1>
    </div>
    <section class='content'>
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='col-sm-12'>
                      <form method='get'>
                      <div class=''>
                          <label class='col-md-2 text-right' >Department</label>
                          <div class='col-md-3'>
                            <select id='departments' class='form-control' name='department_id' data-placeholder='Select Department' onchange='getUsers()' <?php echo !(empty($_GET['department_id']))?"data-selected='".$_GET['department_id']."'":NULL ?>>
                                <?php echo makeOptions($departments) ?>
                            </select>
                          </div>
                      </div>
                      <div class=''>
                          <label class='col-md-2 text-right' >User</label>
                          <div class='col-md-3'>
                            <select id='users' class='form-control' name='user_id' data-placeholder='Select User' <?php echo !(empty($_GET['user_id']))?"data-selected='".$_GET['user_id']."'":NULL ?>>
                            <?php echo makeOptions($users) ?>
                            </select>
                          </div>
                      </div>
                      
                      <div class='col-md-2'>
                        <button type='submit'  class='btn-flat btn btn-success' >Filter</button>
                      </div>
                      </form>
                    </div>
                    <br/>
                    <br/>
                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Asset Tag</th>
                                            <th class='text-center'>Serial Number</th>
                                            <th class='text-center' style="min-width: 150px">Asset Name</th>
                                            <th class='text-center'>Manufacturer</th>
                                            <th class='text-center'>Model</th>
                                            <th class='text-center'>Status</th>
                                            <th class='text-center'>Location</th>
                                            <th class='text-center'>Category</th>
                                            <th class='date-td text-center'>EOL</th>
                                            <th class='text-center'>Order Number</th>
                                            <th class='date-td text-center'>Checkout Date</th>
                                            <th class='date-td text-center'>Expected Checkin Date</th>
                                            <th class='date-td text-center'>Purchase Date</th>
                                            <th class='date-td text-center'>Depreciation Date</th>
                                            <th class='text-center'>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $filter_sql="";
                                            $dep_sql="";
                                            $user_sql="";
                                            if(!empty($_GET['department_id'])){
                                                $dep_sql="u.department_id=:department_id";
                                                $inputs['department_id']=$_GET['department_id'];
                                                $filter_sql.=$dep_sql;
                                            }

                                            if(!empty($_GET['user_id'])){
                                                $user_sql="u.id=:user_id";
                                                $inputs['user_id']=$_GET['user_id'];
                                                if(!empty($filter_sql)){
                                                    $filter_sql.=" AND ";
                                                }
                                                $filter_sql.=$user_sql;
                                            }
                                            if(!empty($dep_sql) || !empty($user_sql)){
                                                $filter_sql="AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
                                            }
                                            else{
                                                $filter_sql="";
                                            }
                                            // die($filter_sql);
                                            $asset_sql="SELECT asset_tag,serial_number,asset_name,manufacturer,model,asset_status,asset_status_label,location,category,DATE_FORMAT(eol,'%m/%d/%Y')as eol,order_number,DATE_FORMAT(check_out_date,'%m/%d/%Y') as check_out_date,DATE_FORMAT(expected_check_in_date,'%d/%m/%Y') as expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder,DATE_FORMAT(purchase_date,'%m/%d/%Y') as purchase_date,depreciation_term,notes FROM qry_assets WHERE is_deleted=0 {$filter_sql}";
                                            if(empty($_GET['status']) || $_GET['status']=='All'){
                                                if(!empty($dep_sql) || !empty($user_sql)){
                                                    $assets=$con->myQuery($asset_sql,$inputs)->fetchAll(PDO::FETCH_ASSOC);
                                                }
                                                else{
                                                    $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);
                                                }
                                                
                                            }
                                            else{
                                                if($_GET['status']!="Deployed"){
                                                    $assets=$con->myQuery($asset_sql." AND asset_status_label=?",array($_GET['status']))->fetchAll(PDO::FETCH_ASSOC);
                                                }
                                                else{
                                                 $assets=$con->myQuery($asset_sql." AND check_out_date<>'0000-00-00'")->fetchAll(PDO::FETCH_ASSOC);   
                                                }
                                            }

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=="check_out_date" || $key=="expected_check_in_date" || $key=="purchase_date" || $key=="eol"):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if($value!="0000-00-00" && $value!="00/00/0000"){
                                                                echo htmlspecialchars($value);                                                                
                                                            }
                                                        ?>
                                                    </td>
                                                <?php
                                                    elseif($key=="asset_tag"):
                                                ?>
                                                    <td>
                                                        <?php echo htmlspecialchars($value)?>
                                                    </td>
                                                <?php
                                                    elseif($key=="asset_status_label"):
                                                ?>
                                                        <td>
                                                            <?php
                                                                if($asset['check_out_date']!="0000-00-00" && $asset['check_out_date']!="00/00/0000"){
                                                                    echo "Deployed (".htmlspecialchars($asset['current_holder']).")";
                                                                }
                                                                else{
                                                                    echo htmlspecialchars($value);
                                                                }
                                                            ?>
                                                        </td>
                                                <?php
                                                    elseif($key=="depreciation_term"):
                                                ?>
                                                        <td>
                                                            <?php
                                                                if(!empty($value)){

                                                                echo date_format( new DateTime(getDepriciationDate($asset['purchase_date'],$value)),"d/m/Y");
                                                                }
                                                            ?>
                                                        </td>
                                                <?php
                                                    elseif($key=="asset_status" || $key=="current_holder" || $key=="id"):
                                                        #skipped keys
                                                    else:
                                                ?>
                                                    <td>
                                                        <?php
                                                            echo htmlspecialchars($value);
                                                        ?>
                                                    </td>
                                                <?php
                                                    endif;
                                                    endforeach;
                                                ?>
                                            </tr>
                                        <?php
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY":"400px",
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span style='color:#fff'><span class='fa fa-download'></span> Download Excel </span>",
                        className:"btn btn-success btn-flat",
                        "extension":".xls"
                    }
                    ]
        });
        
        $('#departments').select2({
          allowClear:true
        });
        $('#users').select2({
          allowClear:true
        });
    });

    function getUsers() {
        // console.log($("#departments").val());
        $("#users").val(null).trigger("change"); 
        $("#users").load("ajax/cb_users.php?d_id="+$("#departments").val());
    }
    </script>
<?php
    Modal();
	makeFoot();
?>