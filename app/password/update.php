<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }
  
  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/account.php");

  if (isset($_POST["submit"])) {
    $old_password = $_POST["old-password"];
    $new_password = $_POST["new-password"];
    $confirm_password = $_POST["confirm-password"];

    if ($new_password != $confirm_password) {
      $_SESSION["toastr"] = array("type" => "error", "message" => "Password does not matched.");
    } else {
      $obj = new Account();
      $account = $obj->getAccountById($_SESSION["account_id"]);
      
      if ($account["password"] != $old_password) {
        $_SESSION["toastr"] = array("type" => "error", "message" => "Invalid password!");
      } else {
        $obj = new Account();
        $obj->updatePassword($_SESSION["account_id"], $new_password);
        
        $_SESSION["toastr"] = array("type" => "success", "message" => "Password has been changed.");
        header("location: ../dashboard/index.php");
        exit();
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment and Clearance and 201 Files</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <!-- Custom style -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include("../../app/components/navbar.php"); ?>
    <!-- Sidebar -->
    <?php include("../../app/components/sidebar.php"); ?>

    <!-- Content -->
    <div class="content-wrapper">
      <!-- Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Change Password</h1>
            </div>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="content">
        <div class="container-fluid">
          <form id="account-form" name="account-form" action="#" method="post">
            <div class="row">
              <div class="col-md-4 offset-md-4">
                <div class="card flex-fill">
                  <div class="card-header">
                    <h4 class="card-title">Setting Password</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Old Password</label>
                          <input name="old-password" type="password" class="form-control">
                        </div>
                        <div class="form-group">
                          <label>New Password</label>
                          <input name="new-password" type="password" class="form-control">
                        </div>
                        <div class="form-group">
                          <label>Confirm Password</label>
                          <input name="confirm-password" type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4"></div>
              <div class="col-md-12">
                <button name="submit" type="submit" class="btn btn-success">Save</button>
                <a href="#" onclick="window.history.back(); return false;" class="btn btn-secondary ml-2">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php include("../../app/components/footer.php"); ?>
  </div>

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Toastr -->
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
      <?php
        if (isset($_SESSION["toastr"])) {
          echo "toastr." . $_SESSION["toastr"]["type"] . "('" . $_SESSION["toastr"]["message"] . "')";
          unset($_SESSION["toastr"]);
        }
      ?>
    });
  </script>
</body>
</html>