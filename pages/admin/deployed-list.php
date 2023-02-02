<?php
include_once("../../backend/nodes.php");
if (!isset($_SESSION["id"])) {
  header("location: ../../");
}
$user = getUserById($_SESSION['id']);
$fullName = "";
if ($user->mname != null) {
  $fullName = ucwords("$user->fname " . $user->mname[0] . ". $user->lname");
} else {
  $fullName = ucwords("$user->fname  $user->lname");
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OJT Documentation</title>

  <!-- Favicons -->
  <link href="../../assets/img/ojt.png" rel="icon">
  <link href="../../assets/img/ojt.png" rel="apple-touch-icon">


  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="../../assets/vendor/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/vendor/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="../../assets/vendor/datatables-select/css/select.bootstrap4.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.4/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="../../assets/vendor/datatables-datetime/css/dataTables.dateTime.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../assets/vendor/summernote/summernote-bs4.min.css">
  <!-- Template Main CSS File -->
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once("../../components/header.php"); ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once("../../components/sidebar.php") ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title">Students</h4>
            </div>
            <div class="card-body">
              <table id="studentTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>Name</th>
                    <th>Course & Section</th>
                    <th>Rendered</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM users WHERE `role` = 'student' and deployment_id='$user->office_account_id'"
                  );
                  while ($row = mysqli_fetch_object($query)) :
                    $course = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM course WHERE course_id='$row->course_id'"
                      )
                    );
                  ?>
                    <tr>
                      <td><?= ucwords("$row->fname $row->mname $row->lname") ?></td>
                      <td><?= $course->short_name . " 4-" . strtoupper($row->section) ?></td>
                      <td style="text-transform: capitalize;">
                        <?php
                        $labels = getTotalAndRemainingTime($row->id);
                        ?>
                        Total:
                        <label style="color:<?= $labels["rendered"] != "" ? "darkgreen" : "darkred" ?>">
                          <?= $labels["rendered"] != "" ? $labels["rendered"] : "-------------" ?>
                        </label>
                        <br>
                        Remaining:
                        <label style="color:<?= $labels["remaining"] != "" ? "darkred" : "darkgreen" ?>">
                          <?= $labels["remaining"] != "" ? $labels["remaining"] : "Done" ?>
                        </label>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->



</body>

<!-- Vendor JS Files -->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/tinymce/tinymce.min.js"></script>

<script src="../../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../assets/vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../assets/vendor/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../assets/vendor/jszip/jszip.min.js"></script>
<script src="../../assets/vendor/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../assets/vendor/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../assets/vendor/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../../assets/vendor/datatables-select/js/dataTables.select.js"></script>
<script src="../../assets/vendor/datatables-select/js/select.bootstrap4.min.js"></script>

<script src="../../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<!-- Template Main JS File -->
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/swalGlobal.js"></script>
<!-- Summernote -->
<script src="../../assets/vendor/summernote/summernote-bs4.min.js"></script>

<script>
  var table = $("#studentTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,

    "buttons": [{
        extend: 'excel',
        exportOptions: {
          columns: [0, 1, 2]
        }
      },
      {
        extend: 'print',
        title: "",
        exportOptions: {
          columns: [0, 1, 2]
        }
      },
      "searchBuilder",
    ],
    language: {
      searchBuilder: {
        button: 'Advance search',
      }
    },
  })

  table.buttons().container().appendTo('#studentTable_wrapper .col-md-6:eq(0)');
</script>

</html>