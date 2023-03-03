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
              <button class="btn btn-primary" style="height: 40px;" onclick="handleOpenModal()">Add Admin</button>
            </div>
            <div class="card-body">
              <table id="adminTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>Avatar</th>
                    <th>Full name</th>
                    <th>Office</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM users WHERE id != '$user->id' and `role` != 'student'"
                  );
                  while ($row = mysqli_fetch_object($query)) :
                    $officeData = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM office WHERE id ='$row->office_account_id'"
                      )
                    );
                    $adminFullName = ucwords("$row->fname $row->mname $row->lname");
                  ?>
                    <tr>
                      <td class="tableTdAvatar">
                        <img src="<?= "$SERVER_NAME/profile/" . ($row->avatar ? "$row->avatar" : "default.png") ?>" alt="Profile" class="rounded-circle">
                      </td>
                      <td class="tdName">
                        <?= $adminFullName ?>
                      </td>
                      <td><?= ucwords($officeData->name) ?></td>
                      <td><?= $row->email ?></td>
                      <td class="text-center">
                        <button class="btn btn-warning m-1" onclick="handleOpenModal('<?= $row->id ?>')">
                          Edit
                        </button>
                        <button class="btn btn-danger" onclick="handleDelete('<?= $row->id ?>')">
                          Delete
                        </button>
                      </td>
                    </tr>
                    <div class="modal fade" id="editAdminModal<?= $row->id ?>" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Admin</h5>
                          </div>
                          <form id="editAdmin<?= $row->id ?>" method="POST">
                            <input type="text" name="id" value="<?= $row->id ?>" hidden readonly>
                            <div class="modal-body">
                              <div class="form-group">
                                <label class="form-label">First name</label>
                                <input type="text" name="fname" value="<?= ucwords($row->fname) ?>" class="form-control" required>
                              </div>
                              <div class="form-group">
                                <label class="form-label">Middle name</label>
                                <input type="text" name="mname" value="<?= ucwords($row->mname) ?>" class="form-control" required>
                              </div>
                              <div class="form-group">
                                <label class="form-label">Last name</label>
                                <input type="text" name="lname" value="<?= ucwords($row->lname) ?>" class="form-control" required>
                              </div>

                              <div class="form-group">
                                <label class="form-label">Office</label>
                                <select name="office_id" class="form-select">
                                  <option value="">-- Select office --</option>
                                  <?php
                                  $officeQuery = mysqli_query(
                                    $con,
                                    "SELECT * FROM office"
                                  );
                                  while ($office = mysqli_fetch_object($officeQuery)) :
                                  ?>
                                    <option value="<?= $office->id ?>" <?= $row->office_account_id == $office->id ? "selected" : "" ?>><?= $office->name ?></option>
                                  <?php endwhile; ?>
                                </select>
                              </div>

                              <div class="form-group">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                  <span class="input-group-text">@</span>
                                  <input type="email" class="form-control" value="<?= $row->email ?>" name="email" required>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-primary" onclick="handleEdit($(this))">Save</button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="modal fade" id="addAdminModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Admin</h5>
          </div>
          <form id="addAdmin" method="POST">
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label">First name</label>
                <input type="text" name="fname" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-label">Middle name</label>
                <input type="text" name="mname" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-label">Last name</label>
                <input type="text" name="lname" class="form-control" required>
              </div>

              <div class="form-group">
                <label class="form-label">Office</label>
                <select name="office_id" class="form-select">
                  <option value="">-- Select office --</option>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM office"
                  );
                  while ($office = mysqli_fetch_object($query)) :
                  ?>
                    <option value="<?= $office->id ?>"><?= $office->name ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text">@</span>
                  <input type="email" class="form-control" name="email" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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

<script src="../../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<!-- Template Main JS File -->
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/swalGlobal.js"></script>

<script>
  function handleEdit(el) {
    showLoading();
    $.post(
      `../../backend/nodes?action=editAdmin`,
      $(el[0].form).serialize(),
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

  $("#addAdmin").on("submit", function(e) {
    e.preventDefault();
    showLoading();
    $.post(
      `../../backend/nodes?action=addAdmin`,
      $(this).serialize(),
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
  })

  function handleDelete(userId) {
    swal.fire({
      title: 'Are you sure',
      icon: 'question',
      html: `you want to delete this admin?`,
      showDenyButton: true,
      confirmButtonText: 'Yes',
      denyButtonText: 'No',
    }).then((res) => {
      if (res.isConfirmed) {
        showLoading();
        $.post(
          `../../backend/nodes?action=delete`, {
            userId: userId,
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

  function handleOpenModal(modalId = null) {
    if (modalId === null) {
      $(`#addAdminModal`).modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      })
    } else {
      $(`#editAdminModal${modalId}`).modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      })
    }
  }

  $("#adminTable").DataTable({
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
          columns: [1, 2, 3]
        }
      },
      {
        extend: 'print',
        title: "",
        exportOptions: {
          columns: [1, 2, 3]
        }
      },
      "searchBuilder"
    ],
    "searchPanes": {
      cascadePanes: true,
      viewTotal: true
    }
  }).buttons().container().appendTo('#adminTable_wrapper .col-md-6:eq(0)');
</script>

</html>