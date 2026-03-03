<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block dropdown">
      <a href="#" class="nav-link" data-toggle="dropdown">
        <i class="far fa-user"></i>
        Profile
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header"><?php echo $_SESSION["display_name"]; ?></span>
        <div class="dropdown-divider"></div>
        <a href="../accounts/read.php?account_id=<?php echo $_SESSION["account_id"]; ?>" class="dropdown-item">
          <i class="fas fa-user mr-2"></i>
          Profile
        </a>
        <a href="../password/update.php" class="dropdown-item">
          <i class="fas fa-lock mr-2"> </i>
          Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="../../logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"> </i>
          Logout
        </a>
      </div>
    </li>
  </ul>
</nav>