<?php
  require_once("support/config.php");
   if(!isLoggedIn()){
    toLogin();
    die();
   }
     if(!AllowUser(array(1,2))){
         redirect("index.php");
     }
  makeHead("Audit Log");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Audit Log
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
              <?php 
                Alert();
              ?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>User</th>
                              <th class='text-center'>Action</th>
                              <th class='text-center'>Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                          </tbody>
                        </table>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>
<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
                "scrollX": true,
                "ajax":"audit_log.txt",
                "dataSrc": "",
                "order": [[ 2, "desc" ]],
                "deferRender": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ]
                
        });
      });
</script>
<?php
  Modal();
  makeFoot();
?>