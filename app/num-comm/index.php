<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/num-record.php");

  $obj = new NumRecord();
  $records = $obj->getRecordsByClass(1);

  $statDisabledUpdate = array("Released", "Cancelled");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Numerical - Communication</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
              <h1 class="m-0">Numerical - Communication</h1>
            </div>
          </div>
        </div>
      </div>

      <!-- Main -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <table id="records" class="table table-hover">
                    <thead>
                      <tr>
                        <th style="width: 6%">ID</th>
                        <th>Control No</th>
                        <th>Client Name</th>
                        <th style="width: 8%" class="text-center">Status</th>
                        <th>Handling Personnel</th>
                        <th style="width: 14%">Created Date</th>
                        <th style="width: 10%"></th>
                      </tr>
                    </thead>
                    <tbody id="num-comm-tbody">
                      <?php
                        foreach ($records as $item) {
                          echo "
                          <tr>
                            <td class='align-middle'>$item[record_id]</td>
                            <td class='align-middle'>$item[control_no]</td>
                            <td class='align-middle'>$item[client_name]</td>
                            <td class='align-middle text-center'>";
                          
                          switch ($item["status"]) {
                            case "Open":
                              echo "<span class='badge badge-pill bg-cyan w-75'>$item[status]</span>";
                              break;
                            case "In Progress":
                              echo "<span class='badge badge-pill bg-green w-75'>$item[status]</span>";
                              break;
                            case "Received":
                              echo "<span class='badge badge-pill bg-teal w-75'>$item[status]</span>";
                              break;
                            case "Forwarded":
                              echo "<span class='badge badge-pill bg-purple w-75'>$item[status]</span>";
                              break;
                            case "Returned":
                              echo "<span class='badge badge-pill bg-pink w-75'>$item[status]</span>";
                              break;
                            case "In Review":
                              echo "<span class='badge badge-pill bg-indigo w-75'>$item[status]</span>";
                              break;
                            case "For Release":
                              echo "<span class='badge badge-pill bg-blue w-75'>$item[status]</span>";
                              break;
                            case "Released":
                              echo "<span class='badge badge-pill bg-dark w-75'>$item[status]</span>";
                              break;
                            case "On Hold":
                              echo "<span class='badge badge-pill bg-red w-75'>$item[status]</span>";
                              break;
                            case "Cancelled":
                              echo "<span class='badge badge-pill bg-gray w-75'>$item[status]</span>";
                              break;
                          }

                          echo "
                            </td>
                            <td class='align-middle'>$item[personnel]</td>
                            <td class='align-middle'>$item[created_date]</td>
                            <td class='align-middle'>
                              <div class='row'>
                                <div class='col-sm-4 offset-sm-2'>
                                  <a href='read.php?record_id=$item[record_id]' class='btn btn-block btn-info btn-sm'>
                                    <i class='fas fa-search'></i>
                                  </a>
                                </div>
                                <div class='col-sm-4'>
                                  <a href='update.php?record_id=$item[record_id]' class='btn btn-block btn-primary btn-sm" . ((in_array($item["status"], $statDisabledUpdate)) ? " disabled" : "") . "'>
                                  <i class='fas fa-pencil-alt'></i>
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>";
                        }
                      ?>
                    </tbody>
                  </table>
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
  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
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
      
      var statDisabledUpdate = <?php echo json_encode($statDisabledUpdate); ?>;
      function statusClass(s) {
        var map = { "Open": "bg-cyan", "In Progress": "bg-green", "Received": "bg-teal", "Forwarded": "bg-purple", "Returned": "bg-pink", "In Review": "bg-indigo", "For Release": "bg-blue", "Released": "bg-dark", "On Hold": "bg-red", "Cancelled": "bg-gray" };
        return map[s] || "bg-secondary";
      }
      function esc(s) { return (s == null ? "" : String(s)).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;"); }

      var officeTable = $("#records").DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "scrollY": "55vh",
        "scrollCollapse": true,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[ 0, "desc" ]],
        "columnDefs": [
          { "targets": [3, 6], "orderable": false, "createdCell": function (td, cellData) { td.innerHTML = cellData || ""; } }
        ]
      });

      function refreshList() {
        fetch("data.php?t=" + Date.now(), { cache: "no-store", credentials: "same-origin" })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.error && data.records) return;
            var list = data.records || [];
            var rows = [];
            for (var i = 0; i < list.length; i++) {
              var r = list[i];
              var statusHtml = "<span class='badge badge-pill " + statusClass(r.status) + " w-75'>" + esc(r.status) + "</span>";
              var updDisabled = statDisabledUpdate.indexOf(r.status) !== -1 ? " disabled" : "";
              var actions = "<div class='row'><div class='col-sm-4 offset-sm-2'><a href='read.php?record_id=" + r.record_id + "' class='btn btn-block btn-info btn-sm'><i class='fas fa-search'></i></a></div><div class='col-sm-4'><a href='update.php?record_id=" + r.record_id + "' class='btn btn-block btn-primary btn-sm" + updDisabled + "'><i class='fas fa-pencil-alt'></i></a></div></div>";
              rows.push([r.record_id, esc(r.control_no), esc(r.client_name), statusHtml, esc(r.personnel), esc(r.created_date), actions]);
            }
            officeTable.clear();
            if (rows.length) officeTable.rows.add(rows);
            officeTable.draw(false);
          })
          .catch(function () {});
      }
      setInterval(refreshList, 5000);
    });
  </script>
</body>
</html>