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
        <div class="col-lg-12">
          <div class="card">
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

                <li class="nav-item">
                  <button class="nav-link" data-toggle="tab" data-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="POST" id="formUserData">
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="<?= "$SERVER_NAME/profile/" . ($user->avatar ? "$user->avatar" : "default.png") ?>" alt="Profile" style="object-fit: cover;" id="imgProfile">

                        <input type="file" id="profile" name="inputProfile" class="d-none" accept="image/png,image/jpeg" onchange="uploadImg(this,$(this))">

                        <div class="pt-2">
                          <button type="button" class="btn btn-primary btn-sm" id="buttonUpload" title="Upload new profile image">
                            <i class="bi bi-upload"></i>
                          </button>

                          <button type="button" id="buttonRemove" class="btn btn-danger btn-sm" title="Remove my profile image" style="<?= $user->avatar ? "" : "display: none" ?>">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-4">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label class="form-label">First name</label>
                          <input type="text" name="fname" class="form-control" value="<?= $user->fname ?>" required>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Middle name</label>
                          <input type="text" name="mname" class="form-control" value="<?= $user->mname ?>" required>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Last name</label>
                          <input type="text" name="lname" class="form-control" value="<?= $user->lname ?>" required>
                        </div>

                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Section</label>
                              <div class="input-group">
                                <span class="input-group-text">4</span>
                                <input type="text" class="form-control" name="section" value="<?= $user->section ?>" maxlength="1" required>
                              </div>
                            </div>
                          </div>

                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Course</label>
                              <select name="course" class="form-select" required>
                                <option value="" selected disabled></option>
                                <?php
                                $query = mysqli_query(
                                  $con,
                                  "SELECT * FROM course"
                                );
                                while ($course = mysqli_fetch_object($query)) :
                                ?>
                                  <option value="<?= $course->course_id ?>" <?= $course->course_id == $user->course_id ? "selected"  : "" ?>><?= "($course->short_name) " . ucwords($course->name) ?></option>
                                <?php endwhile; ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Office</label>
                          <?php
                          $officeQuery = mysqli_query(
                            $con,
                            "SELECT * FROM office WHERE id='$user->deployment_id'"
                          );
                          $officeName = "";
                          if (mysqli_num_rows($officeQuery) > 0) {
                            $office = mysqli_fetch_object($officeQuery);
                            $officeName = $office->name;
                          }
                          ?>
                          <input type="text" class="form-control" value="<?= $officeName ?>" readonly>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Email</label>
                          <input type="text" name="email" class="form-control" value="<?= $user->email ?>" required>
                        </div>
                      </div>

                      <div class="col-lg-4">
                        <div class="form-group">
                          <label class="form-label">Contact</label>
                          <input type="text" name="contact" class="form-control" value="<?= $user->contact ?>" required>
                        </div>

                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Date of Birth</label>
                              <input type="date" name="date_of_birth" value="<?= $user->date_of_birth ?>" class="form-control" required>
                            </div>
                          </div>

                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Place of Birth</label>
                              <input type="text" name="place_of_birth" value="<?= $user->place_of_birth ?>" class="form-control" required>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Civil Status</label>
                              <select class="form-select" name="civil_status" required>
                                <option value="" selected disabled></option>
                                <?php
                                $civilStats = array("Single", "Married", "Divorced");
                                foreach ($civilStats as $civilStat) :
                                ?>
                                  <option value="<?= $civilStat ?>" <?= $civilStat == $user->civil_status ? "selected" : "" ?>><?= $civilStat ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                          </div>

                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Gender</label>
                              <select name="gender" class="form-select form-control-line" required>
                                <option value="" selected disabled></option>
                                <?php
                                $genders = array(
                                  "Male",
                                  "Female",
                                  "Gay",
                                  "Lesbian",
                                  "Bisexual",
                                  "Prefer not to say"
                                );
                                foreach ($genders as $gender) :
                                ?>
                                  <option value="<?= $gender ?>" <?= $gender == $user->gender ? "selected" : "" ?>><?= $gender ?></option>
                                <?php endforeach; ?>

                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Height <small>(Metrics)</small></label>
                              <input type="text" name="height" class="form-control" value="<?= $user->height ?>" placeholder="eg. 175cm" required>
                            </div>
                          </div>

                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-label">Weight <small>(kgs)</small></label>
                              <input type="text" name="weight" class="form-control" value="<?= $user->weight ?>" placeholder="eg. 53kg" required>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Special Skills <small>separate with comma (,)</small></label>
                          <textarea class="form-control" name="special_skills" rows="4"><?= $user->special_skills ?></textarea>
                        </div>

                      </div>

                      <div class="col-lg-4">
                        <div class="form-group">
                          <label class="form-label">Physical Disability, if any:</label>
                          <textarea class="form-control" name="physical_disability" rows="4"><?= $user->physical_disability ?></textarea>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Mental Disability, if any:</label>
                          <textarea class="form-control" name="mental_disability" rows="4"><?= $user->mental_disability ?></textarea>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Criminal Liability, if any:</label>
                          <textarea class="form-control" name="criminal_liability" rows="4"><?= $user->criminal_liability ?></textarea>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-label">City Address</label>
                          <input type="text" name="city_add" class="form-control" value="<?= $user->city_address ?>">
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label class="form-label">Provincial Address</label>
                          <input type="text" name="prov_add" class="form-control" value="<?= $user->provincial_address ?>">
                        </div>
                      </div>
                    </div>

                    <?php
                    if ($user->office_account_id != null) :
                      $office = mysqli_fetch_object(
                        mysqli_query(
                          $con,
                          "SELECT * FROM office WHERE id='$user->office_account_id'"
                        )
                      );
                    ?>
                      <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Office</label>
                        <div class="col-md-8 col-lg-9">
                          <input type="text" class="form-control" value="<?= $office->name ?>" disabled>
                        </div>
                      </div>
                    <?php endif; ?>
                    <div class="text-center mt-4">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="family">
                  <!-- Family Data Form -->
                  <form method="POST" id="familyForm">
                    <?php $userFamilyData = getUserFamilyData($user->id) ?>
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>

                    <div class="row">
                      <div class="col-12">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Father's name</label>
                              <input type="text" name="father" value="<?= $userFamilyData->father_name ?>" class="form-control">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Father's Occupation</label>
                              <input type="text" name="father_occ" value="<?= $userFamilyData->father_occupation ?>" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Mother's name</label>
                              <input type="text" name="mother" class="form-control" value="<?= $userFamilyData->mother_name ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Mother's Occupation</label>
                              <input type="text" name="mother_occ" class="form-control" value="<?= $userFamilyData->mother_occupation ?>">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center mt-4">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Family Data Form -->

                </div>

                <div class="tab-pane fade pt-3" id="education">
                  <!-- Family Data Form -->
                  <form method="POST" id="educationForm">
                    <?php $userEducationData = getUserEducationData($user->id) ?>
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>

                    <div class="row">
                      <div class="col-12">
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label class="form-label">Elementary</label>
                              <input type="text" name="elementary" class="form-control" value="<?= $userEducationData->elementary ?>" required>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-label">Date Graduated</label>
                              <input type="date" name="elem_grad" class="form-control" value="<?= $userEducationData->elem_grad ?>" required>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label class="form-label">Secondary</label>
                              <input type="text" name="secondary" class="form-control" value="<?= $userEducationData->secondary ?>" required>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-label">Date Graduated</label>
                              <input type="date" name="sec_grad" class="form-control" value="<?= $userEducationData->sec_grad ?>" required>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label class="form-label">Vocational</label>
                              <input type="text" name="vocational" value="<?= $userEducationData->vocational ?>" class="form-control">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-label">Date Graduated</label>
                              <input type="date" name="voc_grad" value="<?= $userEducationData->voc_grad ?>" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label class="form-label">College</label>
                              <input type="text" name="college" class="form-control" value="<?= $userEducationData->college ?>">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-label" style="color:transparent">Date Graduated</label>
                              <label class="form-control" style="border: 0;text-align: center">(Present)</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="text-center mt-4">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Family Data Form -->

                </div>

                <div class="tab-pane fade pt-3" id="emergency">
                  <!-- Family Data Form -->
                  <form method="POST" id="emergencyForm">
                    <?php $userEmergencyData = getUserEmergencyData($user->id) ?>
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>

                    <div class="row">
                      <div class="col-12">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Name</label>
                              <input type="text" name="name" value="<?= $userEmergencyData->name ?>" class="form-control">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label">Relationship</label>
                              <input type="text" name="relationship" value="<?= $userEmergencyData->relationship ?>" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label class="form-label">Address</label>
                              <input type="text" name="address" value="<?= $userEmergencyData->address ?>" class="form-control">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-label">Contact #</label>
                              <input type="text" name="incase_contact" value="<?= $userEmergencyData->contact ?>" class="form-control">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="text-center mt-4">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Family Data Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" id="changePassword">
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>
                    <input type="text" name="role" value="<?= $user->role ?>" hidden readonly>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control inputPass" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control inputPass" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewPassword" type="password" class="form-control inputPass" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label"></label>
                      <div class="col-md-8 col-lg-9">
                        <input class="form-check-input" type="checkbox" id="showPassword" value="true">
                        <label class="form-check-label">Show password</label>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

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


