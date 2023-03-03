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

    <section class="section profile">
      <div class="row">
        <?php
        $student = getStudentFullData($_GET['id']);
        $studentName = ucwords("$student->fname $student->mname $student->lname");
        ?>

        <div class="col-xl-12">

          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title">Student Data</h4>
            </div>

            <div class="card-body pt-3">

              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-toggle="tab" data-target="#profile-edit">Personal</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-toggle="tab" data-target="#family">Family</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-toggle="tab" data-target="#education">Education</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-toggle="tab" data-target="#emergency">Incase of Emergency</button>
                </li>

              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="profile-edit">
                  <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                      <img src="<?= "$SERVER_NAME/profile/" . ($student->avatar ? "$student->avatar" : "default.png") ?>" alt="Profile" style="object-fit: cover;" class="rounded-circle" id="imgProfile">
                      <h2><?= $studentName ?></h2>
                      <?php
                      $officeQuery = mysqli_query(
                        $con,
                        "SELECT * FROM office WHERE id='$student->deployment_id'"
                      );
                      $officeName = "";
                      if (mysqli_num_rows($officeQuery) > 0) {
                        $office = mysqli_fetch_object($officeQuery);
                        if ($office->name) {
                          echo "<h3> $office->name</h3>";
                        }
                      }
                      $courseQuery = mysqli_query(
                        $con,
                        "SELECT * FROM course WHERE course_id='$student->course_id'"
                      );
                      if (mysqli_num_rows($courseQuery) > 0) {
                        $course = mysqli_fetch_object($courseQuery);
                        if ($course->short_name) {
                          echo "<h3> $course->short_name 4-$student->section</h3>";
                        }
                      }
                      ?>

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $student->email ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Contact</div>
                    <div class="col-lg-9 col-md-8"><?= $student->contact ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                    <div class="col-lg-9 col-md-8"><?= $student->date_of_birth ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Place of Birth</div>
                    <div class="col-lg-9 col-md-8"><?= $student->place_of_birth ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Civil Status</div>
                    <div class="col-lg-9 col-md-8"><?= $student->civil_status ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8"><?= $student->gender ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Height</div>
                    <div class="col-lg-9 col-md-8"><?= $student->height ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Weight</div>
                    <div class="col-lg-9 col-md-8"><?= $student->weight ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $student->email ?></div>
                  </div>
                  <?php if ($student->special_skills) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Special Skills</div>
                      <div class="col-lg-9 col-md-8"><?= $student->special_skills ?></div>
                    </div>
                  <?php endif;
                  if ($student->physical_disability) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Physical Disability</div>
                      <div class="col-lg-9 col-md-8"><?= $student->physical_disability ?></div>
                    </div>
                  <?php endif;
                  if ($student->mental_disability) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Mental Disability</div>
                      <div class="col-lg-9 col-md-8"><?= $student->mental_disability ?></div>
                    </div>
                  <?php endif;
                  if ($student->criminal_liability) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Criminal Liability</div>
                      <div class="col-lg-9 col-md-8"><?= $student->criminal_liability ?></div>
                    </div>
                  <?php endif;
                  if ($student->city_address) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">City Address</div>
                      <div class="col-lg-9 col-md-8"><?= $student->city_address ?></div>
                    </div>
                  <?php endif;
                  if ($student->provincial_address) : ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Provincial Address</div>
                      <div class="col-lg-9 col-md-8"><?= $student->provincial_address ?></div>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="tab-pane fade pt-3 profile-overview" id="family">
                  <!-- Family Data Form -->
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Father's name</div>
                    <div class="col-lg-9 col-md-8"><?= $student->father_name ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Father's Occupation</div>
                    <div class="col-lg-9 col-md-8"><?= $student->father_occupation ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Mother's name</div>
                    <div class="col-lg-9 col-md-8"><?= $student->mother_name ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Mother's Occupation</div>
                    <div class="col-lg-9 col-md-8"><?= $student->mother_occupation ?></div>
                  </div>

                </div>

                <div class="tab-pane fade pt-3 profile-overview" id="education">
                  <div class="row">
                    <div class="col-6">
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label">Elementary</div>
                        <div class="col-lg-9 col-md-8"><?= $student->elementary ?></div>
                      </div>
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label">Secondary</div>
                        <div class="col-lg-9 col-md-8"><?= $student->secondary ?></div>
                      </div>
                      <?php if ($student->vocational) : ?>
                        <div class="row">
                          <div class="col-lg-3 col-md-4 label">Vocational</div>
                          <div class="col-lg-9 col-md-8"><?= $student->vocational ?></div>
                        </div>
                      <?php endif; ?>
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label">College</div>
                        <div class="col-lg-9 col-md-8"><?= $student->college ?></div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="row">
                        <div class="col-lg-9 col-md-8"><?= $student->elem_grad ?></div>
                      </div>
                      <div class="row">
                        <div class="col-lg-9 col-md-8"><?= $student->sec_grad ?></div>
                      </div>
                      <?php if ($student->vocational) : ?>
                        <div class="row">
                          <div class="col-lg-9 col-md-8"><?= $student->voc_grad ?></div>
                        </div>
                      <?php endif; ?>
                      <div class="row">
                        <div class="col-lg-9 col-md-8">(Present)</div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="tab-pane fade pt-3 profile-overview" id="emergency">

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Name</div>
                    <div class="col-lg-9 col-md-8"><?= $student->name ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Relationship</div>
                    <div class="col-lg-9 col-md-8"><?= $student->relationship ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?= $student->address ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Contact #</div>
                    <div class="col-lg-9 col-md-8"><?= $student->incase_contact ?></div>
                  </div>

                </div>

              </div><!-- End Bordered Tabs -->
            </div>

          </div>
        </div>

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
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $formData = array(
                    array(
                      "id" => "applicationLetter",
                      "name" => "Application Letter",
                    ),
                    array(
                      "id" => "endorsement",
                      "name" => "Endorsement Letter",
                    ),
                    array(
                      "id" => "cv",
                      "name" => "Curriculum Vitae",
                    ),
                    array(
                      "id" => "waiver",
                      "name" => "Waiver",
                    ),
                  );
                  foreach ($formData as $index => $form) :
                    $forms = mysqli_query(
                      $con,
                      "SELECT * FROM forms WHERE user_id='$student->id' and form_type='$form[id]'"
                    );
                    $count = mysqli_num_rows($forms);
                    $docPath = "";

                    if ($count > 0) {
                      $docData = mysqli_fetch_object($forms);
                      $docPath = "$SERVER_NAME/uploads/$student->id/$docData->file_name";
                    }
                  ?>
                    <tr>
                      <td style="text-transform: capitalize;"><?= ucwords($form["name"]) ?></td>
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
                                "SELECT * FROM forms WHERE user_id='$student->id' and form_type='journal' ORDER BY form_id DESC LIMIT 1"
                              )
                            );
                            $lastUploaded = date("F d, Y h:i:s A", strtotime($getLastUploaded->createdAt));
                          ?>

                            <span class="badge rounded-pill bg-success px-4" style="font-size: 15px">
                              Last uploaded: <br><em><?= $lastUploaded ?></em>
                            </span>
                          <?php endif; ?>
                        </p>
                      </td>
                      <td>
                        <?php if ($count > 0) : ?>
                          <button type="button" class="btn btn-primary m-1" onclick="handlePreviewDocument('<?= $form['name'] ?>', '<?= $docPath ?>')">
                            Preview
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

        <div class="col-xl-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title">Journal of Daily Activities</h4>
            </div>
            <div class="card-body">
              <table id="journalTable" class=" table table-bordered table-hover table-striped">
                <thead>
                  <tr class="bg-dark text-white">
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $journalQ = mysqli_query(
                    $con,
                    "SELECT * FROM forms WHERE user_id='$student->id' and form_type='journal'"
                  );
                  while ($journal = mysqli_fetch_object($journalQ)) :
                    $title = date("F d, Y h:i:s A", strtotime($journal->createdAt));
                    $journalPath = "$SERVER_NAME/uploads/$student->id/$journal->file_name";
                  ?>
                    <tr>
                      <td><?= $title ?></td>
                      <td>
                        <button type="button" class="btn btn-primary" onclick="handlePreviewJournal('<?= $title ?>', '<?= $journalPath ?>')">
                          Preview
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

