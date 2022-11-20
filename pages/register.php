<?php
include("../backend/nodes.php");
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
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>

  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">
          <div class="card mb-3">
            <div class="card-body">
              <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Register</h5>
              </div>
              <form class="row g-3" id="register" method="POST">
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
                  <label class="form-label">Section</label>
                  <div class="input-group">
                    <span class="input-group-text">4</span>
                    <input type="text" class="form-control" name="section" maxlength="1" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Course</label>
                  <select name="course" class="form-select" required>
                    <option value="">-- Select course --</option>
                    <?php
                    $query = mysqli_query(
                      $con,
                      "SELECT * FROM course"
                    );
                    while ($course = mysqli_fetch_object($query)) :
                    ?>
                      <option value="<?= $course->course_id ?>"><?= "($course->short_name) " . ucwords($course->name) ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Office <small>(optional)</small></label>
                  <select name="office_id" class="form-select" >
                    <option value="" selected disabled>-- Select office --</option>
                    <?php
                    $officeQuery = mysqli_query(
                      $con,
                      "SELECT * FROM office"
                    );
                    while ($office = mysqli_fetch_object($officeQuery)) :
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

                <div class="form-group">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="inputPassword" required>
                </div>

                <div class="form-group">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="showPassword" value="true">
                    <label class="form-check-label">Show password</label>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary w-100" type="submit">Register</button>
                </div>
                <div class="form-group">
                  <p class="small mb-0">Already have an account? <a href="../">Log in</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</body>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script src="../assets/js/swalGlobal.js"></script>

<script>
  $("#showPassword").on("change", function(e) {
    if ($(this).is(":checked")) {
      $("#inputPassword").attr("type", "text")
    } else {
      $("#inputPassword").attr("type", "password")
    }
  })

  $("#register").on("submit", function(e) {
    e.preventDefault();
    showLoading();
    $.post(
      `../backend/nodes?action=register`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swalAlert(
          resp.success ? 'Success!' : 'Error!',
          resp.message ? resp.message : "",
          resp.success ? 'success' : 'error',
          () => {
            if (resp.success) {
              return window.location.href = "../"
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
</script>

</html>