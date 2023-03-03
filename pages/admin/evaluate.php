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

$student = getUserById($_GET['id']);
$studentName = $student->mname != null ? ucwords("$student->fname " . $student->mname[0] . ". $student->lname") : ucwords("$student->fname  $student->lname");

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
  <style>
    .v-align-middle {
      vertical-align: middle !important;
    }

    .radio-big {
      width: 19px;
      height: 19px;
    }
  </style>
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
        <div class="col-lg-12 col-sm-12">
          <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
              <h4 class="card-title"><?= $studentName ?> evaluation</h4>
              <button class="btn btn-primary btn-sm" style="height: 40px;" onclick="return window.history.back()">Go Back</button>
            </div>
            <div class="card-body">
              <form class="row g-3" id="ratingForm" method="POST" novalidate>
                <input type="text" name="user_id" value="<?= $student->id ?>" hidden readonly>
                <table class="table table-stripe table-bordered">
                  <thead>
                    <caption class="p-0" style="color: black; text-align: center; caption-side: top; border: 1px solid #dee2e6">
                      <strong>
                        <pre>1=Poor;    2=Fair;    3=Good;    4=Very Good;    5=Excellent</pre>
                      </strong>
                    </caption>
                    <tr>
                      <th rowspan="2" class="v-align-middle text-center">Observe Behavior</th>
                      <th colspan="5" class="v-align-middle text-center">Rating scale</th>
                    </tr>
                    <tr>
                      <th class="v-align-middle text-center">Poor</th>
                      <th class="v-align-middle text-center">Fair</th>
                      <th class="v-align-middle text-center">Good</th>
                      <th class="v-align-middle text-center">Very Good</th>
                      <th class="v-align-middle text-center">Excellent</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach (json_decode(ratingBehavior(), true) as $index => $rating_detail) :
                    ?>
                      <tr>
                        <td><?= intval($index) + 1 . ". " . $rating_detail["title"] ?></td>
                        <?= generatedRadio(5, $rating_detail["name"]) ?>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <caption style="caption-side:bottom">
                    <h4>
                      Mean:
                      <label id="labTotal">0</label>
                    </h4>
                  </caption>
                </table>
                <div class="card-footer d-flex justify-content-end">
                  <button type="button" class="btn btn-primary m-1" id="btnSubmit">Submit</button>
                  <button type="button" class="btn btn-danger m-1" onclick="return window.history.back()">Cancel</button>
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
  <script src="../../assets/vendor/jquery-validation/jquery.validate.min.js"></script>s
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
  <!-- Template Main JS File -->
  <script src="../../assets/js/main.js"></script>
  <script src="../../assets/js/swalGlobal.js"></script>

  <script>
    const validateConfig = {
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
    }

    const ratingBehavior = <?= ratingBehavior() ?>;
    let rating = ratingBehavior;
    $("input[type=radio]").on("change", function(e) {
      const ratingIndex = rating.map((e) => e.name).indexOf(e.currentTarget.name);
      rating[ratingIndex].value = Number(e.target.value)
      updateTotal()
    })

    $("#btnSubmit").on("click", function(e) {
      $(`#ratingForm`).validate(validateConfig);

      if ($(`#ratingForm`).valid()) {
        showLoading();
        $.post(
          `../../backend/nodes?action=saveEvaluation`, {
            evaluationData: rating,
            user_id: $("input[name='user_id']").val()
          },
          (data, status) => {
            const resp = JSON.parse(data)
            swalAlert(
              resp.success ? 'Success!' : 'Error!',
              resp.message ? resp.message : "",
              resp.success ? 'success' : 'error',
              () => {
                window.location.href = resp.location
              }
            );
          }).fail(function(e) {
          swalAlert(
            'Error!',
            e.statusText,
            'error'
          );
        });
      } else {
        swalAlert("Error!", "Please check error fields", "error");
      }
    })

    function updateTotal() {
      let sum = 0;
      let mean = 0;
      rating.forEach(item => {
        sum += item.value;
      });

      mean = (sum / rating.length).toFixed(2)

      $("#labTotal").html(sum)
      $("#interval").html(mean)
      console.log(sum);
      console.log(mean);
    }
  </script>
</body>

</html>