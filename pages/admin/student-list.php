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
              <button class="btn btn-primary" style="height: 40px;" onclick="updateDeployment()">Update deployment</button>
            </div>
            <div class="card-body">
              <table id="studentTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th style="width: 30px;"></th>
                    <th>Id</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Section</th>
                    <th>Course</th>
                    <th>Deployment office</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM users WHERE `role` = 'student'"
                  );
                  while ($row = mysqli_fetch_object($query)) :
                    $course = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM course WHERE course_id='$row->course_id'"
                      )
                    );
                    $office = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM office WHERE id='$row->deployment_id'"
                      )
                    );
                  ?>
                    <tr>
                      <td></td>
                      <td><?= $row->id ?></td>
                      <td><?= ucwords($row->fname) ?></td>
                      <td><?= ucwords($row->mname) ?></td>
                      <td><?= ucwords($row->lname) ?></td>
                      <td><?= "4-" . strtoupper($row->section) ?></td>
                      <td><?= $course->short_name ?></td>
                      <td> <?= !$row->deployment_id ? "" : $office->name ?> </td>
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
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0,
      },
      {
        targets: 1,
        visible: false,
        searchable: false,
      },
    ],
    select: {
      style: 'multi',
    },
    "buttons": [{
        text: 'Deselect all',
        action: function() {
          table.rows(['.selected']).deselect()
        }
      },
      {
        extend: 'excel',
        exportOptions: {
          columns: [2, 3, 4, 5, 6, 7]
        }
      },
      {
        extend: 'print',
        title: "",
        exportOptions: {
          columns: [2, 3, 4, 5, 6, 7]
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

  function updateDeployment() {
    if (table.rows(['.selected']).data().count() > 0) {

      const jsonOffice = $.parseJSON('<?= getAllOffice() ? json_encode(getAllOffice()) : '[]' ?>')
      let options = "<option value='' disabled selected>-----</option>"
      options += jsonOffice.map((data) => {
        return `<option value="${data.id}">
                    ${data.name}
                  </option>`
      });
      const html = `
            <select id="inputOffice" class="form-select" style="text-transform: capitalize">
              ${jsonOffice.length == 0  ? "<option value='' disabled selected> No available office </option>" : options}
            </select>
        `;
      swal.fire({
        icon: 'question',
        title: "Select office",
        html: html,
        showDenyButton: true,
        confirmButtonText: 'Submit',
        denyButtonText: 'Cancel',
        allowOutsideClick: false,
        allowEscapeKey: false,
        preConfirm: () => {
          if (!$("#inputOffice").val()) {
            $("#inputOffice").addClass("is-invalid");
            swal.showValidationMessage("please select office to deploy")
            return false;
          }
          return $("#inputOffice").val()
        },
      }).then((res) => {
        if (res.isConfirmed) {
          showLoading();
          const officeId = res.value;
          const userIds = $.map(table.rows(['.selected']).data(), (data) => data[1])

          $.post(
            `../../backend/nodes?action=updateDeployment`, {
              officeId: officeId,
              userIds: userIds
            },
            (data, status) => {
              const resp = JSON.parse(data)
              swalAlert(
                resp.success ? 'Success!' : 'Error!',
                resp.message ? resp.message : "",
                resp.success ? 'success' : 'error',
                () => {
                  if (resp.success) {
                    return window.location.reload()
                  }
                }
              );
            }).fail(function(e) {
            swalAlert(
              'Error!',
              e.statusText,
              'error'
            );
          });

        }
      })
      if (jsonOffice.length == 0) {
        swal.getConfirmButton().disabled = true
      }
    } else {
      swalAlert('Error!', "No selected row on table", 'error');
    }
  }
</script>

</html>