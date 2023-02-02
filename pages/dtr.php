<?php
include_once("../backend/nodes.php");
if (!isset($_SESSION["id"])) {
  header("location: ../");
}
$user = getUserById($_SESSION['id']);
$fullName = "";
if ($user->mname != null) {
  $fullName = ucwords("$user->fname " . $user->mname[0] . ". $user->lname");
} else {
  $fullName = ucwords("$user->fname  $user->lname");
};

$isStudent = $user->role == "student" ? true : false;
$isStudentDeployed = $user->deployment_id != null ? true : false;
$labels = getTotalAndRemainingTime($user->id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OJT Documentation</title>

  <!-- Favicons -->
  <link href="../assets/img/ojt.png" rel="icon">
  <link href="../assets/img/ojt.png" rel="apple-touch-icon">


  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="../assets/vendor/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/vendor/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="../assets/vendor/datatables-select/css/select.bootstrap4.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.4/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="../assets/vendor/datatables-datetime/css/dataTables.dateTime.min.css">
  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <!-- summernote -->
  <link rel="stylesheet" href="../assets/vendor/summernote/summernote-bs4.min.css">
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once("../components/header.php"); ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once("../components/sidebar.php") ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title"><?= $isStudent ? "My DTR" : "Students" ?></h4>
              <?php
              if ($isStudent) :
              ?>
                <p>
                  Total:
                  <label style="color:<?= $labels["rendered"] != "" ? "darkgreen" : "darkred" ?>">
                    <?= $labels["rendered"] != "" ? $labels["rendered"] : "-------------" ?>
                  </label>
                  <br>
                  Remaining:
                  <label style="color:<?= $labels["remaining"] != "" ? "darkred" : "darkgreen" ?>">
                    <?= $labels["remaining"] != "" ? $labels["remaining"] : "Done" ?>
                  </label>
                </p>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <table id="studentTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <?php if (!$isStudent) : ?>
                      <th>Name</th>
                      <th>Course and Section</th>
                    <?php endif; ?>
                    <th>Time in</th>
                    <th>Time out</th>
                    <th>Image</th>
                    <th>Activity</th>
                    <th>Time consume</th>
                    <?php if ($isStudent) : ?>
                      <th>Action</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = null;
                  if ($isStudent && $isStudentDeployed) {
                    $query = mysqli_query(
                      $con,
                      "SELECT * FROM users u INNER JOIN attendance a ON u.id = a.user_id WHERE a.time_in is NOT NULL and a.time_out is NOT NULL and u.role = 'student' and id='$user->id'"
                    );
                  } else {
                    $query = mysqli_query(
                      $con,
                      "SELECT * FROM users u INNER JOIN attendance a ON u.id = a.user_id WHERE a.time_in is NOT NULL and a.time_out is NOT NULL and u.role = 'student'" . ($user->role == "admin" ? " and u.deployment_id='$user->office_account_id'" : "") . ""
                    );
                  }
                  if (mysqli_num_rows($query) > 0) :
                    $hours = 0;
                    $mins = 0;
                    $secs = 0;
                    $totalTime = 0;
                    while ($row = mysqli_fetch_object($query)) :
                      $name = ucwords("$row->fname " . ($row->mname ? $row->mname[0] . "." : "") . " $row->lname");
                      $course = mysqli_fetch_object(
                        mysqli_query(
                          $con,
                          "SELECT * FROM course WHERE course_id='$row->course_id'"
                        )
                      );
                      $diff = dateDiff("$row->date $row->time_in", "$row->date $row->time_out");
                      $hoursInSecs = $diff['hours'] * 60 * 60;
                      $minsInSecs = $diff['minutes'] * 60;

                      $totalTime += $hoursInSecs + $minsInSecs + $diff['seconds'];

                      $disabled = date("Y-m-d") != $row->date ? "disabled" : "";
                  ?>
                      <tr>
                        <?php if (!$isStudent) : ?>
                          <td><?= ucwords("$row->fname $row->mname $row->lname") ?></td>
                          <td><?= $course->short_name . " 4-" . strtoupper($row->section) ?></td>
                        <?php endif; ?>
                        <td><?= "$row->date $row->time_in" ?></td>
                        <td><?= "$row->date $row->time_out" ?></td>
                        <td style="width: 200px;">
                          <img src="<?= $SERVER_NAME . $row->image ?>" class="img-fluid">
                        </td>
                        <td>
                          <?= nl2br($row->activity) ?>
                        </td>
                        <td>
                          <?= "$diff[hours] hrs $diff[minutes] mins $diff[seconds] secs" ?>
                        </td>
                        <?php if ($isStudent) : ?>
                          <td class="text-center">
                            <input type="text" value='<?= nl2br($row->activity) ?>' id="activity<?= $row->attendance_id ?>" hidden readonly>
                            <button type="button" class="btn btn-primary" <?= $disabled ?> onclick="handleEditActivity('<?= $row->attendance_id ?>', '<?= $name ?>')">Edit Activity</button>
                          </td>
                        <?php endif; ?>
                      </tr>

                  <?php endwhile;
                  endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <?php
  if ($isStudent) :
  ?>
    <div class="modal fade" id="modalEditActivity">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <form method="POST" id="formEditAct" novalidate>
            <div class="modal-header">
              <h5 class="modal-title" id="editActivityTitle"></h5>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <input type="text" name="attendanceId" hidden readonly>
                <label class="control-label mb-2">
                  <h5>
                    <strong>
                      What you did this day?
                    </strong>
                  </h5>
                </label>
                <textarea type="text" class="form-control form-control-sm summernote" name="did" required></textarea>
              </div>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>


</body>

<!-- Vendor JS Files -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/tinymce/tinymce.min.js"></script>

<script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/vendor/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/vendor/jszip/jszip.min.js"></script>
<script src="../assets/vendor/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/vendor/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../assets/vendor/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../assets/vendor/datatables-select/js/dataTables.select.js"></script>
<script src="../assets/vendor/datatables-select/js/select.bootstrap4.min.js"></script>

<script src="../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<!-- Template Main JS File -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/swalGlobal.js"></script>
<!-- Summernote -->
<script src="../assets/vendor/summernote/summernote-bs4.min.js"></script>

<script>
  $(document).ready(function() {
    var summernoteForm = $('#formEditAct');
    var summernoteElement = $('.summernote');

    var summernoteValidator = summernoteForm.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      ignore: ':hidden:not(.summernote),.note-editable.card-block',
      errorElement: "span",
      errorPlacement: function(error, element) {
        error.addClass("invalid-feedback");
        element.closest(".form-group").append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass("is-invalid");
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
      },
    });

    summernoteElement.summernote({
      height: 200,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ol', 'ul', 'paragraph', 'height']],
        ['table', ['table']],
        ['insert', ['link', 'picture']],
        ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
      ],
      callbacks: {
        onChange: function(contents, $editable) {
          summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);
          summernoteValidator.element(summernoteElement);
        }
      }
    });
  })

  function handleEditActivity(attendanceId, name) {
    $("input[name='attendanceId']").val(attendanceId)
    $(`#editActivityTitle`).html(`Edit <strong>${name}</strong> Activity`)
    $(".summernote").summernote("code", $(`#activity${attendanceId}`).val());
    $(`#modalEditActivity`).modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })
  }

  $("#formEditAct").on("submit", function(e) {
    if ($(this).valid()) {
      showLoading();
      $.post(
        `../backend/nodes?action=editActivity`,
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swalAlert(
            resp.success ? 'Success!' : 'Error!',
            resp.message ? resp.message : "",
            resp.success ? 'success' : 'error',
            () => {
              if (resp.success) {
                window.location.reload()
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
    e.preventDefault()
  })


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
          columns: <?= $isStudent ? json_encode([0, 1, 4]) : json_encode([0, 1, 2, 3, 6]) ?>
        }
      },
      {
        extend: 'print',
        title: "",
        exportOptions: {
          columns: <?= $isStudent ? json_encode([0, 1, 4]) : json_encode([0, 1, 2, 3, 6]) ?>
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