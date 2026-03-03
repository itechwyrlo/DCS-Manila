<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/record.php");
  include_once("../../core/repositories/rrm-record.php");
  include_once("../../core/repositories/rrm-record-tracking.php");
  include_once("../../core/repositories/control-no.php");
  include_once("../../core/repositories/category.php");
  include_once("../../core/repositories/queue-no.php");
  include_once("../../core/repositories/queuing.php");
  
  $obj = new Category();
  $category = $obj->getCategoryById(2);

  if (isset($_POST["submit"])) {
    $client_name = $_POST["client-name"];

    $obj = new ControlNo();
    $control_no = $obj->getControlNoById(1);

    $obj = new ControlNo();
    $obj->updateControlNo($control_no["control_id"], $control_no["value"]);

    $obj = new Record();
    $record_id = $obj->addRecord($client_name, $control_no["value"], $category["category_id"]);
    
    $obj = new RrmRecord();
    $obj->addRecord($record_id);
    
    $message = "Request with Control No. " . $control_no["value"] . " has been created";
    $obj = new RrmRecordTracking();
    $obj->addRecord($record_id, $message, $_SESSION["account_id"] ?? null);

    $obj = new QueueNo();
    $queue = $obj->getQueueNoByCategory(2);
    $modified_date = date("Y-m-d", strtotime(str_replace("-", "/", $queue["modified_date"])));

    $counter = 0;
    if ($modified_date >= date("Y-m-d")) {
      $counter = $queue["counter"] + 1;
    } else {
      $counter = 1;
    }
  
    $obj = new QueueNo();
    $obj->updateQueueNo($queue["queue_id"], 0, 0);
    $obj->updateQueueNo($queue["queue_id"], $counter, $counter);

    $obj = new Queuing();
    $obj->addQueue($record_id, $counter);

    $_SESSION["toastr"] = array("type" => "success", "message" => "Record has been created!");
    header("location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receiving Routing and Mailing - Highschool</title>

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
              <h1 class="m-0">Request Record</h1>
            </div>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="content">
        <div class="container-fluid">
          <form id="record-form" name="record-form" action="#" method="post">
            <div class="row">
              <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                  <div class="card-header">
                    <h4 class="card-title">Request Information</h4>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Client Name</label>
                          <input name="client-name" type="text" class="form-control" placeholder="Client Name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Category</label>
                          <input name="category" type="text" class="form-control" readonly value="<?php echo $category["name"]; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-1">
                <button name="submit" type="submit" class="btn btn-block btn-success">Save</button>
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
  <!-- Jquery Validation -->
  <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
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
      
      $("#client-name").attr("maxlength", "150");

      $.validator.addMethod("lettersandcharacters", function(value, element) {
        return /^[^0-9]*$/i.test(value);
      });
      
      $("#record-form").validate({
        rules: {
          "client-name": {
            required: true,
            lettersandcharacters: true
          },
          "details": {
            required: true
          }
        },
        messages: {
          "client-name": {
            required: "Client name is required",
            lettersandcharacters: "Client name should contain letters and special characters only"
          },
          "details": {
            required: "Details is required"
          }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
          error.addClass("invalid-feedback");
          element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass("is-invalid");
        }
      });
    });
  </script>
</body>
</html>