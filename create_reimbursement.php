<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }

	$organization="";
    $a="";
    
    if(!empty($_GET['id']))
    {
        $getReimbursement=$con->myQuery("SELECT 
                                        r.payee,
                                        r.goods_services,
                                        r.description,
                                        r.transaction_date,
                                        r.amount,
                                        r.or_number,
                                        r.invoice_number,
                                        r.id
                                        from reimbursements r
                                        where r.is_deleted=0
                                        and r.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

                                        $getAttachments=$con->myQuery("SELECT
                                        file_name,
                                        DATE_FORMAT(date_added, '%m/%d/%Y') as date_added,
                                        file_location,
                                        id
                                        from files
                                        where is_deleted=0
                                        and reimbursement_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

    }

	makeHead("Reimbursement Creation");
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
        <section class="content-header ">
          <h1 class="page-header text-center text-brand">
            Reimbursement Creation
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

              <div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form name='frm_r' class='form-horizontal' method='POST' action='save_reimbursement.php' enctype="multipart/form-data">
                                <input type='hidden' name='r' id='r' value='<?php echo !empty($_GET['r'])?$_GET["r"]:""?>'>
                                <input type='hidden' name='id' id='id' value='<?php echo !empty($getReimbursement)?$getReimbursement["id"]:""?>'>
                                <input type='hidden' name='countFiles' id='countFiles' value='<?php echo !empty($getAttachments)?count($getAttachments):""; count($getAttachments); ?>'>

                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Name of Payee*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="payee" placeholder="Enter Name Of Payee" value="<?php echo !empty($getReimbursement)?$getReimbursement["payee"]:"" ?>" required>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Transaction*</label>
                                    <div class='col-sm-12 col-md-9'>
                                       
                                        <select class='form-control' name='expense_type' id='expense_type' data-placeholder="Select Transaction" <?php echo!(empty($getReimbursement))?"data-selected='".$getReimbursement['goods_services']."'":NULL ?> style='width:100%' required>
                                                <option value="1">Services</option>
                                                <option value="2">Goods</option>
                                                <option value="3">Goods/Services</option>
                                                    <?php
                                                        //echo makeOptions($user_type,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description of Expense*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class="form-control" name="description" placeholder="Enter Description Of Expense" required><?php echo !empty($getReimbursement)?$getReimbursement["description"]:"" ?></textarea>
                                    </div>
                                </div>

                                <?php
                                    if (!empty($_GET['r'])) 
                                    {
                                        $a='readonly';
                                    }
                                ?>


                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Transaction Date*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                        $start_date="";
                                         if(!empty($maintenance)){
                                            $start_date=$maintenance['start_date'];
                                            if($start_date=="0000-00-00"){
                                                $start_date="";
                                            }
                                         }
                                        ?>
                                        <input type='date' class='form-control' name='transaction_date'  value='<?php echo !empty($getReimbursement)?$getReimbursement['transaction_date']:"" ?>' required <?php echo $a;?>>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Amount*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control unsigned_integer' name='amount' placeholder='Enter Amount' value='<?php echo !empty($getReimbursement)?$getReimbursement['amount']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> OR Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control numeric' name='or_number' placeholder='Enter OR Number' value='<?php echo !empty($getReimbursement)?$getReimbursement['or_number']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group' id='invoicediv' style="display: none;">
                                    <label class='col-sm-12 col-md-3 control-label'> Invoice Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control numeric' name='invoice_number' placeholder='Enter Invoice Number' value='<?php echo !empty($getReimbursement)?$getReimbursement['invoice_number']:"" ?>' >
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Attachment*</label>
                                    <div class='col-sm-12 col-md-9'>
                                         <input type='file' multiple="" name='file[]' class="filestyle" data-classButton="" data-buttonName="btn btn-flat btn-default" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Select Files" >
                                    </div>
                                </div>
                      </div>
                  </div><!-- /.row -->
                  <?php
                    $var='';
                    if(!empty($_GET['id']))
                    {
                       
                    }
                    else
                    {
                        $var="style='display:none;'";
                    }
                    //var_dump($getAttachments);
                      ?>
                            <div class='form-group' id='tblAttachments' <?php echo $var?>>
                             <h4 class='col-sm-12 col-md-6 col-md-offset-3'>Attachments</h4>  
                                <div class='col-sm-12 col-md-6 col-md-offset-3 text-center'>
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
                                                                
                                                        <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $data?>&t=att&reim=<?php echo !empty($getReimbursement)?$getReimbursement["id"]:""?>' onclick='return confirm("This attachment will be deleted.")'><span class='fa fa-trash'></span></a>
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

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-6 col-md-offset-3 text-center'>

                                    <?php 
                                        if (empty($_GET['r'])) 
                                        {
                                    ?>
                                        <button type='submit' name='save' value='draft' class='btn btn-brand btn-flat'> <span class='fa fa-save'></span> Save as Draft</button>

                                    <?php
                                        }
                                    ?>
                                        <button type='submit' name='save' value='save' class='btn btn-brand btn-flat'> <span class='fa fa-check'></span> Submit</button>

                                        <button type='button' class='btn btn-flat btn-default' onclick="if(confirm('Are you sure you want to cancel?')){history.back();}"> Cancel</button>
                                    </div>
                                    
                                </div>                      
                    
                </form>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
    function val (frm) 
    {     
        var sv = document.forms["frm_r"]["save"].value;
        if(sv=="save")
        {
          alert("bawal");
          return false;
        }
        return true;
    }

 var dttable="";
      $(document).ready(function() {
        dttable=$('#dataTables').DataTable({
                "scrollY":"100%",
                "scrollX":"100%",
                "searching": false,
                "lengthChange":false,
                "language": {
                    "zeroRecords": "Reimbursement not found"
                }
                
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
 $(document).ready(function(){
    $('#expense_type').on('change', function() {
      if ( this.value == '2')
      //.....................^.......
      {
        $("#invoicediv").show();
      }
      else
      {
        $("#invoicediv").hide();
      }
    });
});

$(document).ready(function() {
    if($("#id").val() == ""){
        $("#tblAttachments").hide();
    }
    else{
         $("#tblAttachments").show();
    }
});
</script>

<?php
	makeFoot();
?>