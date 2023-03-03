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

  <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.4/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="../assets/vendor/datatables-datetime/css/dataTables.dateTime.min.css">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once("../components/header.php"); ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once("../components/sidebar.php") ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">

          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title">Forms</h4>
            </div>
            <div class="card-body">
              <table id="formsTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>Form name</th>
                    <?php if ($isStudent) : ?>
                      <th>Status</th>
                    <?php endif; ?>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $formData = array();
                  if ($isStudent) {
                    $formData = array(
                      array(
                        "id" => "applicationLetter",
                        "name" => "Application Letter",
                        "location" => "$SERVER_NAME/forms/Application Letter.docx"
                      ),
                      array(
                        "id" => "cv",
                        "name" => "Curriculum Vitae",
                        "location" => "$SERVER_NAME/forms/CURRICULUM VITAE.docx"
                      ),
                      array(
                        "id" => "endorsement",
                        "name" => "Endorsement Letter",
                        "location" => "$SERVER_NAME/forms/Endorsement Letter.docx"
                      ),
                      array(
                        "id" => "waiver",
                        "name" => "Waiver",
                        "location" => "$SERVER_NAME/forms/Waiver.docx"
                      ),
                      array(
                        "id" => "journal",
                        "name" => "Journal of Daily Activities",
                        "location" => "$SERVER_NAME/forms/Journal of Daily Activities.docx"
                      ),
                    );
                  } else {
                    $formData = array(
                      array(
                        "id" => "memo",
                        "name" => "Memorandum of Agreement",
                        "location" => "$SERVER_NAME/forms/Memo.docx"
                      ),

                    );
                  }

                  foreach ($formData as $index => $form) :
                    $forms = mysqli_query(
                      $con,
                      "SELECT * FROM forms WHERE user_id='$_SESSION[id]' and form_type='$form[id]'"
                    );
                    $count = mysqli_num_rows($forms);
                    $docPath = "";

                    if ($count > 0) {
                      $docData = mysqli_fetch_object($forms);
                      $docPath = "$SERVER_NAME/uploads/$_SESSION[id]/$docData->file_name";
                    }
                  ?>
                    <tr>
                      <td style="text-transform: capitalize;"><?= ucwords($form["name"]) ?></td>
                      <?php if ($isStudent) : ?>
                        <td>
                          <p class='text-center'>
                            <?php if ($count > 0 && $form["id"] != "journal") : ?>
                              <span class="badge rounded-pill bg-success px-4" style="font-size: 15px">
                                <em>File submitted</em>
                              </span>
                            <?php elseif ($count == 0 && $form["id"] != "journal") : ?>
                              <span class="badge rounded-pill bg-warning px-4 text-dark" style="font-size: 15px">
                                <em>No file submitted</em>
                              </span>
                            <?php endif;
                            if ($form["id"] == "journal" && $count == 0) : ?>
                              <span class="badge rounded-pill bg-secondary px-4" style="font-size: 15px">
                                <em>No last uploaded</em>
                              </span>
                            <?php elseif ($form["id"] == "journal" && $count > 0) :
                              $getLastUploaded = mysqli_fetch_object(
                                mysqli_query(
                                  $con,
                                  "SELECT * FROM forms WHERE user_id='$_SESSION[id]' and form_type='journal' ORDER BY form_id DESC LIMIT 1"
                                )
                              );
                              $lastUploaded = date("F d, Y h:i:s A", strtotime($getLastUploaded->createdAt));
                            ?>

                              <span class="badge rounded-pill bg-success px-4" style="font-size: 15px">
                                Last uploaded: <br><em><?= $lastUploaded ?></em>
                              </span>
                            <?php endif; ?>
                          </p>
                        <?php endif; ?>
                        </td>
                        <td>
                          <button type="button" class="btn btn-primary m-1" onclick="handleDownloadDoc('<?= $form['location'] ?>')">
                            Download
                          </button>
                          <?php if ($isStudent) : ?>
                            <button type="button" class="btn btn-warning m-1" onclick="handleUploadModal('<?= $form['id'] ?>', '<?= $form['name'] ?>', '<?= $docPath ?>')">
                              Upload
                            </button>
                          <?php endif; ?>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</body>

<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" id="formUploadDoc" enctype="multipart/form-data">
        <input type="text" id="uploadType" name="uploadType" readonly hidden>
        <div class="modal-header">
          <h5 class="modal-title" id="uploadLabel"></h5>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label class="control-label">Document (PDF File Only)</label>
            <div class="custom-file">
              <input type="file" name="pdfFile" class="form-control" onchange="displayPDF(this,$(this))" accept="application/pdf" required>
            </div>
          </div>

          <div class="form-group mt-4">
            <div class="embed-responsive embed-responsive-4by3" id="divIframe" style="display: none;">
              <iframe class="embed-responsive-item" id="pdfPreview" allowfullscreen style="width: 100%; height: 60vh"></iframe>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="btnSave" disabled>Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Vendor JS Files -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
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

<script src="../assets/vendor/datatables-searchbuilder/js/dataTables.searchBuilder.js"></script>
<script src="../assets/vendor/datatables-datetime/js/dataTables.dateTime.min.js"></script>

<!-- Template Main JS File -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/swalGlobal.js"></script>

<script>
  $("#formUploadDoc").on("submit", function(e) {
    e.preventDefault();
    showLoading()

    $.ajax({
      url: "../backend/nodes?action=saveUpload",
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = JSON.parse(data);
        if (resp.success) {
          swal.fire({
            title: 'Success!',
            text: resp.message,
            icon: 'success',
          }).then(() => window.location.reload())
        } else {
          swal.fire({
            title: 'Error!',
            text: resp.message,
            icon: 'error',
          })
        };
      },
      error: function(data) {
        swal.fire({
          title: 'Oops...',
          text: 'Something went wrong.',
          icon: 'error',
        })
      }
    });
  })

  function displayPDF(input, _this) {
    if (input.files && input.files[0]) {
      if (input.files[0].name.split('.').pop().toLowerCase() === "pdf") {
        var reader = new FileReader();
        reader.onload = function(e) {
          $(`#pdfPreview`).attr('src', `${e.target.result}#embedded=true&toolbar=1&navpanes=0`);
          // _this.siblings('.custom-file-label').html(input.files[0].name)
          $("#btnSave").prop("disabled", false)
          $("#divIframe").show()
        }
      } else {
        swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        }).fire({
          icon: 'error',
          title: 'Upload pdf only'
        }).then(() => {
          $("#btnSave").prop("disabled", true)
        })
      }

      reader.readAsDataURL(input.files[0]);
    } else {
      $("#divIframe").hide()
    }
  }

  function handleUploadModal(docId, docName, docPath = null) {
    $("#uploadLabel").html(docName);
    $("#uploadType").val(docId);

    $("#uploadModal").modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })

    if (docPath) {
      $(`#pdfPreview`).attr('src', `${docPath}#embedded=true&toolbar=1&navpanes=0`);
      $("#divIframe").show()
    }
  }

  function handleDownloadDoc(location) {
    window.open(location)
  }

  var table = $("#formsTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": false,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "buttons": [
      "searchBuilder",
    ],
    "columns": [{
      "width": "50%"
    }, {
      "width": "20%"
    }, {
      "width": "30%"
    }],
    language: {
      searchBuilder: {
        button: 'Advance search',
      }
    },
  })

  table.buttons().container().appendTo('#studentTable_wrapper .col-md-6:eq(0)');
</script>

</html>