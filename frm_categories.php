<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $category=$con->myQuery("SELECT categories.id,categories.name,category_types.name as asset_type,category_type_id FROM `categories` JOIN category_types ON categories.category_type_id=category_types.id WHERE categories.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($category)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Category Selected");
            redirect("categories.php");
            die();
        }
    }

    $category_types=$con->myQuery("SELECT id,name FROM category_types")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Categories");
?>

<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>

  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class='page-header text-center text-green'>
            Categories
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                                            <div class='row'>
                            <div class='col-sm-12 col-md-8 col-md-offset-2'>
                                <form class='form-horizontal' method='POST' action='save_category.php'>
                                    <input type='hidden' name='id' value='<?php echo !empty($category)?$category['id']:""?>'>
                                    
                                    <div class='form-group'>
                                        <label class='col-sm-12 col-md-3 control-label'> Category Name</label>
                                        <div class='col-sm-12 col-md-9'>
                                            <input type='text' class='form-control' name='name' placeholder='Enter Category Name' value='<?php echo !empty($category)?$category['name']:"" ?>' required>
                                        </div>
                                    </div>

                                    <div class='form-group'>
                                        <label class='col-sm-12 col-md-3 control-label'> Category Type</label>
                                        <div class='col-sm-12 col-md-9'>
                                            <select class='form-control' name='category_type_id' data-placeholder="Select a Category" <?php echo!(empty($category))?"data-selected='".$category['category_type_id']."'":NULL ?> required>
                                                <?php
                                                    echo makeOptions($category_types);
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    

                                    <div class='form-group'>
                                        <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                            <a href='categories.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($category)?'Are you sure you want to cancel the modification of the category?':'Are you sure you want to cancel the creation of the new category?';?>')">Cancel</a>
                                            <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                        </div>
                                        
                                    </div>
                                    
                                </form>
                            </div>
                        </div>

                </div>
            </div>
        </section><!-- /.content -->
  </div>
<?php
Modal();
?>
<?php
	makeFoot();
?>