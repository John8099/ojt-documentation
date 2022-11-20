<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
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
    <?php
    $headline = "";
    $content = "";

    if (isset($_GET["403"])) {
      $headline = "403";
      $content = "You don't have permission to access this resource.";
    } else if (isset($_GET["404"])) {
      $headline = "404";
      $content = "We could not find the page you were looking for.";
    } else if (isset($_GET["500"])) {
      $headline = "500";
      $content = "We will work on fixing that right away.";
    }
    ?>
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
      <h1><?= $headline ?></h1>
      <h2> <?= $content ?></h2>
      <button class="btn" onclick="return window.history.back()">Go Back</button>
      <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">

    </section>

  </div>
</body>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</html>