<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/rrm-record.php");

  $obj = new RrmRecord();
  $records = $obj->getRecordsByCategory(1);

  $statDisabledUpdate = array("Released", "Cancelled");
  $statBasedDelete = array("Open");
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
              <h1 class="m-0">Receiving Routing and Mailing - Elementary</h1>
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
                        <th>On Holding Office</th>
                        <th>On Holding Section</th>
                        <th style="width: 14%">Created Date</th>
                        <th style="width: 10%"></th>
                      </tr>
                    </thead>
                    <tbody id="rrm-elem-tbody">
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
                            <td class='align-middle'>$item[office]</td>
                            <td class='align-middle'>$item[section]</td>
                            <td class='align-middle'>$item[created_date]</td>
                            <td class='align-middle'>
                              <div class='row'>
                                <div class='col-sm-4'>
                                  <a href='read.php?record_id=$item[record_id]' class='btn btn-block btn-info btn-sm'>
                                    <i class='fas fa-search'></i>
                                  </a>
                                </div>
                                <div class='col-sm-4'>
                                  <a href='update.php?record_id=$item[record_id]' class='btn btn-block btn-primary btn-sm" . ((in_array($item["status"], $statDisabledUpdate)) ? " disabled" : "") . "'>
                                  <i class='fas fa-pencil-alt'></i>
                                  </a>
                                </div>
                                <div class='col-sm-4'>
                                  <a id='delete-icon' href='#delete-modal' class='btn btn-block btn-danger btn-sm" . ((in_array($item["status"], $statBasedDelete)) ? "" : " disabled") . "' data-toggle='modal' data-id='$item[record_id]'>
                                    <i class='fas fa-trash'></i>
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

          <div class="row mb-2">
            <div class="col-md-1">
              <a href="create.php" class="btn btn-block btn-success ">Add</a>
            </div>
          </div>
        </div>

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
                <a id="delete" href="#" class="btn btn-danger">Delete</a>
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

      var rrmTable = $("#records").DataTable({
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
          { "targets": [3, 7], "orderable": false, "createdCell": function (td, cellData) { td.innerHTML = cellData || ""; } }
        ]
      });

      var statDisabledUpdate = <?php echo json_encode($statDisabledUpdate); ?>;
      var statBasedDelete = <?php echo json_encode($statBasedDelete); ?>;
      function statusClass(s) {
        var map = { "Open": "bg-cyan", "In Progress": "bg-green", "Received": "bg-teal", "Forwarded": "bg-purple", "Returned": "bg-pink", "In Review": "bg-indigo", "For Release": "bg-blue", "Released": "bg-dark", "On Hold": "bg-red", "Cancelled": "bg-gray" };
        return map[s] || "bg-secondary";
      }
      function esc(s) { return (s == null ? "" : String(s)).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;"); }
      function refreshRRMList() {
        fetch("data.php?t=" + Date.now(), { cache: "no-store", credentials: "same-origin" })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.error && data.records) return;
            var list = data.records || [];
            var rows = [];
            for (var i = 0; i < list.length; i++) {
              var r = list[i];
              var statusCls = statusClass(r.status);
              var statusHtml = "<span class='badge badge-pill " + statusCls + " w-75'>" + esc(r.status) + "</span>";
              var updDisabled = statDisabledUpdate.indexOf(r.status) !== -1 ? " disabled" : "";
              var delDisabled = statBasedDelete.indexOf(r.status) !== -1 ? "" : " disabled";
              var actions = "<div class='row'><div class='col-sm-4'><a href='read.php?record_id=" + r.record_id + "' class='btn btn-block btn-info btn-sm'><i class='fas fa-search'></i></a></div>" +
                "<div class='col-sm-4'><a href='update.php?record_id=" + r.record_id + "' class='btn btn-block btn-primary btn-sm" + updDisabled + "'><i class='fas fa-pencil-alt'></i></a></div>" +
                "<div class='col-sm-4'><a id='delete-icon' href='#delete-modal' class='btn btn-block btn-danger btn-sm" + delDisabled + "' data-toggle='modal' data-id='" + r.record_id + "'><i class='fas fa-trash'></i></a></div></div>";
              rows.push([r.record_id, esc(r.control_no), esc(r.client_name), statusHtml, esc(r.office), esc(r.section), esc(r.created_date), actions]);
            }
            rrmTable.clear();
            if (rows.length) rrmTable.rows.add(rows);
            rrmTable.draw(false);
          })
          .catch(function () {});
      }
      setInterval(refreshRRMList, 5000);

      $(document).on("click", "#delete-icon", function() {
        var a = document.getElementById("delete");
        var id = $(this).data("id");
        a.href = "delete.php?record_id=" + id;
      });
    });
  </script>
</body>
</html>