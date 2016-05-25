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
   $getDrafts=$con->myQuery("SELECT 
reimbursements.transaction_date, 
CONCAT(users.last_name ,', ', users.first_name ,' ', users.middle_name) as requestor, 
departments.name as department, 
reimbursements.payee, 
reimbursements.amount,
(CASE reimbursements.goods_services
when 1 then 'Services'
when 2 then 'Goods'
when 3 then 'Goods/Services'
END) as goods_services, 
reimbursements.description,
reimbursements.id
from reimbursements
inner JOIN users on reimbursements.user_id=users.id
inner join departments on users.department_id=departments.id
where reimbursements.status='For Audit'
OR reimbursements.status='For Approval'
AND reimbursements.is_deleted=0
ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("For Audit/Approval Reimbursements");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-brand'>For Audit/Approval Reimbursements</h1>
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
                                        <!-- <th class='date-td text-center'>Date Filed</th> -->
                                        <th class='text-center'>Transaction Date</th>
                                        <th class='text-center'>Requestor</th>
                                        <th class='text-center'>Department</th>
                                        <th class='text-center'>Payee</th>
                                        <th class='text-center'>Amount</th>
                                        <th class='text-center'>Description of Transaction</th>
                                        <th class='text-center'>Description of Expense</th>
                                        <th class='text-center'>Action</th>
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

<script>
    var dttable="";
      $(document).ready(function() {
        dttable=$('#dataTables').DataTable({
                "scrollY":"400px",
                "searching": false,
                "processing": true,
                "serverSide": true,
                "select":true,
                "ajax":{
                  "url":"ajax/reimbursements_drafts.php",
                  "data":function(d){
                    d.start_date=$("input[name='date_start']").val();
                    d.end_date=$("input[name='date_end']").val();
                    d.department_id=$("select[name='department_id']").val();
                    d.user_id=$("select[name='user_id']").val();
                  }
                },"language": {
                    "zeroRecords": "Reimbursement not found"
                },
                order:[[1,'desc']]
                ,"columnDefs": [
                    { "orderable": false, "targets": [-1] }
                  ] 
        });
       
        $("select[name='expense_classification_id']").select2({
          allowClear:true
        });


        
    });

    function filter_search() {
            dttable.ajax.reload();
            //console.log(dttable);
    }


    </script>
<?php
    Modal();
	makeFoot();
?>