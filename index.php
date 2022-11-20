<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OJT Documentation</title>

  <!-- Favicons -->
  <link href="assets/img/ojt.png" rel="icon">
  <link href="assets/img/ojt.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                </div>
                <form class="row g-3" id="login" method="POST">
                  <div class="col-12">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                      <span class="input-group-text">@</span>
                      <input type="email" class="form-control" name="email" required>
                    </div>
                  </div>

                  <div class="col-12">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword" required>
                  </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="showPassword" value="true">
                      <label class="form-check-label">Show password</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Don't have account? <a href="./pages/register">Create an account</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</body>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/js/swalGlobal.js"></script>

<script>
  $("#showPassword").on("change", function(e) {
    if ($(this).is(":checked")) {
      $("#inputPassword").attr("type", "text")
    } else {
      $("#inputPassword").attr("type", "password")
    }
  })

  $("#login").on("submit", function(e) {
    e.preventDefault();
    showLoading();
    $.post(
      `backend/nodes?action=login`,
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        if (resp.isNew && resp.success) {
          swalConfirm(
            "Your account in newly added. would you like to change your password?",
            () => {
              return window.location.href = "pages/users-profile"
            },
            () => {
              return window.location.href = resp.role == "student" ? "pages/student/" : "pages/admin/"
            },
          )
        } else {
          swalAlert(
            resp.success ? 'Success!' : 'Error!',
            resp.message ? resp.message : "",
            resp.success ? 'success' : 'error',
            () => {
              if (resp.success) {
                return window.location.href = resp.role == "student" ? "pages/student/" : "pages/admin/"
              }
            }
          );
        }

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