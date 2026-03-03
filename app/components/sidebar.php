<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="../../app/dashboard/index.php" class="brand-link">
    <img src="../../assets/img/logo.png" alt="logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">DCS - Manila</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo $_SESSION["avatar_path"]; ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="../accounts/read.php?account_id=<?php echo $_SESSION["account_id"]; ?>" class="d-block"><?php echo $_SESSION["display_name"]; ?></a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
          // Accounts
          if ($_SESSION["role"] == "Administrator") { 
            echo "
            <li class='nav-item'>
              <a href='../accounts/index.php' class='nav-link'>
                <i class='nav-icon fas fa-users'></i>
                <p>Accounts</p>
              </a>
            </li>";
          }
          // Queuing
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Receiving Routing and Mailing") { 
            echo "
            <li class='nav-item'>
              <a href='../queuing/index.php' class='nav-link'>
                <i class='nav-icon fas fa-tachometer-alt'></i>
                <p>Queuing</p>
              </a>
            </li>";
          }
          // Administrative Issuance
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Administrative Issuance") { 
            echo "
            <li class='nav-item'>
              <a href='../ai/index.php' class='nav-link'>
                <i class='nav-icon fas fa-bookmark'></i>
                <p>Administrative Issuance</p>
              </a>
            </li>";
          }
          // Certification Authentication and Verification
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Certification Authentication and Verification") { 
            echo "
            <li class='nav-item'>
              <a href='../cav/index.php' class='nav-link'>
                <i class='nav-icon fas fa-certificate'></i>
                <p>Certification Authentication and Verification</p>
              </a>
            </li>";
          }
          // Appointment and Clearance / 201 Files
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Appointment and Clearances / 201 Files") { 
            echo "
            <li class='nav-item'>
              <a href='../ac201/index.php' class='nav-link'>
                <i class='nav-icon fas fa-file'></i>
                <p>Appointment and Clearance / 201 Files</p>
              </a>
            </li>";
          }
          // Numerical Files
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Numerical Files") { 
            echo "
            <li class='nav-item'>
              <a href='#' class='nav-link'>
                <i class='nav-icon fas fa-book'></i>
                <p>Numerical Files</p>
                <i class='fas fa-angle-left right'></i>
              </a>
              <ul class='nav nav-treeview'>
                <li class='nav-item'>
                  <a href='../num-comm/index.php' class='nav-link'>
                    <i class='far fa-circle nav-icon'></i>
                    <p>Communication</p>
                  </a>
                </li>
                <li class='nav-item'>
                  <a href='../num-others/index.php' class='nav-link'>
                    <i class='far fa-circle nav-icon'></i>
                    <p>Others</p>
                  </a>
                </li>
              </ul>
            </li>";
          }
          // Receiving Routing and Mailing
          if ($_SESSION["role"] == "Administrator" || $_SESSION["role"] == "Receiving Routing and Mailing") { 
            echo "
            <li class='nav-item'>
              <a href='#' class='nav-link'>
                <i class='nav-icon fas fa-envelope'></i>
                <p>Receiving Routing and Mailing</p>
                <i class='fas fa-angle-left right'></i>
              </a>
              <ul class='nav nav-treeview'>
                <li class='nav-item'>
                  <a href='../rrm-elem/index.php' class='nav-link'>
                    <i class='far fa-circle nav-icon'></i>
                    <p>Elementary</p>
                  </a>
                </li>
                <li class='nav-item'>
                  <a href='../rrm-hs/index.php' class='nav-link'>
                    <i class='far fa-circle nav-icon'></i>
                    <p>HighSchool</p>
                  </a>
                </li>
              </ul>
            </li>";
          }
          // Transaction History (Admin only)
          if ($_SESSION["role"] == "Administrator") {
            echo "
            <li class='nav-item'>
              <a href='../transaction-history/index.php' class='nav-link'>
                <i class='nav-icon fas fa-history'></i>
                <p>Transaction History</p>
              </a>
            </li>";
          }
          // Audit Log (Admin only)
          if ($_SESSION["role"] == "Administrator") {
            echo "
            <li class='nav-item'>
              <a href='../audit-log/index.php' class='nav-link'>
                <i class='nav-icon fas fa-clipboard-list'></i>
                <p>Audit Log</p>
              </a>
            </li>";
          }
        ?>
      </ul>
    </nav>
  </div>
</aside>