<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }

    if(!empty($_GET['date_start'])){
    $date_start=date_create($_GET['date_start']);
  }
  else{
    $date_start="";
  }
  if(!empty($_GET['date_end'])){
    $date_end=date_create($_GET['date_end']);
  }
  else{
    $date_end="";
  }
    $departments=$con->myQuery("SELECT id,CONCAT('(',code,') ',name) as department FROM departments WHERE is_deleted=0 ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);
    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name) as user FROM users WHERE is_deleted=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
    //$expense_classifications=$con->myQuery("SELECT id,CONCAT('(',code,') ',name) as expense_classification FROM expense_classifications WHERE is_deleted=0 ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("Audit Reimbursements");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-brand'>Audit Reimbursements</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-sm-12'>
                      <form method='get' class='form-horizontal'>
                          <div class='form-group'>
                            <label class='col-md-3 text-right' >Start Date (Transaction Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_start' class='form-control' id='date_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                          </div>
                          <label class='col-md-3 text-right' >End Date (Transaction Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_end' class='form-control' id='date_end' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                          </div>
                      </div>
                       <div class='form-group'>
                          <label class='col-md-3 text-right' >Start Date (File Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_start_file' class='form-control' id='date_start_file' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                          </div>
                          <label class='col-md-3 text-right' >End Date (File Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_end_file' class='form-control' id='date_end_file' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                          </div>
                      </div>
                      <div class='form-group'>
                              
                          <label class='col-md-3 text-right' >Department</label>
                          <div class='col-md-3'>
                            <select class='form-control' name='department_id' data-placeholder='Filter By Department' onchange='getUsers()' style='width:100%'>
                            <?php
                                echo makeOptions($departments);
                            ?>
                            </select>
                          </div>
                          <label class='col-md-3 text-right' >Employee</label>
                          <div class='col-md-3'>
                            <select class='form-control' name='user_id' data-placeholder='Filter By User' style='width:100%'>
                            <?php
                                echo makeOptions($users);
                            ?>
                            </select>
                          </div>
                      </div>
                      <!-- <div class='form-group'>
                              
                          <label class='col-md-3 text-right' >Expense Classification</label>
                          <div class='col-md-3'>
                            <select class='form-control' name='expense_classification_id' data-placeholder='Select Expense Classification'>
                            <?php
                                echo makeOptions($expense_classifications);
                            ?>
                            </select>
                          </div>
                          
                      </div> -->
                      <div class='form-group'>
                          <div class='col-md-4 col-md-offset-4 text-right'>
                            <button type='button'  class='btn-flat btn btn-block btn-brand' onclick='filter_search()'>Filter</button>
                          </div>
                      </div>
                      </form>
                </div>
                <br/>
                <br/>
                <div class='clearfix'></div>
                <div class='col-md-12'>
                <div class='page-header'></div>
                </div>
                <div class='col-md-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='dataTable_wrapper '>
                        <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                            <thead>
                                <tr>
                                    <tr>    
                                        <th class='date-td text-center'>Transaction Date</th>
                                        <th class='date-td text-center'>Date Filed</th>
                                        <th class='text-center'>Requestor</th>
                                        <th class='text-center'>Department</th>
                                        <th class='text-center'>Payee</th>
                                        <th class='text-center'>Amount</th>
                                        <th class='text-center'>OR Number</th>
                                        <th class='text-center' style="min-width:150px">Description of Transaction</th>
                                        <th class='text-center'>Description of Expense</th>
                                        <th class='text-center' style="min-width:100px">Action</th>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
    </section>
</div>
<?php
    $return_page="reimbursements_audit.php";
    require_once('./include/modal_query.php');
    require_once('./include/modal_reject.php');
?>
<script>
    var dttable="";
      $(document).ready(function() {
        dttable=$('#dataTables').DataTable({
                "scrollY":"400px",
                "scrollX":"100%",
                "searching": false,
                "processing": true,
                "serverSide": true,
                "select":true,
                "ajax":{
                  "url":"ajax/reimbursements_audit.php",
                  "data":function(d){
                    d.start_date=$("input[name='date_start']").val();
                    d.end_date=$("input[name='date_end']").val();
                    d.start_date_file=$("input[name='date_start_file']").val();
                    d.end_date_file=$("input[name='date_end_file']").val();
                    d.department_id=$("select[name='department_id']").val();
                    d.user_id=$("select[name='user_id']").val();
                  }
                },"language": {
                    "zeroRecords": "Reimbursement not found"
                },
                order:[[0,'desc']]
                ,"columnDefs": [
                    { "orderable": false, "targets": [-1] }
                  ] 
        });
        $("select[name='user_id']").select2({
          allowClear:true
        });
        $("select[name='department_id']").select2({
          allowClear:true
        });
        $("select[name='expense_classification_id']").select2({
          allowClear:true
        });


        
    });

    function filter_search() {
            dttable.ajax.reload();
            //console.log(dttable);
    }

    function getUsers() {
        // console.log($("#departments").val());
        $("select[name='user_id']").val(null).trigger("change"); 
        $("select[name='user_id']").load("ajax/cb_users.php?d_id="+$("select[name='department_id']").val());
    }

    </script>
<?php
    Modal();
	makeFoot();
?>