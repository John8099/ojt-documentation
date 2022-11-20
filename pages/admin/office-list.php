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

  <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.4/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="../../assets/vendor/datatables-datetime/css/dataTables.dateTime.min.css">

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
              <h4 class="card-title">Admins</h4>
              <button class="btn btn-primary" style="height: 40px;" onclick="saveOffice('add')">Add Office</button>
            </div>
            <div class="card-body">
              <table id="officeTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>Office name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM office ORDER BY `name`"
                  );
                  while ($row = mysqli_fetch_object($query)) :
                  ?>
                    <tr>
                      <td><?= $row->name ?></td>
                      <td class="text-center">
                        <button class="btn btn-warning m-1" onclick="saveOffice('edit', '<?= $row->name ?>', '<?= $row->id ?>')">
                          Edit
                        </button>
                        <button class="btn btn-danger" onclick="handleDelete('<?= $row->id ?>')">
                          Delete
                        </button>
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

<script src="../../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<!-- Template Main JS File -->
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/swalGlobal.js"></script>

<script>
  function saveOffice(action, name = "", id = "") {
    swal.fire({
      input: 'textarea',
      inputLabel: 'Office name',
      inputValue: name.trim(),
      inputPlaceholder: '',
      inputAttributes: {
        'aria-label': 'Type your message here'
      },
      showCancelButton: true,
      cancelButtonColor: "#dc3545",
      confirmButtonText: action === "add" ? "Add office" : "Update office",
      allowOutsideClick: false,
      allowEscapeKey: false,
      showLoaderOnConfirm: true,
      preConfirm: (officeName) => {
        return $.post(
          `../../backend/nodes?action=saveOffice`, {
            id: id,
            officeName: officeName,
            action: action
          },
          (data, status) => {
            const resp = JSON.parse(data)
            if (!resp.success) {
              return swal.showValidationMessage(resp.message)
            }
            return resp
          }).catch(function(e) {
          swal.showValidationMessage(error)
        });
      },
    }).then((data) => {
      if (data.isConfirmed) {
        const resp = JSON.parse(data.value)
        swalAlert('Success!', resp.message, 'success', () => window.location.reload())
      }
    })
  }

  function handleDelete(id) {
    swal.fire({
      title: 'Are you sure',
      icon: 'question',
      html: `you want to delete this?`,
      showDenyButton: true,
      confirmButtonText: 'Yes',
      denyButtonText: 'No',
    }).then((res) => {
      if (res.isConfirmed) {
        swal.showLoading();
        $.post(
          "../../backend/nodes?action=deleteOffice", {
            id: id
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
  }

  $("#officeTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    language: {
      searchBuilder: {
        button: 'Advance search',
      }
    },
    "buttons": [{
        extend: 'excel',
        exportOptions: {
          columns: [0]
        }
      },
      {
        extend: 'print',
        title: '',
        exportOptions: {
          columns: [0]
        }
      },
      {
        extend: "searchBuilder",
        title: "Filter by"
      },
    ],

  }).buttons().container().appendTo('#officeTable_wrapper .col-md-6:eq(0)');
</script>

</html>