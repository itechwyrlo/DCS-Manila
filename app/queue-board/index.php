<?php
// Resolve project root: app/queue-board (2 levels up) or queue-board at doc root (1 level up)
if (!defined('APP_ROOT')) {
  $corePaths = __DIR__ . '/../../core/paths.php';
  define('APP_ROOT', file_exists($corePaths) ? dirname(__DIR__, 2) : dirname(__DIR__));
}
require_once file_exists(__DIR__ . '/../../core/paths.php') ? __DIR__ . '/../../core/paths.php' : __DIR__ . '/../core/paths.php';

$dataDir = APP_ROOT . DIRECTORY_SEPARATOR . 'data';
$flagFile = $dataDir . DIRECTORY_SEPARATOR . "queuing_display.flag";
$displayOn = (file_exists($flagFile) && trim(file_get_contents($flagFile)) === "1");

$windowFile = $dataDir . DIRECTORY_SEPARATOR . "queuing_window.json";
$windowElem = 1;
$windowHs = 2;
$elemWaiting = [];
$elemNowServing = null;
$hsWaiting = [];
$hsNowServing = null;

if ($displayOn) {
  if (file_exists($windowFile)) {
    $decoded = @json_decode(file_get_contents($windowFile), true);
    if (is_array($decoded)) {
      if (isset($decoded["1"])) $windowElem = (int)$decoded["1"];
      if (isset($decoded["2"])) $windowHs = (int)$decoded["2"];
    }
  }
  include_once APP_ROOT . '/core/repositories/database.php';
  include_once APP_ROOT . '/core/repositories/queuing.php';
  $obj = new Queuing();
  $elemWaitingRaw = $obj->getQueues(1, 1);
  $elemNowServingList = $obj->getQueues(1, 2);
  $elemNowServingRaw = !empty($elemNowServingList) ? $elemNowServingList[0] : null;
  $hsWaitingRaw = $obj->getQueues(2, 1);
  $hsNowServingList = $obj->getQueues(2, 2);
  $hsNowServingRaw = !empty($hsNowServingList) ? $hsNowServingList[0] : null;
  function formatQueueNo($category_id, $queue_no) {
    $prefix = (int)$category_id === 1 ? "EL" : "HS";
    return $prefix . "-" . str_pad((string)(int)$queue_no, 3, "0", STR_PAD_LEFT);
  }
  $elemWaiting = array_map(function ($r) { return formatQueueNo(1, $r["queue_no"]); }, $elemWaitingRaw);
  $elemNowServing = $elemNowServingRaw ? formatQueueNo(1, $elemNowServingRaw["queue_no"]) : null;
  $hsWaiting = array_map(function ($r) { return formatQueueNo(2, $r["queue_no"]); }, $hsWaitingRaw);
  $hsNowServing = $hsNowServingRaw ? formatQueueNo(2, $hsNowServingRaw["queue_no"]) : null;
}
// Asset base: ../ when queue-board is at doc root, ../../ when under app/queue-board
$base = (APP_ROOT === dirname(__DIR__)) ? '../' : '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Queue Board - DCS Manila</title>
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo $base; ?>plugins/particles/css/style.css">
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
  <style>
    .queue-board-page { min-height: 100vh; position: relative; }
    .queue-board-page #particles-js { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
    .queue-board-page .queue-board-content { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; }
    .queue-board-page .queue-board-content .board-row { flex: 1 1 auto; min-height: 0; display: flex; }
    .queue-board-page .queue-board-content .board-card { display: flex; flex-direction: column; min-height: 0; }
    .queue-board-page .queue-board-content .board-card .card-inner { flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column; }
    .queue-board-page .queue-board-content .board-card .card-inner .queue-area { flex: 1 1 auto; min-height: 280px; display: flex; align-items: stretch; }
    /* NOW SERVING column: full height so Queue No block can be vertically centered */
    .queue-board-page .queue-area .serving-col { min-height: 100%; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; }
    /* Queue No — large for monitor display */
    .queue-board-page .serving-label { font-size: 1.5rem !important; }
    .queue-board-page .serving-box { width: 280px !important; height: 140px !important; }
    .queue-board-page .serving-box .queue-no-value { font-size: 4rem !important; line-height: 1.2; }
    .queue-board-page .queue-board-content .window-msg { font-size: 1.25rem !important; }
  </style>
