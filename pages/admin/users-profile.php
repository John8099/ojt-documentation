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
                  <button class="nav-link active" data-toggle="tab" data-target="#profile-edit">Profile</button>
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

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">First name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fname" type="text" class="form-control" value="<?= $user->fname ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Middle name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="mname" type="text" class="form-control" value="<?= $user->mname ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Last name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lname" type="text" class="form-control" value="<?= $user->lname ?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" value="<?= $user->email ?>" required>
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
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" id="changePassword">
                    <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>
                    <input type="text" name="role" value="<?= $user->role ?>" hidden readonly>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewPassword" type="password" class="form-control" required>
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

  $("#formUserData").on("submit", function(e) {
    e.preventDefault();
    showLoading();

    $.post(
      `../../backend/nodes?action=updateUserData`,
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

  $("#showPassword").on("change", function(e) {
    if ($(this).is(":checked")) {
      $("input[type='password']").attr("type", "text")
    } else {
      $("input[type='password']").attr("type", "password")
    }
  })
</script>

</html>