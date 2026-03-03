<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/queuing.php");
  include_once("../../core/repositories/rrm-record.php");
  include_once("../../core/repositories/category.php");

  // Status: 1 = Open (Waiting), 2 = In Progress (Now Serving), 3 = Received (after serving)
  const STATUS_WAITING = 1;
  const STATUS_NOW_SERVING = 2;
  const STATUS_RECEIVED = 3;

  $catObj = new Category();
  
  $categories = [
    $catObj->getCategoryById(1),
    $catObj->getCategoryById(2)
  ];
  $categories = array_filter($categories);
  $category_id = isset($_GET["transaction"]) ? (int)$_GET["transaction"] : 1;
  if ($category_id !== 1 && $category_id !== 2) {
    $category_id = 1;
  }
  // Window No is fixed by transaction: 1 = Elementary, 2 = High School
  $window_no = $category_id;

  $flagFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "queuing_display.flag";
  $dataDir = dirname($flagFile);
  if (!is_dir($dataDir)) {
    @mkdir($dataDir, 0755, true);
  }

  // Persist current window for this transaction so queue board can show it in real time
  $windowFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "queuing_window.json";
  $windows = ["1" => 1, "2" => 2];
  if (file_exists($windowFile)) {
    $decoded = @json_decode(file_get_contents($windowFile), true);
    if (is_array($decoded)) {
      if (isset($decoded["1"])) $windows["1"] = (int)$decoded["1"];
      if (isset($decoded["2"])) $windows["2"] = (int)$decoded["2"];
    }
  }
  $windows[(string)$category_id] = $window_no;
  @file_put_contents($windowFile, json_encode($windows));

  $snapshotFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "queuing_snapshot.json";

  function saveQueuingSnapshot($objQ, $windowFile, $snapshotFile) {
    $windows = ["1" => 1, "2" => 2];
    if (file_exists($windowFile)) {
      $decoded = @json_decode(file_get_contents($windowFile), true);
      if (is_array($decoded)) {
        if (isset($decoded["1"])) $windows["1"] = (int)$decoded["1"];
        if (isset($decoded["2"])) $windows["2"] = (int)$decoded["2"];
      }
    }
    $elemWaiting = $objQ->getQueues(1, STATUS_WAITING);
    $elemNowServingList = $objQ->getQueues(1, STATUS_NOW_SERVING);
    $elemNowServing = !empty($elemNowServingList) ? $elemNowServingList[0] : null;
    $hsWaiting = $objQ->getQueues(2, STATUS_WAITING);
    $hsNowServingList = $objQ->getQueues(2, STATUS_NOW_SERVING);
    $hsNowServing = !empty($hsNowServingList) ? $hsNowServingList[0] : null;
    $fmt = function ($cat, $no) {
      $prefix = (int)$cat === 1 ? "EL" : "HS";
      return $prefix . "-" . str_pad((string)(int)$no, 3, "0", STR_PAD_LEFT);
    };
    $payload = [
      "displayOn" => true,
      "elementary" => [
        "windowNo" => $windows["1"],
        "waiting" => array_map(function ($r) use ($fmt) { return $fmt(1, $r["queue_no"]); }, $elemWaiting),
        "nowServing" => $elemNowServing ? $fmt(1, $elemNowServing["queue_no"]) : null
      ],
      "highschool" => [
        "windowNo" => $windows["2"],
        "waiting" => array_map(function ($r) use ($fmt) { return $fmt(2, $r["queue_no"]); }, $hsWaiting),
        "nowServing" => $hsNowServing ? $fmt(2, $hsNowServing["queue_no"]) : null
      ]
    ];
    $dataDir = dirname($snapshotFile);
    if (!is_dir($dataDir)) {
      @mkdir($dataDir, 0755, true);
    }
    @file_put_contents($snapshotFile, json_encode($payload));
  }

  // Start: turn on display, save window for this category, promote first waiting to Now Serving if none, then save snapshot for queue board.
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["start"])) {
    $dataDir = dirname($flagFile);
    if (!is_dir($dataDir)) {
      @mkdir($dataDir, 0755, true);
    }
    @file_put_contents($flagFile, "1");

    $windows = ["1" => 1, "2" => 2];
    if (file_exists($windowFile)) {
      $decoded = @json_decode(file_get_contents($windowFile), true);
      if (is_array($decoded)) {
        if (isset($decoded["1"])) $windows["1"] = (int)$decoded["1"];
        if (isset($decoded["2"])) $windows["2"] = (int)$decoded["2"];
      }
    }
    $windows[(string)$category_id] = $window_no;
    @file_put_contents($windowFile, json_encode($windows));

    $objQ = new Queuing();
    $objR = new RrmRecord();
    $nowServing = $objQ->getQueues($category_id, STATUS_NOW_SERVING);
    $waiting = $objQ->getQueues($category_id, STATUS_WAITING);
    if (empty($nowServing) && !empty($waiting)) {
      $objR->setStatus($waiting[0]["record_id"], STATUS_NOW_SERVING);
    }

    saveQueuingSnapshot($objQ, $windowFile, $snapshotFile);

    $redirect = "index.php?transaction=" . $category_id . "&window=" . $window_no;
    header("location: " . $redirect);
    exit();
  }

  // Stop: turn off display; queue board will show no data when display is off.
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["stop"])) {
    $dataDir = dirname($flagFile);
    if (!is_dir($dataDir)) {
      @mkdir($dataDir, 0755, true);
    }
    @file_put_contents($flagFile, "0");
    $redirect = "index.php?transaction=" . $category_id . "&window=" . $window_no;
    header("location: " . $redirect);
    exit();
  }

  // Next: move current serving to Received, first waiting to Now Serving, then save snapshot for queue board.
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["next"])) {
    $objQ = new Queuing();
    $objR = new RrmRecord();
    $nowServing = $objQ->getQueues($category_id, STATUS_NOW_SERVING);
    $waiting = $objQ->getQueues($category_id, STATUS_WAITING);
    if (!empty($nowServing)) {
      $objR->setStatus($nowServing[0]["record_id"], STATUS_RECEIVED);
    }
    if (!empty($waiting)) {
      $objR->setStatus($waiting[0]["record_id"], STATUS_NOW_SERVING);
    }
    saveQueuingSnapshot($objQ, $windowFile, $snapshotFile);
    $redirect = "index.php?transaction=" . $category_id . "&window=" . $window_no;
    header("location: " . $redirect);
    exit();
  }

  $obj = new Queuing();
  $waitingList = $obj->getQueues($category_id, STATUS_WAITING);
  $nowServingList = $obj->getQueues($category_id, STATUS_NOW_SERVING);
  $nowServing = !empty($nowServingList) ? $nowServingList[0] : null;

  function formatQueueNo($category_id, $queue_no) {
    $prefix = (int)$category_id === 1 ? "EL" : "HS";
    return $prefix . "-" . str_pad((string)(int)$queue_no, 3, "0", STR_PAD_LEFT);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Queuing</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    .queuing-config-panel { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem; }
    .queuing-now-serving-banner { background: #28a745; color: #fff; padding: 8px 12px; font-weight: 700; text-transform: uppercase; font-size: 1rem; }
    .queuing-waiting-banner { background: #ffc107; color: #212529; padding: 8px 12px; font-weight: 700; text-transform: uppercase; font-size: 1rem; }
    .queuing-queue-box { border: 2px solid #212529; border-radius: 4px; padding: 1.5rem 2rem; text-align: center; background: #fff; min-height: 100px; display: flex; align-items: center; justify-content: center; }
    .queuing-queue-number { font-size: 2.5rem; font-weight: 700; }
    .queuing-waiting-list { list-style: none; padding: 0; margin: 0; }
    .queuing-waiting-list li { background: #e9ecef; padding: 12px 16px; margin-bottom: 4px; text-align: center; font-size: 1.5rem; font-weight: 600; }
    .queuing-client-name { margin-top: 8px; font-size: 0.95rem; color: #495057; }
  </style>
</head>
<body class="hold-transition sidebar-mini" onload="startTime()">
  <div class="wrapper">
    <?php include("../../app/components/navbar.php"); ?>
    <?php include("../../app/components/sidebar.php"); ?>

    <div class="content-wrapper">

    <!-- Page Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-6">
            <h1 class="m-0">Queuing</h1>
          </div>
          <div class="col-6 text-right">
            <span id="clock" class="font-weight-bold"></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row" style="min-height: calc(100vh - 200px);">

          <!-- LEFT: Configuration + Buttons -->
          <div class="col-md-3 d-flex flex-column">
            <div class="card flex-fill mb-0">
              <div class="card-header">
                <h3 class="card-title font-weight-bold">Configuration</h3>
              </div>
              <div class="card-body">
  <form method="get" action="index.php" id="config-form">
    <div class="form-group">
      <label class="font-weight-bold small">Transaction</label>
      <select name="transaction" id="config-transaction" class="form-control form-control-sm"
              onchange="document.getElementById('config-window').value=this.value; document.getElementById('config-form').submit();">
        <?php foreach ($categories as $cat):
          $sel = ($cat["category_id"] == $category_id) ? " selected" : "";
          echo '<option value="' . (int)$cat["category_id"] . '"' . $sel . '>' . htmlspecialchars($cat["name"]) . '</option>';
        endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label class="font-weight-bold small">Window No</label>
      <input type="number" name="window" id="config-window" class="form-control form-control-sm" min="1" max="2"
             value="<?php echo $window_no; ?>"
             readonly title="Window No is 1 for Elementary, 2 for High School">
    </div>
  </form>
</div>
              <div class="card-footer">
                <form method="post" action="index.php?transaction=<?php echo $category_id; ?>&window=<?php echo $window_no; ?>">
                  <div class="row no-gutters">
                    <div class="col pr-1">
                      <button type="submit" name="start" value="1" class="btn btn-success btn-block">Start</button>
                    </div>
                    <div class="col px-1">
                      <button type="submit" name="stop" value="1" class="btn btn-danger btn-block">Stop</button>
                    </div>
                    <div class="col pl-1">
                      <button type="submit" name="next" value="1" class="btn btn-primary btn-block">Next</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- CENTER: Now Serving -->
          <div class="col-md-5 d-flex flex-column">
            <div class="card flex-fill mb-0">
              <div class="card-header bg-success text-center p-2">
                <h3 class="card-title font-weight-bold text-uppercase text-white w-100 text-center mb-0">NOW SERVING</h3>
              </div>
              <div class="card-body d-flex flex-column justify-content-center align-items-center" id="queuing-now-serving">
                <p class="text-muted small mb-1">Queue No</p>
                <div class="border border-dark rounded p-4 mb-3 text-center" style="min-width:180px;">
                  <span id="queuing-queue-no" style="font-size:3rem; font-weight:700; line-height:1;">
                    <?php echo $nowServing ? formatQueueNo($category_id, $nowServing["queue_no"]) : "&mdash;"; ?>
                  </span>
                </div>
                <p class="text-muted small mb-0">
                  Client Name: <strong class="text-dark" id="queuing-client-name">
                    <?php echo $nowServing ? htmlspecialchars($nowServing["client_name"]) : "&mdash;"; ?>
                  </strong>
                </p>
              </div>
            </div>
          </div>

          <!-- RIGHT: Waiting -->
          <div class="col-md-4 d-flex flex-column">
            <div class="card flex-fill mb-0">
              <div class="card-header bg-warning text-center p-2">
                <h3 class="card-title font-weight-bold text-uppercase text-dark w-100 text-center mb-0">WAITING</h3>
              </div>
              <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="queuing-waiting-list">
                  <?php
                    if (empty($waitingList)) {
                      echo '<li class="list-group-item text-center text-muted">&mdash;</li>';
                    } else {
                      foreach ($waitingList as $item) {
                        echo '<li class="list-group-item text-center font-weight-bold" style="font-size:1.1rem;">'
                          . formatQueueNo($category_id, $item["queue_no"])
                          . '</li>';
                      }
                    }
                  ?>
                </ul>
              </div>
            </div>
          </div>

        </div><!-- /.row -->
      </div>
    </div>

    <?php include("../../app/components/footer.php"); ?>
  </div>
</div>

  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/adminlte.min.js"></script>
  <script>
    var QUEUING_CATEGORY_ID = <?php echo (int)$category_id; ?>;

    function startTime() {
      var t = new Date(), h = t.getHours(), m = t.getMinutes(), s = t.getSeconds();
      var ampm = h >= 12 ? "PM" : "AM";
      h = h % 12 || 12;
      document.getElementById("clock").innerHTML = (h < 10 ? "0" : "") + h + ":" + (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s + " " + ampm;
      setTimeout(startTime, 1000);
    }

    function refreshQueueData() {
      $.ajax({
        url: "data.php",
        data: { transaction: QUEUING_CATEGORY_ID },
        dataType: "json"
      }).done(function (data) {
        if (data.error) return;
        var no = document.getElementById("queuing-queue-no");
        var name = document.getElementById("queuing-client-name");
        var list = document.getElementById("queuing-waiting-list");
        if (no) no.textContent = data.nowServing ? data.nowServing.queue_no_display : "\u2014";
        if (name) name.textContent = data.nowServing ? data.nowServing.client_name : "\u2014";
        if (list) {
          list.innerHTML = "";
          if (!data.waiting || data.waiting.length === 0) {
            list.innerHTML = "<li class=\"list-group-item text-center text-muted\">\u2014</li>";
          } else {
            data.waiting.forEach(function (q) {
              var li = document.createElement("li");
              li.className = "list-group-item text-center font-weight-bold";
              li.style.fontSize = "1.1rem";
              li.textContent = q;
              list.appendChild(li);
            });
          }
        }
      });
    }

    setInterval(refreshQueueData, 3000);
  </script>
</body>
</html>