<!-- Template Main JS File -->
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/swalGlobal.js"></script>

<script>
  $("#buttonUpload").on("click", function() {
    $("#profile").trigger("click")
  })

  $("#buttonRemove").on("click", function() {
    showLoading();
    $.get(
      `../../backend/nodes?action=removeProfile`,
      (data, status) => {
        const resp = $.parseJSON(data)
        swal.close();

        if (resp.success) {
          $('#imgProfile').attr('src', resp.img_url);
          $('#headerProfile').attr('src', resp.img_url);
          $(this).hide();
        }
        $("#profile").val("")
      }).fail(function(e) {
      swalAlert(
        'Error!',
        e.statusText,
        'error'
      );
    });
  })

  function uploadImg(input, _this) {
    var formData = new FormData();
    if (input.files && input.files[0]) {
      formData.append("inputProfile", input.files[0]);
      $.ajax({
        url: "../../backend/nodes?action=uploadProfile",
        method: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          showLoading();
        },
        success: function(data) {
          const resp = $.parseJSON(data)
          swal.close();

          if (resp.success) {
            $('#imgProfile').attr('src', resp.img_url);
            $('#headerProfile').attr('src', resp.img_url);
            $("#buttonRemove").show();
          }
          $("#profile").val("")
        }
      });
    } else {
      $('#imgProfile').attr('src', "../../profile/default.png");
    }
  }


  $("#changePassword").on("submit", function(e) {
    e.preventDefault();
    showLoading();
    $.post(
      `../../backend/nodes?action=changePassword`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
          () => {
            if (resp.success) {
              $(".inputPass").attr("type", "password")
              $("#changePassword").get(0).reset()
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

  $("#formUserData").on("submit", function(e) {
    e.preventDefault();
    showLoading();

    $.post(
      `../../backend/nodes?action=updateStudentPersonalData`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
        );
      }).fail(function(e) {
      swalAlert(
        'Error!',
        e.statusText,
        'error'
      );
    });
  })

  $("#familyForm").on("submit", function(e) {
    e.preventDefault();
    showLoading();

    $.post(
      `../../backend/nodes?action=updateFamilyData`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
        );
      }).fail(function(e) {
      swalAlert(
        'Error!',
        e.statusText,
        'error'
      );
    });
  })

  $("#educationForm").on("submit", function(e) {
    e.preventDefault();
    showLoading();

    $.post(
      `../../backend/nodes?action=updateEducationData`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
        );
      }).fail(function(e) {
      swalAlert(
        'Error!',
        e.statusText,
        'error'
      );
    });
  })

  $("#emergencyForm").on("submit", function(e) {
    e.preventDefault();
    showLoading();

    $.post(
      `../../backend/nodes?action=updateEmergencyData`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
        );
      }).fail(function(e) {
      swalAlert(
        'Error!',
        e.statusText,
        'error'
      );
    });
  })

  $("#showPassword").on("change", function(e) {
    if ($(this).is(":checked")) {
      $(".inputPass").attr("type", "text")
    } else {
      $(".inputPass").attr("type", "password")
    }
  })
</script>

</html>