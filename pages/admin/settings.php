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

    <section class="section dashboard">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-8">
          <div class="card mb-3">
            <div class="card-body">
              <form class="row g-3" id="changeSetting" method="POST">
                <?php
                $settings = mysqli_fetch_object(
                  mysqli_query(
                    $con,
                    "SELECT * FROM setting"
                  )
                );
                ?>
                <input type="text" name="id" value="<?= $settings->setting_id ?>" hidden readonly>
                <label class="form-label">Max rendered hours</label>
                <div class="input-group mb-3">
                  <input type="text" name="hours" class="form-control" value="<?= $settings->hours ?>" required>
                  <button type="submit" class="btn btn-primary" >Change</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->


  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
  <!-- Template Main JS File -->
  <script src="../../assets/js/main.js"></script>
  <script src="../../assets/js/swalGlobal.js"></script>

  <script>
    $("#changeSetting").on("submit", function(e) {
      e.preventDefault()
      showLoading();
      $.post(
        "../../backend/nodes?action=updateSetting",
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
  </script>

</body>

</html>