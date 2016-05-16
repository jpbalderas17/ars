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

	makeHead("Asset Maintenance Report");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Asset Maintenance Report</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
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
                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <tr>
                                                <th>Asset Tag</th>
                                                <th>Asset Name</th>    
                                                <th>Maintenance Title</th>
                                                <th>Maintenance Type</th>
                                                <th>Maintenance Cost</th>
                                                <th class='date-td'>Start Date</th>
                                                <th class='date-td'>Completion Date</th>
                                                <th>Notes</th>
                                            </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $date_filter="";
                                            if(!empty($date_start)){
                                                $date_filter.=" AND asset_maintenances.start_date >= '".date_format($date_start,'Y-m-d')."'";
                                            }

                                            if(!empty($date_end)){
                                                $date_filter.=" AND asset_maintenances.start_date <= '".date_format($date_end,'Y-m-d')."'";
                                            }

                                            $asset_sql="SELECT asset_maintenances.id,assets.asset_name,assets.asset_tag,asset_maintenances.asset_id,asset_maintenance_types.name as maintenance_type,asset_maintenances.title,DATE_FORMAT(asset_maintenances.start_date,'%m/%d/%Y') as start_date,DATE_FORMAT(asset_maintenances.completion_date,'%m/%d/%Y')as completion_date,asset_maintenances.cost,asset_maintenances.notes FROM `asset_maintenances` JOIN asset_maintenance_types ON asset_maintenances.asset_maintenance_type_id=asset_maintenance_types.id JOIN assets ON assets.id=asset_maintenances.asset_id WHERE asset_maintenances.is_deleted=0 {$date_filter} ORDER BY asset_maintenances.start_date";
                                            $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($asset['asset_tag'])?></td>
                                                <td><?php echo htmlspecialchars($asset['asset_name'])?></td>
                                                <td><?php echo htmlspecialchars($asset['title'])?></td>
                                                <td><?php echo htmlspecialchars($asset['maintenance_type'])?></td>
                                                <td><?php echo htmlspecialchars(number_format($asset['cost'],2))?> </td>
                                                <td><?php echo $asset['start_date']?></td>
                                                <td><?php echo $asset['completion_date']=="00/00/0000"?"":$asset['completion_date'];?></td>
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