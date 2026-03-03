<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }
  
  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/rrm-record.php");
  include_once("../../core/repositories/rrm-record-tracking.php");
  include_once("../../core/repositories/ai-record.php");
  include_once("../../core/repositories/ac201-record.php");
  include_once("../../core/repositories/cav-record.php");
  include_once("../../core/repositories/num-record.php");
  include_once("../../core/repositories/status.php");
  include_once("../../core/repositories/office.php");
  include_once("../../core/repositories/section.php");
  include_once("../../core/repositories/memorandum-no.php");

  $obj = new Status();
  $statuses = $obj->getStatuses();
  
  $obj = new Office();
  $offices = $obj->getOffices();
  
  $obj = new Section();
  $sections = $obj->getSections();
  
  $obj = new RrmRecord();
  $record = $obj->getRecordById($_GET["record_id"]);

  if (isset($_POST["submit"])) {
    $status = $_POST["status"];
    $office = $_POST["office-selected"];
    $section = $_POST["section-selected"];
    $documents = $_POST["documents"];
    $remarks = $_POST["remarks"];

    $obj = new Status();
    $status = $obj->getStatusByName($status);

    $obj = new Office();
    $office = $obj->getOfficeByName($office);

    $obj = new Section();
    $section = $obj->getSectionByName($section);

    if ($status["name"] == "Released") {
      $obj = new RrmRecord();
      $obj->releaseRecord($_GET["record_id"], $status["status_id"], $office["office_id"], $section["section_id"], $_SESSION["account_id"], $documents, $remarks);
    } else {
      $obj = new RrmRecord();
      $obj->updateRecord($_GET["record_id"], $status["status_id"], $office["office_id"], $section["section_id"], $_SESSION["account_id"], $documents, $remarks);
    }
    
    $obj = new RrmRecordTracking();
    $obj->addRecord($_GET["record_id"], $remarks, $_SESSION["account_id"] ?? null);

    $record_id = $_GET["record_id"];
    $status_id = $status["status_id"];

    // Sync status to every section that has this record (so 201/AI/Num/CAV dashboards show current status even when RRM changes section back to RRM)
    $objAc201 = new Ac201Record();
    if ($objAc201->hasRecord($record_id)) $objAc201->updateStatus($record_id, $status_id);
    $objAi = new AiRecord();
    if ($objAi->hasRecord($record_id)) $objAi->updateStatus($record_id, $status_id);
    $objCav = new CavRecord();
    if ($objCav->hasRecord($record_id)) $objCav->updateStatus($record_id, $status_id);
    $objNum = new NumRecord();
    if ($objNum->hasRecord($record_id)) $objNum->updateStatus($record_id, $status_id);

    switch ($section["name"]) {
      case "Administrative Issuance":
        $obj = new AiRecord();
        if (!$obj->hasRecord($record_id)) {
          $objMemo = new MemorandumNo();
          $memorandum_no = $objMemo->getMemorandumNoById(1);
          $objMemo->updateMemorandumNo($memorandum_no["memorandum_id"], $memorandum_no["value"]);
          $obj->addRecord($record_id, 1, $memorandum_no["value"]);
        }
        break;
      case "Appointment and Clearances / 201 Files":
        $obj = new Ac201Record();
        if (!$obj->hasRecord($record_id)) {
          $obj->addRecord($record_id, 1);
        }
        break;
      case "Certification Authentication and Verification":
        $obj = new CavRecord();
        if (!$obj->hasRecord($record_id)) {
          $obj->addRecord($record_id, 1);
        }
        break;
      case "Numerical - Communication":
        $obj = new NumRecord();
        if (!$obj->hasRecord($record_id)) {
          $obj->addRecord($record_id, 1, 1);
        }
        break;
      case "Numerical - Others":
        $obj = new NumRecord();
        if (!$obj->hasRecord($record_id)) {
          $obj->addRecord($record_id, 1, 2);
        }
        break;
      default:
        break;
    }

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
  <title>Receiving Routing and Mailing - Highschool</title>

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
                          <select id="status" name="status" class="form-control select2bs4" style="width: 100%;">
                            <?php
                              foreach ($statuses as $item) {
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
                          <label>On Holding Office</label>
                          <select id="office" name="office" class="form-control select2bs4" style="width: 100%;">
                            <?php
                              foreach ($offices as $item) {
                                if ($record["office"] == $item["name"]) {
                                  echo "<option selected>$item[name]</option>";
                                } else {
                                  echo "<option>$item[name]</option>";
                                }
                              }
                            ?>
                          </select>
                          <input id="office-selected" name="office-selected" type="hidden" value="<?php echo $record["office"]; ?>"/>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>On Holding Section</label>
                          <select id="section" name="section" class="form-control select2bs4" style="width: 100%;">
                            <?php
                              foreach ($sections as $item) {
                                if ($record["section"] == $item["name"]) {
                                  echo "<option selected>$item[name]</option>";
                                } else {
                                  echo "<option>$item[name]</option>";
                                }
                              }
                            ?>
                          </select>
                          <input id="section-selected" name="section-selected" type="hidden" value="<?php echo $record["section"]; ?>"/>
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
                          <textarea name="documents" class="form-control" rows="2" style="resize: none;"><?php echo $record["documents"]; ?></textarea>
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

      $("#record-form").validate({
        rules: {
          "status": {
            required: true
          },
          "office": {
            required: true
          }
        },
        messages: {
          "status": {
            required: "Status is required"
          },
          "office": {
            required: "Status is required"
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

      $("#status").select2({
        theme: "bootstrap4",
        placeholder: "Status"
      }).on("change", function (e) {
        $(this).valid()
      });
      $("#office").select2({
        theme: "bootstrap4",
        placeholder: "Office"
      }).on("change", function (e) {
        $(this).valid()
      });
      $("#section").select2({
        theme: "bootstrap4",
        placeholder: "Section"
      });

      function statusSelect() {
        switch ($("#status").val()) {
          case "Forwarded":
            $("#office").removeAttr("disabled");
            $("#section").removeAttr("disabled");
            break;
          default:
            $("#office").attr("disabled", "disabled");
            $("#section").attr("disabled", "disabled");
            break;
        }
      }
      function officeSelect() {
        switch ($("#office").val()) {
          case "Records Management Services":
            $("#section").removeAttr("disabled");
            break;
          default:
            $("#section").attr("disabled", "disabled");
            break;
        }
      }

      statusSelect();

      $("#status").change(function () {
        statusSelect();
      });
      $("#office").change(function () {
        officeSelect();
        var value = $(this).find("option:selected").text();
        $("#office-selected").val(value);
      });
      $("#section").change(function () {
        var value = $(this).find("option:selected").text();
        $("#section-selected").val(value);
      });
    });
  </script>
</body>
</html>