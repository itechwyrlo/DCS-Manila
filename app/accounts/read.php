<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/account.php");

  $obj = new Account();
  $account = $obj->getAccountById($_GET["account_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>

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
              <h1 class="m-0">View Account</h1>
            </div>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="content">
        <div class="container-fluid">
          <form action="#" method="post">
            <div class="row">
              <div class="col-md-3 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                      <div class="profile-container mt-2 mb-4">
                        <img id="avatar" src="<?php echo $account["avatar_path"]; ?>" alt="" class="rounded-circle img-thumbnail" width="130px">
                        <input id="upload" name="upload" type="file" capture>
                      </div>
                      <h4>Avatar</h4>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-9 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <h4 class="mb-2">Credentials</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email</label>
                          <input name="email" type="email" class="form-control" readonly value="<?php echo $account["email"]; ?>">
                        </div>
                        <div class="form-group">
                          <label>Role</label>
                          <input name="role" type="role" class="form-control" readonly value="<?php echo $account["role"]; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 d-flex">
                <div class="card card-primary flex-fill">
                  <div class="card-body">
                    <h4 class="mb-2">Personal Information</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>First Name</label>
                          <input name="first-name" type="text" class="form-control" readonly value="<?php echo $account["first_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Middle Name</label>
                          <input name="middle-name" type="text" class="form-control" readonly value="<?php echo $account["middle_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Last Name</label>
                          <input name="last-name" type="text" class="form-control" readonly value="<?php echo $account["last_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Suffix</label>
                          <input name="suffix" type="text" class="form-control" readonly value="<?php echo $account["suffix"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Birthdate</label>
                          <div id="birthdate-group" class="input-group date" data-target-input="nearest">
                            <input name="birthdate" type="text" class="form-control datetimepicker-input" data-target="#birthdate-group"
                              data-toggle="datetimepicker" readonly value="<?php echo date("m/d/Y", strtotime($account["birthdate"])); ?>" />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Contact No</label>
                          <input name="contact-no" type="text" class="form-control" readonly value="<?php echo $account["contact_no"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Position</label>
                          <input name="position" type="text" class="form-control" readonly value="<?php echo $account["position"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Employment Date</label>
                          <div id="employment-date-group" class="input-group date" data-target-input="nearest">
                            <input name="employment-date" type="text" class="form-control datetimepicker-input" data-target="#employment-date-group"
                              data-toggle="datetimepicker" readonly value="<?php echo date("m/d/Y", strtotime($account["employment_date"])); ?>" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-1">
                <a href="update.php?account_id=<?php echo $account["account_id"]; ?>" class="btn btn-block btn-primary
                <?php echo (($account['account_id'] == 1) ? ' disabled' : ''); ?>">Update</a>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#delete-modal" style="cursor: default;"
                  <?php echo (($_SESSION["account_id"] == $account["account_id"]) ? "disabled" : ""); ?>>Delete</button>
              </div>
            </div>
          </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="delete-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Delete Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this account?</p>
              </div>
              <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="delete.php?account_id=<?php echo $account["account_id"]; ?>" class="btn btn-danger">Delete</a>
              </div>
            </div>
          </div>
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