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
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    small {
      color: #938f8f;
    }
  </style>
</head>

<body>

  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="row justify-content-center">
        <div class="col-12 d-flex flex-column align-items-center justify-content-center">
          <div class="card mb-3">
            <div class="card-header m-2">
              <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Register</h5>
              </div>
            </div>
            <div class="card-body">
              <form class="row g-3" id="register" method="POST">
                <div class="row mt-4">
                  <h5>Personal Data</h5>
                  <hr>
                  <div class="col-lg-4">
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

                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label class="form-label">Section</label>
                          <div class="input-group">
                            <span class="input-group-text">4</span>
                            <input type="text" class="form-control" name="section" maxlength="1" required>
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
                              <option value="<?= $course->course_id ?>"><?= "($course->short_name) " . ucwords($course->name) ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Office <small>(optional)</small></label>
                      <select name="office_id" class="form-select">
                        <option value="" selected></option>
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
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-label">Contact</label>
                      <input type="text" name="contact" class="form-control" required>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label class="form-label">Date of Birth</label>
                          <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label class="form-label">Place of Birth</label>
                          <input type="text" name="place_of_birth" class="form-control" required>
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
                              <option value="<?= $civilStat ?>"><?= $civilStat ?></option>
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
                            foreach ($genders as $genderList) :
                            ?>
                              <option value="<?= $genderList ?>"><?= $genderList ?></option>
                            <?php endforeach; ?>

                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label class="form-label">Height <small>(Metrics)</small></label>
                          <input type="text" name="height" class="form-control" placeholder="eg. 175cm" required>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label class="form-label">Weight <small>(kgs)</small></label>
                          <input type="text" name="weight" class="form-control" placeholder="eg. 53kg" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Special Skills <small>separate with comma (,)</small></label>
                      <input type="text" class="form-control" name="special_skills" required>
                    </div>

                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-label">Physical Disability, if any:</label>
                      <textarea class="form-control" name="physical_disability" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Mental Disability, if any:</label>
                      <textarea class="form-control" name="mental_disability" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Criminal Liability, if any:</label>
                      <textarea class="form-control" name="criminal_liability" rows="3"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label">City Address</label>
                      <input type="text" name="city_add" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label">Provincial Address</label>
                      <input type="text" name="prov_add" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <h5>Incase of Emergency Notify</h5>
                  <hr>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Name</label>
                          <input type="text" name="name" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Relationship</label>
                          <input type="text" name="relationship" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Address</label>
                          <input type="text" name="address" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Contact #</label>
                          <input type="text" name="incase_contact" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <h5>Family Data</h5>
                  <hr>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Father's name</label>
                          <input type="text" name="father" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Father's Occupation</label>
                          <input type="text" name="father_occ" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Mother's name</label>
                          <input type="text" name="mother" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Mother's Occupation</label>
                          <input type="text" name="mother_occ" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <h5>Educational Background</h5>
                  <hr>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Elementary</label>
                          <input type="text" name="elementary" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Date Graduated</label>
                          <input type="date" name="elem_grad" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Secondary</label>
                          <input type="text" name="secondary" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Date Graduated</label>
                          <input type="date" name="sec_grad" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Vocational</label>
                          <input type="text" name="vocational" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Date Graduated</label>
                          <input type="date" name="voc_grad" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">College</label>
                          <input type="text" name="college" class="form-control">
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

                <div class="form-group">
                  <label class="form-label">Email</label>
                  <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="email" class="form-control" name="email" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Password</label>
                  <div class="input-group flex-nowrap">
                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" id="inputPassword" required>
                  </div>

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