</head>
<body class="hold-transition queue-board-page" onload="startTime()">

  <div class="queue-board-content container-fluid px-4 py-4">
    <div class="bg-white rounded mb-3 d-flex justify-content-between align-items-center py-3 px-4 flex-shrink-0">
      <span class="font-weight-bold" style="font-size:18px; letter-spacing:0.5px;">DCS-MANILA RECORD MANAGEMENT INFORMATION SYSTEM</span>
      <span id="clock" class="font-weight-bold" style="font-size:18px;"></span>
    </div>
    <div class="row no-gutters board-row">
      <div class="col bg-white rounded overflow-hidden p-0 mr-2 board-card">
        <div class="text-center font-weight-bold py-3 flex-shrink-0" style="font-size:18px; letter-spacing:1px;">ELEMENTARY</div>
        <div class="border rounded mx-3 mb-3 overflow-hidden card-inner flex-grow-1">
          <div class="row mx-0 no-gutters">
            <div class="col-4 bg-warning text-white text-center font-weight-bold py-2" style="font-size:13px; letter-spacing:1px;">WAITING</div>
            <div class="col-8 bg-success text-white text-center font-weight-bold py-2" style="font-size:13px; letter-spacing:1px;">NOW SERVING</div>
          </div>
          <div class="row mx-0 no-gutters queue-area">
            <div class="col-4 px-0 border-right" id="elem-waiting">
              <?php if (!$displayOn || empty($elemWaiting)): ?>
                <div class="text-center py-2 text-muted">—</div>
              <?php else: ?>
                <?php foreach ($elemWaiting as $i => $q): ?>
                  <div class="text-center py-2 <?php echo $i < count($elemWaiting) - 1 ? 'border-bottom' : ''; ?>"><?php echo htmlspecialchars($q); ?></div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="col-8 serving-col d-flex flex-column align-items-center justify-content-center" id="elem-serving-col">
              <div class="no-data" id="elem-no-data" style="<?php echo $displayOn ? 'display:none;' : ''; ?>">No data to display</div>
              <div class="text-muted mb-1 serving-label" style="<?php echo !$displayOn ? 'display:none;' : ''; ?>">Queue No</div>
              <div class="border border-dark rounded d-flex align-items-center justify-content-center mb-2 serving-box" style="<?php echo !$displayOn ? 'display:none;' : ''; ?>">
                <span class="font-weight-bold queue-no-value" id="elem-now-serving"><?php echo $displayOn && $elemNowServing !== null ? htmlspecialchars($elemNowServing) : "—"; ?></span>
              </div>
              <div class="text-muted text-center window-msg" id="elem-window-msg">Please proceed to Window No. <?php echo (int)$windowElem; ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col bg-white rounded overflow-hidden p-0 ml-2 board-card">
        <div class="text-center font-weight-bold py-3 flex-shrink-0" style="font-size:18px; letter-spacing:1px;">HIGHSCHOOL</div>
        <div class="border rounded mx-3 mb-3 overflow-hidden card-inner flex-grow-1">
          <div class="row mx-0 no-gutters">
            <div class="col-4 bg-warning text-white text-center font-weight-bold py-2" style="font-size:13px; letter-spacing:1px;">WAITING</div>
            <div class="col-8 bg-success text-white text-center font-weight-bold py-2" style="font-size:13px; letter-spacing:1px;">NOW SERVING</div>
          </div>
          <div class="row mx-0 no-gutters queue-area">
            <div class="col-4 px-0 border-right" id="hs-waiting">
              <?php if (!$displayOn || empty($hsWaiting)): ?>
                <div class="text-center py-2 text-muted">—</div>
              <?php else: ?>
                <?php foreach ($hsWaiting as $i => $q): ?>
                  <div class="text-center py-2 <?php echo $i < count($hsWaiting) - 1 ? 'border-bottom' : ''; ?>"><?php echo htmlspecialchars($q); ?></div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="col-8 serving-col d-flex flex-column align-items-center justify-content-center" id="hs-serving-col">
              <div class="no-data" id="hs-no-data" style="<?php echo $displayOn ? 'display:none;' : ''; ?>">No data to display</div>
              <div class="text-muted mb-1 serving-label" style="<?php echo !$displayOn ? 'display:none;' : ''; ?>">Queue No</div>
              <div class="border border-dark rounded d-flex align-items-center justify-content-center mb-2 serving-box" style="<?php echo !$displayOn ? 'display:none;' : ''; ?>">
                <span class="font-weight-bold queue-no-value" id="hs-now-serving"><?php echo $displayOn && $hsNowServing !== null ? htmlspecialchars($hsNowServing) : "—"; ?></span>
              </div>
              <div class="text-muted text-center window-msg" id="hs-window-msg">Please proceed to Window No. <?php echo (int)$windowHs; ?></div>
            </div>
          </div>
        </div>
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
    function startTime() {
      var t = new Date(), h = t.getHours(), m = t.getMinutes(), s = t.getSeconds();
      var ampm = h >= 12 ? "PM" : "AM";
      h = h % 12 || 12;
      document.getElementById("clock").textContent =
        (h < 10 ? "0" : "") + h + ":" + (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s + " " + ampm;
      setTimeout(startTime, 1000);
    }
    function renderWaiting(containerId, items) {
      var el = document.getElementById(containerId);
      if (!el) return;
      el.innerHTML = "";
      if (!items || items.length === 0) {
        var empty = document.createElement("div");
        empty.className = "text-center py-2 text-muted";
        empty.textContent = "\u2014";
        el.appendChild(empty);
      } else {
        items.forEach(function (q, i) {
          var div = document.createElement("div");
          div.className = "text-center py-2" + (i < items.length - 1 ? " border-bottom" : "");
          div.textContent = q;
          el.appendChild(div);
        });
      }
    }
    function updateBoard(data) {
      if (!data) return;
      var displayOn = !!data.displayOn;
      var elem = data.elementary, hs = data.highschool;
      function updateServingCol(colId, noDataId, noElId, winMsgId, payload, defaultWindow) {
        var colEl = document.getElementById(colId);
        var noDataEl = document.getElementById(noDataId);
        var noEl = document.getElementById(noElId);
        var winMsg = document.getElementById(winMsgId);
        if (!colEl) return;
        if (noDataEl) noDataEl.style.display = displayOn ? "none" : "";
        var label = colEl.querySelector(".serving-label");
        var box = colEl.querySelector(".serving-box");
        if (label) label.style.display = displayOn ? "" : "none";
        if (box) box.style.display = displayOn ? "" : "none";
        if (noEl && payload) noEl.textContent = payload.nowServing || "\u2014";
        if (winMsg && payload) winMsg.textContent = "Please proceed to Window No. " + (payload.windowNo || defaultWindow);
      }
      if (elem) {
        updateServingCol("elem-serving-col", "elem-no-data", "elem-now-serving", "elem-window-msg", elem, 1);
        renderWaiting("elem-waiting", displayOn ? elem.waiting : []);
      }
      if (hs) {
        updateServingCol("hs-serving-col", "hs-no-data", "hs-now-serving", "hs-window-msg", hs, 2);
        renderWaiting("hs-waiting", displayOn ? hs.waiting : []);
      }
    }
    function fetchBoardData() {
      fetch("data.php?t=" + Date.now(), { cache: "no-store", headers: { "X-Requested-With": "XMLHttpRequest" } })
        .then(function (r) { return r.json(); })
        .then(function (data) { if (data && !data.error) updateBoard(data); })
        .catch(function () {});
    }
    setInterval(fetchBoardData, 1500);
    fetchBoardData();
  </script>
</body>
</html>
