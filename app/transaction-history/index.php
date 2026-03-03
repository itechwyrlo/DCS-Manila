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
  include_once("../../core/repositories/transaction-history.php");
  include_once("../../app/components/status-badge.php");

  $obj = new TransactionHistory();
  $transactions = $obj->getProcessSteps();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction History</title>

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
              <h1 class="m-0">Transaction History</h1>
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
                  <table id="transaction-history" class="table table-hover table-fixed">
                    <thead>
                      <tr>
                        <th style="width: 10%">Control No</th>
                        <th style="width: 14%">Client Name</th>
                        <th style="width: 8%">Category</th>
                        <th style="width: 8%">Step</th>
                        <th style="width: 12%">Section</th>
                        <th style="width: 10%">Office</th>
                        <th style="width: 10%">Handling Personnel</th>
                        <th style="width: 12%">Date</th>
                        <th style="width: 14%">Remarks</th>
                        <th style="width: 6%"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($transactions as $item) {
                          $control_no = htmlspecialchars($item["control_no"] ?? "") ?: "—";
                          $client_name = htmlspecialchars($item["client_name"] ?? "") ?: "—";
                          $category = htmlspecialchars($item["category"] ?? "") ?: "—";
                          $step = $item["step"] ?? "";
                          $section = htmlspecialchars($item["section"] ?? "") ?: "—";
                          $office = htmlspecialchars($item["office"] ?? "") ?: "—";
                          $personnel = htmlspecialchars(trim($item["personnel"] ?? "")) ?: "—";
                          $created_date = htmlspecialchars($item["created_date"] ?? "");
                          $remarks = htmlspecialchars($item["remarks"] ?? "") ?: "—";
                          $step_badge = getStatusBadgeHtml($step);
                          $view_link = !empty($item["control_no"]) ? "../tracking/index.php?control_no=" . urlencode($item["control_no"]) : "#";
                          echo "
                          <tr>
                            <td class='align-middle'>$control_no</td>
                            <td class='align-middle'>$client_name</td>
                            <td class='align-middle'>$category</td>
                            <td class='align-middle'>$step_badge</td>
                            <td class='align-middle'>$section</td>
                            <td class='align-middle'>$office</td>
                            <td class='align-middle'>$personnel</td>
                            <td class='align-middle'>$created_date</td>
                            <td class='align-middle'>$remarks</td>
                            <td class='align-middle'>
                              <a href='$view_link' class='btn btn-info btn-sm' title='View timeline'" . (empty($item["control_no"]) ? " style='pointer-events:none;'" : "") . ">
                                <i class='fas fa-search'></i>
                              </a>
                            </td>
                          </tr>";
                        }
                        if (empty($transactions)) {
                          echo "<tr><td colspan='10' class='text-center text-muted'>No process steps recorded.</td></tr>";
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
  <script>
    $(function () {
      <?php if (isset($_SESSION["toastr"])) { ?>
        toastr.<?php echo $_SESSION["toastr"]["type"]; ?>('<?php echo addslashes($_SESSION["toastr"]["message"]); ?>');
      <?php unset($_SESSION["toastr"]); } ?>

      $("#transaction-history").DataTable({
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
        "order": [[ 7, "desc" ]],
        "dom": "Bfrtip",
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      });
    });
  </script>
</body>
</html>
