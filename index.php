<?php
  session_start();

  if (isset($_POST["submit"])) {
    $control_no = $_POST["control-no"];

    header("location: app/tracking/index.php?control_no=" . $control_no);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tracking</title>

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
<body class="hold-transition tracking-page">
  <div class="tracking-box position-absolute">
    <div id="tracking-card" class="card">
      <h1 class="text-center text-white">Track your document here</h1>
      <div id="tracking-card-body" class="card-body tracking-card-body">
        <form id="tracking-form" action="#" method="post">
          <div class="input-group">
            <input id="control-no" name="control-no" type="text" class="form-control form-control-lg" placeholder="Control No">
            <div class="input-group-append">
              <button name="submit" type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
              </button>
            </div>
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
  <!-- InputMask -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/js/adminlte.min.js"></script>
  <!-- Particles -->
  <script src="plugins/particles/js/particles.min.js"></script>
  <script src="plugins/particles/js/app.js"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
      $("#control-no").inputmask("AAA-9999-99-9999");

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