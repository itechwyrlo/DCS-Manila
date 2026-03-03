<?php
  session_start();

  include_once("core/repositories/database.php");
  include_once("core/repositories/account.php");

  if (isset($_POST["submit"])) {
    $email = $_POST["email"];

    $obj = new Account();
    $account = $obj->getAccountByEmail($email);
    
    if ($account == null) {
      $_SESSION["toastr"] = array("type" => "error", "message" => "Invalid email!");
    } else {
      
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  
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
<body class="hold-transition register-page">
  <div class="register-box position-absolute">
    <div id="register-card" class="card">
      <div id="register-card-header" class="card-header" style="border: none;">
        <div class="text-center">
          <h2>Forgot Password</h2>
          <span>Enter your email address</span>
        </div>
      </div>
      <div id="register-card-body" class="card-body register-card-body">
        <form id="register-form" action="#" method="post">
          <div class="form-group input-group">
            <input id="email" name="email" type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group">
            <button name="submit" type="submit" class="btn btn-block btn-primary btn-lg">Continue</button>
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
    });
  </script>
</body>
</html>