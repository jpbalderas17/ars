<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $department=$con->myQuery("SELECT id,name,address FROM locations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Location Selected.");
            redirect("locations.php");
        }
    }
	makeHead("Locations");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-green'>Locations</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>

                    <div class='row'>
                                    <div class='col-md-12'>
                                        <div class='row'>
                                            <div class='col-sm-12'>
                                                <div class='align-center'>
                                                <form class='form-horizontal' method='POST' action='save_locations.php'>
                                                    <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                                        
                                                        <div class='form-group'>

                                                            <label class=' control-label col-md-2'> Location Name</label>
                                                                <div class='col-md-3'>
                                                                    
                                                                <input type='text' class='form-control' name='name' placeholder='Enter Location' value='<?php echo !empty($department)?$department['name']:"" ?>' required>
                                                                </div>
                                                            <label class=' control-label col-md-2'> Address:</label>
                                                            <div class='col-md-3'>
                                                                <input type='text' class='form-control' name='address' value='<?php echo !empty($department)?$department['address']:""?>' placeholder='Enter Address'>
                                                            </div>
                                                            <div class='col-md-2'>
                                                                <a href='locations.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($department)?'Are you sure you want to cancel the modification of this location?':'Are you sure you want to cancel the creation of the new location?';?>')">Cancel</a>
                                                                    <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                                            </div>
                                                        </div>

                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>    

                                    <div class='panel panel-default'>
                        
                                                <div class='panel-body ' >
 
                                                    <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                        <thead>
                                            <tr>
                                                <th class='text-center'>Location Name</th>
                                                <th class='text-center'>Address</th>
                                                <th class='text-center' style="max-width: 60px;width: 60px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $categories=$con->myQuery("SELECT id,name,address FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($categories as $category):
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($category['name'])?></td>
                                                    <td><?php echo htmlspecialchars($category['address'])?></td>
                                                    <td>
                                                        <a class='btn btn-flat btn-sm btn-success' href='locations.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=l' onclick='return confirm("Are you sure you want to delete this location?")'><span class='fa fa-trash'></span></a>
                                                    </td>
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
            </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
            "scrollY":"400px",
           "language": {
                "zeroRecords": "Locations not found"
            }
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>