<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }
  
  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/ac201-record.php");
  include_once("../../core/repositories/rrm-record.php");
  include_once("../../core/repositories/rrm-record-tracking.php");
  include_once("../../core/repositories/status.php");

  $obj = new Status();
  $statuses = $obj->getStatuses();

  $obj = new Ac201Record();
  $record = $obj->getRecordById($_GET["record_id"]);

  if (isset($_POST["submit"])) {
    $status = $_POST["status"];
    $school = $_POST["school"];
    $remarks = $_POST["remarks"];

    $obj = new Status();
    $status = $obj->getStatusByName($status);

    if ($status["name"] == "Released") {
      $obj = new Ac201Record();
      $obj->releaseRecord($_GET["record_id"], $status["status_id"], $_SESSION["account_id"], $school, $remarks);
    } else {
      $obj = new Ac201Record();
      $obj->updateRecord($_GET["record_id"], $status["status_id"], $_SESSION["account_id"], $school, $remarks);
    }
    $rrmObj = new RrmRecord();
    $rrmObj->setStatus($_GET["record_id"], $status["status_id"]);
    $trackObj = new RrmRecordTracking();
    $trackObj->addRecord($_GET["record_id"], $remarks ?: "Status updated to " . $status["name"] . " (Appointment and Clearance / 201 Files).", $_SESSION["account_id"] ?? null);

    $_SESSION["toastr"] = array("type" => "success", "message" => "Record has been updated!");
    header("location: read.php?record_id=$_GET[record_id]");
    exit();
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
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
              <h1 class="m-0">Update Record</h1>
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
                          <label>Control No</label>
                          <input name="control-no" type="text" class="form-control" readonly value="<?php echo $record["control_no"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6"></div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Client Name</label>
                          <input name="client-name" type="text" class="form-control" readonly value="<?php echo $record["client_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Category</label>
                          <input name="category" type="text" class="form-control" readonly value="<?php echo $record["category"]; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                  <div class="card-header">
                    <h4 class="card-title">RRM Information</h4>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Documents</label>
                          <textarea name="documents" class="form-control" rows="2" style="resize: none;" readonly><?php echo $record["documents"]; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                  <div class="card-header">
                    <h4 class="card-title">AC-201 Information</h4>
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
                          <label>Status</label>
                          <select id="status" name="status" class="form-control select2bs4" style="width: 100%;">
                            <?php
                              $unused = array("Forwarded", "Returned");
                              foreach ($statuses as $item) {
                                if (in_array($item["name"], $unused)) continue;
                                if ($record["status"] == $item["name"]) {
                                  echo "<option selected>$item[name]</option>";
                                } else {
                                  echo "<option>$item[name]</option>";
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Handling Personnel</label>
                          <input name="personnel" type="text" class="form-control" readonly value="<?php echo $_SESSION["full_name"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>School</label>
                          <input name="school" type="text" class="form-control" value="<?php echo $record["school"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Released Date</label>
                          <input name="released-date" type="text" class="form-control" readonly 
                            value="<?php if ($record["released_date"] != null) echo date("m/d/Y h:i:s A", strtotime($record["released_date"])); ?>">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Remarks</label>
                          <textarea name="remarks" class="form-control" rows="2" style="resize: none;"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-2">
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
  <!-- Select2 -->
  <script src="../../plugins/select2/js/select2.full.min.js"></script>
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

      $("#status").select2({
        theme: "bootstrap4",
        placeholder: "Status"
      });
    });
  </script>
</body>
</html>