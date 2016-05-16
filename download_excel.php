<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
// header("Content-Disposition: attachment; filename=\"revenue_report.xls\"");
// header("Content-Type: application/vnd.ms-excel");

	if(!empty($_GET['report'])){
		switch ($_GET['report']) {
			case 'report_asset':
				?>
				<table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th>Asset Tag</th>
                                            <th>Serial Number</th>
                                            <th>Asset Name</th>
                                            <th>Manufacturer</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>EOL</th>
                                            <th>Notes</th>
                                            <th>Order Number</th>
                                            <th>Checkout Date</th>
                                            <th>Expected Checkin Date</th>
                                            <th>Purchase Date</th>
                                            <th>Depreciation Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        	$filter=" AND (asset_tag=:filter OR serial_number=:filter OR asset_name=:filter OR manufacturer=:filter OR model=:filter OR asset_status_label=:filter OR location=:filter OR category=:filter OR eol=:filter OR notes=:filter OR order_number=:filter OR check_out_date=:filter OR expected_check_in_date=:filter OR purchase_date=:filter)";

                                            if(empty($_GET['status']) || $_GET['status']=='All'){
                                                $assets=$con->myQuery($asset_sql.$filter,$keyword)->fetchAll(PDO::FETCH_ASSOC);
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
                                                    if($key=="check_out_date" || $key=="expected_check_in_date" ):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if($value!="0000-00-00"){
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
                                                                if($asset['check_out_date']!="0000-00-00"){
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
                                                                echo date_format( getDepriciationDate($asset['purchase_date'],$value),"Y-m-d");
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
				<?php
				break;
			
			default:
				# code...
				break;
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>