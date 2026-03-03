<?php
  session_start();

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/record.php");

  $control_no = isset($_GET["control_no"]) ? trim($_GET["control_no"]) : "";
  if ($control_no === "") {
    header("location: ../../index.php");
    exit;
  }

  $obj = new Record();
  $tracks = $obj->getTrackByControlNo($control_no);

  if ($tracks == null) {
    $_SESSION["toastr"] = array("type" => "error", "message" => "Invalid control number!");
    header("location: ../../index.php");
    exit;
  }

  // Time Elapsed String -- Not functioning properly. For future reference if needed.
  // function time_elapsed_string($datetime, $full = false) {
  //   $now = new DateTime;
  //   $ago = new DateTime($datetime);
  //   $diff = $now->diff($ago);

  //   $diff->w = floor($diff->d / 7);
  //   $diff->d -= $diff->w * 7;

  //   $string = array(
  //     "y" => "year",
  //     "m" => "month",
  //     "w" => "week",
  //     "d" => "day",
  //     "h" => "hour",
  //     "i" => "minute",
  //     "s" => "second",
  //   );
  //   foreach ($string as $k => &$v) {
  //     if ($diff->$k) {
  //       $v = $diff->$k . " " . $v . ($diff->$k > 1 ? "s" : "");
  //     } else {
  //       unset($string[$k]);
  //     }
  //   }

  //   if (!$full) $string = array_slice($string, 0, 1);
  //   return $string ? implode(", ", $string) . " ago" : "just now";
  // }
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
<body class="hold-transition tracking-index-page">
  <div class="tracking-index-box">
    <div class="row justify-content-md-center">
      <div class="col-md-12">
        <div class="timeline mt-5" id="tracking-timeline">
          <?php
            $date = "";
            foreach ($tracks as $item) {
              if ($date != $item["date"]) {
                $date = $item["date"];

                echo "
                <div class='time-label'>
                  <span class='bg-primary'>" . date("F j, Y", strtotime($item["date"])) . "</span>
                </div>";
              }

              echo "
              <div>
                <div class='timeline-item'>
                  <div class='timeline-header'>
                    <div class='d-flex'>";
              
              if ($item["section"] == "") {
                echo "<span class='mr-auto badge'>" . $item["office_code"] . "</span>";
              } else {
                echo "<span class='mr-auto badge'>" . $item["office_code"] . " | " . $item["section_code"] . "</span>";
              }

              switch ($item["status"]) {
                case "Open":
                  echo "<span class='badge badge-pill bg-cyan' style='width: 80px;'>" . $item["status"] . "</span>";
                  break;
                case "In Progress":
                  echo "<span class='badge badge-pill bg-green' style='width: 80px;'>$item[status]</span>";
                  break;
                case "Received":
                  echo "<span class='badge badge-pill bg-teal' style='width: 80px;'>$item[status]</span>";
                  break;
                case "Forwarded":
                  echo "<span class='badge badge-pill bg-purple' style='width: 80px;'>$item[status]</span>";
                  break;
                case "Returned":
                  echo "<span class='badge badge-pill bg-pink' style='width: 80px;'>$item[status]</span>";
                  break;
                case "In Review":
                  echo "<span class='badge badge-pill bg-indigo' style='width: 80px;'>$item[status]</span>";
                  break;
                case "For Release":
                  echo "<span class='badge badge-pill bg-blue' style='width: 80px;'>$item[status]</span>";
                  break;
                case "Released":
                  echo "<span class='badge badge-pill bg-dark' style='width: 80px;'>$item[status]</span>";
                  break;
                case "On Hold":
                  echo "<span class='badge badge-pill bg-red' style='width: 80px;'>$item[status]</span>";
                  break;
                case "Cancelled":
                  echo "<span class='badge badge-pill bg-gray' style='width: 80px;'>$item[status]</span>";
                  break;
              }

              echo "
                    </div>
                  </div>
                  <div class='timeline-body'>
                    <div class='d-flex flex-column'>
                      <p class='mb-0'>" . $item["remarks"] . "</p>
                    </div>
                  </div>
                  <div class='timeline-footer'>
                    <div class='d-flex'>
                      <span class='badge'>" . date("h:i A", strtotime($item["time"])) . "</span>
                    </div>
                  </div>
                </div>
              </div>";
            }
          ?>
          <div>
            <i class="fas fa-clock bg-gray"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-md-center">
      <div class="col-sm-2">
        <button class="btn btn-block btn-primary" onclick="history.back()">Go Back</button>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Toastr -->
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/js/adminlte.min.js"></script>
  <script>
    (function () {
      var controlNo = <?php echo json_encode($control_no); ?>;
      var pollInterval = 5000;
      var lastCount = <?php echo count($tracks); ?>;

      function statusBadgeClass(s) {
        var map = { "Open": "bg-cyan", "In Progress": "bg-green", "Received": "bg-teal", "Forwarded": "bg-purple", "Returned": "bg-pink", "In Review": "bg-indigo", "For Release": "bg-blue", "Released": "bg-dark", "On Hold": "bg-red", "Cancelled": "bg-gray" };
        return map[s] || "bg-secondary";
      }
      function escapeHtml(t) { return (t == null ? "" : String(t)).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;"); }
      function formatDate(d) {
        if (!d) return "";
        var x = new Date(d);
        var m = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        return m[x.getMonth()] + " " + x.getDate() + ", " + x.getFullYear();
      }
      function formatTime(t) {
        if (!t) return "";
        var parts = String(t).split(":");
        var h = parseInt(parts[0], 10), m = parseInt(parts[1], 10);
        var ampm = h >= 12 ? "PM" : "AM";
        h = h % 12 || 12;
        return (h < 10 ? "0" : "") + h + ":" + (m < 10 ? "0" : "") + m + " " + ampm;
      }

      function renderTimeline(tracks) {
        var html = "";
        var date = "";
        for (var i = 0; i < tracks.length; i++) {
          var item = tracks[i];
          if (date !== item.date) {
            date = item.date;
            html += "<div class='time-label'><span class='bg-primary'>" + escapeHtml(formatDate(item.date)) + "</span></div>";
          }
          var officeLabel = item.section ? (escapeHtml(item.office_code) + " | " + escapeHtml(item.section_code)) : escapeHtml(item.office_code);
          var badgeClass = statusBadgeClass(item.status);
          html += "<div><div class='timeline-item'><div class='timeline-header'><div class='d-flex'>";
          html += "<span class='mr-auto badge'>" + officeLabel + "</span>";
          html += "<span class='badge badge-pill " + badgeClass + "' style='width: 80px;'>" + escapeHtml(item.status) + "</span>";
          html += "</div></div><div class='timeline-body'><div class='d-flex flex-column'><p class='mb-0'>" + escapeHtml(item.remarks) + "</p></div></div>";
          html += "<div class='timeline-footer'><div class='d-flex'><span class='badge'>" + escapeHtml(formatTime(item.time)) + "</span></div></div></div></div>";
        }
        html += "<div><i class='fas fa-clock bg-gray'></i></div>";
        return html;
      }

      function poll() {
        fetch("data.php?control_no=" + encodeURIComponent(controlNo) + "&t=" + Date.now(), { cache: "no-store" })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.error && data.tracks && data.tracks.length === 0) return;
            var tracks = data.tracks || [];
            if (tracks.length !== lastCount) {
              lastCount = tracks.length;
              var el = document.getElementById("tracking-timeline");
              if (el) el.innerHTML = renderTimeline(tracks);
            }
          })
          .catch(function () {});
      }

      setInterval(poll, pollInterval);
    })();
  </script>
</body>
</html>