<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    if(!AllowUser(array(1))){
        redirect("index.php");
    }
    makeHead("View Users");
?>

<?php
     require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>
            Current Users
        </h1>
    </div>
    <section class='content'>
         <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_users.php' class='btn btn-success btn-flat pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Name</th>
                                            <th class='text-center'>Username</th>
                                            <th class='text-center'>Email</th>
                                            <th class='text-center'>Contact Number</th>
                                            <th class='text-center'>Employee Number</th>
                                            <th class='text-center'>Title</th>
                                            <th class='text-center'>Department</th>
                                            <th class='text-center' style='max-width: 160px;width: 160px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $users=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,title,department,id,is_active FROM qry_users")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach (array() as $user):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($user as $key => $value):
                                                    if($key=='name'):
                                                ?>
                                                    <td>
                                                        <a href='view_user.php?id=<?= $user['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                    </td>
                                                <?php
                                                    elseif($key=='is_active'):
                                                ?>
                                                <?php
                                                    elseif($key=='id'):
                                                ?>
                                                    <td>
                                                        <a class='btn btn-flat btn-sm btn-success' href='frm_users.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=u' onclick='return confirm("This user will be deleted.")'><span class='fa fa-trash'></span></a>
                                                    </td>
                                                <?php
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
                "processing": true,
                "serverSide": true,
                "ajax": "ajax/user.php"
        });

    });
    </script>
<?php
    makeFoot();
?>