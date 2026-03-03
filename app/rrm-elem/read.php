<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }
  
  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/rrm-record.php");

  $obj = new RrmRecord();
  $record = $obj->getRecordById($_GET["record_id"]);

  $statDisabledUpdate = array("Released", "Cancelled");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receiving Routing and Mailing - Elementary</title>

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
              <h1 class="m-0">View Record</h1>
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
                      <div class="col-md-1 offset-md-5">
                        <div class="form-group">
                          <label>Queue No</label>
                          <input name="queue-no" type="text" class="form-control" readonly value="<?php echo $record["queue_no"]; ?>">
                        </div>
                      </div>
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
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Status</label>
                          <input name="status" type="text" class="form-control" readonly value="<?php echo $record["status"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Handling Personnel</label>
                          <input name="personnel" type="text" class="form-control" readonly value="<?php echo $record["personnel"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>On Holding Office</label>
                          <input name="office" type="text" class="form-control" readonly value="<?php echo $record["office"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>On Holding Section</label>
                          <input name="section" type="text" class="form-control" readonly value="<?php echo $record["section"]; ?>">
                        </div>
                      </div>
                      <div class="col-md-6 offset-md-6">
                        <div class="form-group">
                          <label>Released Date</label>
                          <input name="released-date" type="text" class="form-control" readonly
                            value="<?php if ($record["released_date"] != null) echo date("m/d/Y h:i:s A", strtotime($record["released_date"])); ?>">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Documents</label>
                          <textarea name="documents" class="form-control" rows="2" style="resize: none;" readonly><?php echo $record["documents"]; ?></textarea>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Remarks</label>
                          <textarea name="remarks" class="form-control" rows="2" style="resize: none;" readonly><?php echo $record["remarks"]; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-1">
                <a href="update.php?record_id=<?php echo $record["record_id"]; ?>" class="btn btn-block btn-primary
                  <?php echo ((in_array($record["status"], $statDisabledUpdate)) ? " disabled" : ""); ?>">Update</a>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#delete-modal"
                  <?php echo (($record["status"] != "Open") ? "disabled" : ""); ?>>Delete</button>
              </div>
            </div>
          </form>

          <!-- Modal -->
          <div class="modal fade" id="delete-modal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Delete Record</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer justify-content-end">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <a href="delete.php?record_id=<?php echo $record["record_id"]; ?>" class="btn btn-danger">Delete</a>
                </div>
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