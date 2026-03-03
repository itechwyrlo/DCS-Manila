<?php
  session_start();

  include_once("core/repositories/database.php");
  include_once("core/repositories/account.php");

  if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $obj = new Account();
    $account = $obj->getAccountByEmail($email);
    
    if ($account == null) {
      $_SESSION["toastr"] = array("type" => "error", "message" => "Invalid credentials!");
    } else if ($account["password"] !== $password) {
      $_SESSION["toastr"] = array("type" => "error", "message" => "Invalid credentials!");
    } else {
      $_SESSION["account_id"] = $account["account_id"];
      $_SESSION["role"] = $account["role"];
      $_SESSION["display_name"] = $account["first_name"] . " " . $account["last_name"] . " " . $account["suffix"];
      $_SESSION["full_name"] = $account["full_name"];
      $_SESSION["avatar_path"] = $account["avatar_path"];
      header("location: app/dashboard/index.php");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Particles -->
  <link rel="stylesheet" href="plugins/particles/css/style.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <!-- Custom style -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="hold-transition login-page">
  <div class="login-box position-absolute">
    <div id="login-card" class="card">
      <div id="login-card-header" class="card-header mb-4">
        <div class="text-center">
          <img id="login-logo" src="assets/img/logo.png" class="img-fluid mb-4" alt="logo">
          <h2 class="text-light">DCS-M RMIS</h2>
        </div>
      </div>
      <div id="login-card-body" class="card-body login-card-body">
        <form id="login-form" action="#" method="post">
          <div class="form-group input-group">
            <input id="email" name="email" type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="form-group input-group">
            <input id="password" name="password" type="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group">
            <button name="submit" type="submit" class="btn btn-block btn-primary btn-lg">Login</button>
          </div>
          <div class="form-group">
            <a href="forgot-password.php" hidden>Forgot password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="particles-js"></div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- Particles -->
  <script src="plugins/particles/js/particles.min.js"></script>
  <script src="plugins/particles/js/app.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
      <?php
        if (isset($_SESSION["toastr"])) {
          echo "toastr." . $_SESSION["toastr"]["type"] . "('" . $_SESSION["toastr"]["message"] . "')";
          unset($_SESSION["toastr"]);
        }
      ?>

      $("#email").attr("maxlength", "100");
      $("#password").attr("maxlength", "50");
    });
  </script>
</body>
</html>