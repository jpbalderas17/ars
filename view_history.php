<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }

    if(empty($_GET['id'])){
      redirect("index.php");
      die;
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

  function clearfix_insert(&$counter,$cols=2)
  {
    $counter++;
    if($counter==$cols){
      $counter=0;
      return true;
    }
      return false;
  }

  if(!empty($_GET['id'])){
        $reimbursement=$con->myQuery("SELECT * FROM vw_reimbursements WHERE id=? AND status <> 'Draft' LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($reimbursement)){
            Modal("Invalid reimbursement.");
            redirect("index.php");
            die;
        }
        else{
            if(AllowUser(array(3))){
              if($reimbursement['user_id']!=$_SESSION[WEBAPP]['user']['id']){
                redirect("index.php");
                die;
              }
            }

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

	makeHead("Reimbursement History");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-brand'>Reimbursement History</h1>
    </div>
    <section class='content'>
        <div class="row">
                
                
                <div class='col-sm-12'>
                      <div class='col-sm-12'>
                        <button class='btn btn-brand btn-flat' onclick='history.back()'><span class='fa fa-arrow-left'></span> Back</button>
                      </div>
                      <h4 class='col-sm-12 col-md-12 text-brand text-center'>Movement History</h4>
                      <form method='get' class='form-horizontal'>
                      <div class='form-group'>
                              
                          <label class='col-md-3 text-right' >Start Date (Action Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_start' class='form-control' id='date_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                          </div>
                          <label class='col-md-3 text-right' >End Date (Action Date)</label>
                          <div class='col-md-3'>
                            <input type='date' name='date_end' class='form-control' id='date_end' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                          </div>
                      </div>
                     
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
                                        <th class='date-td text-center'>Action Date</th>
                                        <!-- <th class='text-center'>Requestor</th>
                                        <th class='text-center'>Department</th> -->
                                        <th class='text-center'>User</th>
                                        <th class='text-center'>Action</th>
                                        <th class='text-center'>Notes</th>
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
                "scrollX":"100%",
                "searching": false,
                "processing": true,
                "serverSide": true,
                "select":true,
                "ajax":{
                  "url":"ajax/reimbursements_history.php",
                  "data":function(d){
                    d.start_date=$("input[name='date_start']").val();
                    d.end_date=$("input[name='date_end']").val();
                    d.department_id=$("select[name='department_id']").val();
                    d.user_id=$("select[name='user_id']").val();
                    <?php
                      if(!empty($_GET['id'])):
                    ?>
                      d.r_id='<?php echo intval($_GET['id']);?>';
                    <?php
                      endif;
                    ?>
                  }
                },"language": {
                    "zeroRecords": "Reimbursement not found"
                },
                order:[[0,'desc']]
                ,"columnDefs": [
                    { "orderable": false, "targets": [-1] }
                  ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span style='color:#fff'><span class='fa fa-download'></span> Download Excel </span>",
                        className:"btn btn-brand btn-flat",
                        "extension":".xls"
                    }
                    ]   
        });
       
        $("select[name='expense_classification_id']").select2({
          allowClear:true
        });

        $('#dataTablesFiles').DataTable({
            "searching":false
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