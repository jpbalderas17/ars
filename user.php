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
        <h1 class='page-header text-center text-brand'>
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
                                <a href='frm_users.php' class='btn btn-brand btn-flat pull-right'> <span class='fa fa-plus'></span> Create New</a>
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