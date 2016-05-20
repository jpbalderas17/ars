<header class="main-header">

        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src='dist/img/sgtsi favico.png' /></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SGTSI</b> ARS</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle visible-xs-inline" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              
             <li><a href='' id='date_time'><?php echo date('F d, Y l h:i A');?></a></li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
                    $image="dist/img/user_male.png";
                  ?>
                  <img src="<?php echo $image;?>" class="user-image" alt="User Image">
                  <span class="hidden-xs">
                    <?php
                        echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                      ?>
                  </span>
                </a>

                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $image;?>" class="img-circle" alt="User Image">
                    <p>
                      <?php
                        echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                      ?>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="view_user.php?id=<?php echo $_SESSION[WEBAPP]['user']['id']?>" class="btn btn-brand btn-flat">User Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-brand btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>
