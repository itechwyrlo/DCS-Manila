<?php
session_start();

require_once __DIR__ . '/../../core/paths.php';

include_once APP_ROOT . '/core/repositories/database.php';
include_once APP_ROOT . '/core/repositories/record.php';
include_once APP_ROOT . '/core/repositories/rrm-record.php';
include_once APP_ROOT . '/core/repositories/rrm-record-tracking.php';
include_once APP_ROOT . '/core/repositories/control-no.php';
include_once APP_ROOT . '/core/repositories/category.php';
include_once APP_ROOT . '/core/repositories/queue-no.php';
include_once APP_ROOT . '/core/repositories/queuing.php';

$errors = [];
$success = false;

if (isset($_POST["submit"])) {
  $client_name = trim($_POST["client-name"] ?? "");
  $transaction = isset($_POST["transaction"]) ? (int)$_POST["transaction"] : 0;

  if ($client_name === "") {
    $errors[] = "Client Name is required.";
  }
  if ($transaction !== 1 && $transaction !== 2) {
    $errors[] = "Please select a transaction type (Elementary or Highschool).";
  }

  if (empty($errors)) {
    $category_id = $transaction;
    $obj = new ControlNo();
    $control_no = $obj->getControlNoById(1);
    $obj->updateControlNo($control_no["control_id"], $control_no["value"]);

    $obj = new Record();
    $record_id = $obj->addRecord($client_name, $control_no["value"], $category_id);

    $obj = new RrmRecord();
    $obj->addRecord($record_id);

    $obj = new RrmRecordTracking();
    $obj->addRecord($record_id, "Request with Control No. " . $control_no["value"] . " has been created", $_SESSION["account_id"] ?? null);

    $obj = new QueueNo();
    $counter = $obj->getNextQueueNo($category_id);

    $obj = new Queuing();
    $obj->addQueue($record_id, $counter);

    $_SESSION["toastr"] = array("type" => "success", "message" => "Transaction added. Control No: " . $control_no["value"]);
    header("location: add-transaction.php");
    exit();
  }
}

$base = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Transaction</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/particles/css/style.css">
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
  <style>
    .add-transaction-page { min-height: 100vh; position: relative; }
    .add-transaction-page #particles-js { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
    .add-transaction-box { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 420px; z-index: 1; }
    .add-transaction-card { border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
    .add-transaction-card .card-header { font-weight: 700; font-size: 1.25rem; }
    .add-transaction-card .btn-submit { background: #007bff; color: #fff; font-weight: 600; padding: 10px 24px; }
    .add-transaction-card .btn-submit:hover { background: #0056b3; color: #fff; }
  </style>
</head>
<body class="hold-transition add-transaction-page">
  <div class="add-transaction-box">
    <div class="card add-transaction-card">
      <div class="card-header">
        Add Transaction
      </div>
      <div class="card-body">
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger small">
            <?php echo implode("<br>", array_map("htmlspecialchars", $errors)); ?>
          </div>
        <?php endif; ?>
        <form method="post" action="add-transaction.php">
          <div class="form-group">
            <label class="small font-weight-bold">Client Name</label>
            <input type="text" name="client-name" class="form-control" placeholder="Client Name" value="" required>
          </div>
          <div class="form-group">
            <label class="small font-weight-bold">Transaction</label>
            <select name="transaction" class="form-control" required>
              <option value="" disabled selected hidden>Transaction Type</option>
              <option value="1">Elementary</option>
              <option value="2">Highschool</option>
            </select>
          </div>
          <div class="form-group mb-0 text-center">
            <button type="submit" name="submit" class="btn btn-submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="particles-js"></div>

  <script src="<?php echo $base; ?>plugins/jquery/jquery.min.js"></script>
  <script src="<?php echo $base; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $base; ?>plugins/toastr/toastr.min.js"></script>
  <script src="<?php echo $base; ?>plugins/particles/js/particles.min.js"></script>
  <script src="<?php echo $base; ?>plugins/particles/js/app.js"></script>
  <script>
    <?php if (isset($_SESSION["toastr"])): ?>
      toastr.<?php echo $_SESSION["toastr"]["type"]; ?>("<?php echo addslashes($_SESSION["toastr"]["message"]); ?>");
      <?php unset($_SESSION["toastr"]); ?>
    <?php endif; ?>
  </script>
</body>
</html>
