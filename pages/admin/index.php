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
      <div class="row">
        <div class="col-lg-12">
          <div class="row">

            <!-- Students Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">Students</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>
                        <?= getStudentCount($user) ?>
                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Admin Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">Admins</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-shield-shaded"></i>
                    </div>
                    <div class="ps-3">
                      <h6>
                        <?= getAdminCount() ?>
                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <div class="col-xxl-3 col-md-6">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Timed in <span>| This Day </span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>
                        <?= getTotalTimeIn($user) ?>
                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <div class="col-xxl-3 col-md-6">
              <div class="card info-card customers-card">

                <div class="card-body">
                  <h5 class="card-title">Timed out <span>| This Day </span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>
                        <?= getTotalTimeOut($user) ?>
                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Reports -->
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Time in & out <span>| Report </span> </h5>
                  <input type="text" id="lineChartData" value='<?= json_encode(getLineChartData($user)) ?>' hidden readonly>
                  <div id="reportsChart"></div>
                </div>
              </div>
            </div>
            <!-- End Reports -->
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->


  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../../assets/js/main.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const dbData = $.parseJSON($("#lineChartData").val());

      let dates = [];
      let data = [{
          name: 'Time in',
          data: [],
        },
        {
          name: 'Time out',
          data: []
        }
      ];

      dbData.forEach(dbDate => {
        if (!dates.includes(dbDate.date)) {
          dates.push(dbDate.date)

          // time in
          data[0].data.push(dbData.filter((e) => e.date == dbDate.date && e.time_id !== null).length)
          // time out
          data[1].data.push(dbData.filter((e) => e.date == dbDate.date && e.time_out !== null).length)
        }

      });

      console.log(dbData)
      console.log(dates)
      console.log(data)


      new ApexCharts(document.querySelector("#reportsChart"), {
        series: data,
        chart: {
          height: 350,
          type: 'area',
          toolbar: {
            show: false
          },
        },
        markers: {
          size: 4
        },
        colors: ['#2eca6a', '#ff771d'],
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.3,
            opacityTo: 0.4,
            stops: [0, 90, 100]
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        yaxis: {
          labels: {
            formatter: (val) => val
          }
        },
        xaxis: {
          type: 'datetime',
          categories: dates,

        },

      }).render();
    });
  </script>

</body>

</html>