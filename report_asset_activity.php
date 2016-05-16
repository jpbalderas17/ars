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

	makeHead("Asset Activity Reports");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-green'>Asset Activity Report</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-sm-12'>
                      <form method='get'>
                      <label class='col-md-2 text-right' >Start Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='date_start' class='form-control' id='date_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                      </div>
                      <label class='col-md-2 text-right' >End Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='date_end' class='form-control' id='date_end' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                      </div>
                      <div class='col-md-2'>
                        <button type='submit'  class='btn-flat btn btn-success' >Filter</button>
                      </div>
                      </form>
                    </div>
                    <br/>
                    <br/>
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <tr>    
                                                <th class='date-td text-center'>Date</th>
                                                <th class='text-center'>Asset Tag</th>
                                                <th class='text-center'>Asset Name</th>
                                                <th class='text-center'>Admin</th>
                                                <th class='text-center'>Actions</th>
                                                <th class='text-center'>User</th>
                                                <th class='text-center'>Notes</th>
                                            </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $date_filter="";
                                            if(!empty($date_start)){
                                                $date_filter.=" AND action_date >= '".date_format($date_start,'Y-m-d')."'";
                                            }

                                            if(!empty($date_end)){
                                                $date_filter.=" AND action_date <= '".date_format($date_end,'Y-m-d')."'";
                                            }

                                            $asset_sql="SELECT DATE_FORMAT(action_date,'%m/%d/%Y')as action_date,activities.item_id,assets.asset_tag,assets.asset_name,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=activities.user_id)as user,action,activities.notes FROM activities JOIN assets ON assets.id=activities.item_id WHERE category_type_id=1 {$date_filter} ORDER BY activities.action_date";
                                            $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($asset['action_date'])?></td>
                                                <td><?php echo htmlspecialchars($asset['asset_tag'])?></td>
                                                <td><?php echo htmlspecialchars($asset['asset_name'])?></td>
                                                <td><?php echo htmlspecialchars($asset['admin'])?></td>
                                                <td><?php echo htmlspecialchars($asset['action'])?></td>
                                                <td><?php echo htmlspecialchars($asset['user'])?></td>
                                                <td><?php echo htmlspecialchars($asset['notes'])?></td>
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
                        extend:"csv",
                        text:"<span class='fa fa-download'></span> Download CSV ",
                        extend:"excel",
                        text:"<span style='color:#fff'><span class='fa fa-download'></span> Download Excel </span>",
                        className:"btn btn-success btn-flat",
                        "extension":".xls"
                    }
                    ]
        });

    });
    </script>
<?php
    Modal();
	makeFoot();
?>