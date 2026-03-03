<?php
  session_start();

  if (!isset($_SESSION["account_id"]) || empty($_SESSION["account_id"])) {
    header("location: ../../login.php");
    exit();
  }

  if ($_SESSION["role"] != "Administrator") {
    header("location: ../dashboard/index.php");
    exit();
  }

  include_once("../../core/repositories/database.php");
  include_once("../../core/repositories/audit-log.php");
  include_once("../../app/components/status-badge.php");

  $obj = new AuditLog();
  $entries = $obj->getEntries();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Audit Log</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
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
              <h1 class="m-0">Audit Log</h1>
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
                  <table id="audit-log" class="table table-hover">
                    <thead>
                      <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 14%">Control No</th>
                        <th style="width: 16%">Who</th>
                        <th style="width: 14%">When</th>
                        <th style="width: 12%">Field</th>
                        <th>New Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($entries as $item) {
                          $id = htmlspecialchars((string)($item["id"] ?? ""));
                          $control_no = htmlspecialchars($item["control_no"] ?? "") ?: "—";
                          $who = htmlspecialchars($item["personnel"] ?? "") ?: "—";
                          $when = htmlspecialchars($item["created_date"] ?? "");
                          $field = htmlspecialchars($item["field"] ?? "—");
                          $new_value_raw = $item["new_value"] ?? "—";
                          $is_status = isset($item["field"]) && $item["field"] === "Status";
                          $new_value_cell = $is_status ? getStatusBadgeHtml($new_value_raw) : htmlspecialchars($new_value_raw);
                          echo "
                          <tr>
                            <td class='align-middle'>$id</td>
                            <td class='align-middle'>$control_no</td>
                            <td class='align-middle'>$who</td>
                            <td class='align-middle'>$when</td>
                            <td class='align-middle'><code>$field</code></td>
                            <td class='align-middle'>$new_value_cell</td>
                          </tr>";
                        }
                        if (empty($entries)) {
                          echo "<tr><td colspan='6' class='text-center text-muted'>No audit log entries.</td></tr>";
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
  <!-- Toastr -->
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/js/adminlte.min.js"></script>
  <script>
    $(function () {
      <?php if (isset($_SESSION["toastr"])) { ?>
        toastr.<?php echo $_SESSION["toastr"]["type"]; ?>('<?php echo addslashes($_SESSION["toastr"]["message"]); ?>');
      <?php unset($_SESSION["toastr"]); } ?>

      $("#audit-log").DataTable({
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
        "order": [[ 3, "desc" ]]
      });
    });
  </script>
</body>
</html>
