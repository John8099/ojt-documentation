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
  <!-- summernote -->
  <link rel="stylesheet" href="../../assets/vendor/summernote/summernote-bs4.min.css">

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
              <h4 class="card-title">Attendance</h4>
            </div>
            <div class="card-body">
              <table id="studentTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Section</th>
                    <th>Course</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query(
                    $con,
                    "SELECT * FROM users WHERE `role` = 'student' and deployment_id='$user->office_account_id'"
                  );
                  while ($row = mysqli_fetch_object($query)) :
                    $name = ucwords("$row->fname " . ($row->mname ? $row->mname[0] . "." : "") . " $row->lname");
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
                      <td><?= ucwords($row->fname) ?></td>
                      <td><?= ucwords($row->mname) ?></td>
                      <td><?= ucwords($row->lname) ?></td>
                      <td><?= "4-" . strtoupper($row->section) ?></td>
                      <td><?= $course->short_name ?></td>
                      <td class="text-center">
                        <button class="btn btn-success m-1" type="button" onclick="handleTimeIn('<?= $row->id ?>', '<?= $name ?>')"> Time in</button>
                        <button class="btn btn-warning m-1" type="button" onclick="handleTimeOut('<?= $row->id ?>', '<?= $name ?>')"> Time out</button>
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

  <div class="modal fade" id="timeInModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="timeInTitle">Time In</h5>
        </div>
        <div class="modal-body">
          <input type="text" name="userId" id="timeInUserId" hidden readonly>
          <div class="row ">
            <div class="col-6 d-flex justify-content-center align-items-center">
              <div id="web_cam"></div>
            </div>
            <div class="col-6  d-flex justify-content-center align-items-center">
              <div id="captured">
                <img id="imagePrev" src="../../assets/img/default-image.jpg" style="width: 500px; height: 400px; padding: 10px 0px;">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type=button class="btn btn-secondary" onclick="take_snapshot()">Take Snapshot</button>
          <button type="button" class="btn btn-primary" onclick="saveSnap()">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Time out modal -->
  <div class="modal fade" id="timeOutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form method="POST" id="formTimeOut" novalidate>
          <div class="modal-header">
            <h5 class="modal-title" id="timeOutTitle">Time Out</h5>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" name="userId" id="timeOutUserId" hidden readonly>
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


</body>

<!-- Vendor JS Files -->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/vendor/jquery-validation/jquery.validate.min.js"></script>
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

<script src="../../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<!-- Template Main JS File -->
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/swalGlobal.js"></script>
<!-- Summernote -->
<script src="../../assets/vendor/summernote/summernote-bs4.min.js"></script>
<script>
  $(document).ready(function() {
    var summernoteForm = $('#formTimeOut');
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

  const width = 500
  const height = 400

  $("#formTimeOut").on("submit", function(e) {
    if ($(this).valid()) {
      showLoading();
      $.post(
        `../../backend/nodes?action=timeOut`,
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swalAlert(
            resp.success ? 'Success!' : 'Error!',
            resp.message ? resp.message : "",
            resp.success ? 'success' : 'error',
            () => {
              if (resp.success) {
                $(`#timeOutModal`).modal({
                  show: false
                })
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

    e.preventDefault();
  })

  function handleTimeOut(userId, name) {
    $("#timeOutTitle").html(`<strong>${name}</strong> Time Out`)
    $("#timeOutUserId").val(userId)
    $(`#timeOutModal`).modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })
  }

  function take_snapshot() {
    Webcam.snap(function(data_uri) {
      $("#imagePrev").attr("src", `${data_uri}`);
    });
  }

  function handleTimeIn(userId, name) {
    $("#timeInTitle").html(`<strong>${name}</strong> Time In`)
    $("#timeInUserId").val(userId)
    $(`#timeInModal`).modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })
    Webcam.set({
      width: width,
      height: height,
      image_format: 'jpeg',
      jpeg_quality: 90
    });

    Webcam.attach('#web_cam');
  }

  $('#timeInModal').on('hidden.bs.modal', function() {
    Webcam.reset();
    $("#imagePrev").attr("src", "../../assets/img/default-image.jpg")
  })

  function saveSnap() {
    let base64image = $("#imagePrev").attr('src');

    if (!base64image.includes("default-image.jpg")) {
      showLoading();

      Webcam.upload(base64image, '../../backend/nodes.php?action=uploadTimeInImg', function(code, img_resp) {
        const imgResp = JSON.parse(img_resp);
        $.post(
          `../../backend/nodes?action=timeIn`, {
            userId: $("#timeInUserId").val(),
            imgUrl: imgResp.img_url
          },
          (data, status) => {
            const resp = JSON.parse(data)
            swalAlert(
              resp.success ? 'Success!' : 'Error!',
              resp.message ? resp.message : "",
              resp.success ? 'success' : 'error',
              () => {
                if (resp.success) {
                  $(`#timeInModal`).modal({
                    show: false
                  })
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
      });
    } else {
      swalAlert(
        'Error!',
        "Please take picture first before submitting.",
        'error'
      );
    }

  }

  var table = $("#studentTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "buttons": [
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