<div class="modal fade" id="previewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" id="formUploadDoc" enctype="multipart/form-data">
        <input type="text" id="uploadType" name="uploadType" readonly hidden>
        <div class="modal-header">
          <h5 class="modal-title" id="previewModalLabel"></h5>
        </div>
        <div class="modal-body">
          <div class="form-group mt-4">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" id="pdfPreview" allowfullscreen style="width: 100%; height: 60vh"></iframe>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="previewJournalModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" id="formUploadDoc" enctype="multipart/form-data">
        <input type="text" id="uploadType" name="uploadType" readonly hidden>
        <div class="modal-header">
          <h5 class="modal-title" id="previewJournalModalTitle"></h5>
        </div>
        <div class="modal-body">
          <div class="form-group mt-4">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" id="journalPreview" allowfullscreen style="width: 100%; height: 60vh"></iframe>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
  function handlePreviewJournal(title, location) {
    $("#previewJournalModalTitle").html(title)
    $("#previewJournalModal").modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })
    $("#journalPreview").attr('src', `${location}#embedded=true&toolbar=1&navpanes=0`);
  }

  function handlePreviewDocument(docName, docPath = null) {
    $("#previewModalLabel").html(docName);
    $("#previewModal").modal({
      show: true,
      backdrop: 'static',
      keyboard: false,
      focus: true
    })

    if (docPath) {
      $(`#pdfPreview`).attr('src', `${docPath}#embedded=true&toolbar=1&navpanes=0`);
    }
  }

  var formsTable = $("#formsTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "buttons": [
      "searchBuilder",
    ],
    "columns": [{
      "width": "60%"
    }, {
      "width": "20%"
    }, {
      "width": "20%"
    }],
    language: {
      searchBuilder: {
        button: 'Advance search',
      }
    },
  })

  var journalTable = $("#journalTable").DataTable({
    "paging": true,
    "lengthChange": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "buttons": [
      "searchBuilder",
    ],
    "columns": [{
      "width": "80%"
    }, {
      "width": "20%"
    }],
    language: {
      searchBuilder: {
        button: 'Advance search',
      }
    },
  })

  formsTable.buttons().container().appendTo('#formsTable_wrapper .col-md-6:eq(0)');
  journalTable.buttons().container().appendTo('#journalTable_wrapper .col-md-6:eq(0)');
</script>

</html>