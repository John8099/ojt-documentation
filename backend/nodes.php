<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include("conn.php");
date_default_timezone_set("Asia/Manila");
$dateNow = date("Y-m-d H:i:s");
$SERVER_NAME = "http://$_SERVER[SERVER_NAME]/ojt-documentation";

if (isset($_GET['action'])) {
  try {
    switch ($_GET['action']) {
      case "logout":
        logout();
        break;
      case "login":
        login();
        break;
      case "saveOffice":
        saveOffice();
        break;
      case "deleteOffice":
        deleteOffice();
        break;
      case "addAdmin":
        addAdmin();
        break;
      case "delete":
        deleteUser();
        break;
      case "editAdmin":
        editAdmin();
        break;
      case "register":
        register();
        break;
      case "updateDeployment":
        updateDeployment();
        break;
      case "updateUserData":
        updateUserData();
        break;
      case "changePassword":
        changePassword();
        break;
      case "updateSetting":
        updateSetting();
        break;
      case "timeIn":
        timeIn();
        break;
      case "uploadTimeInImg":
        uploadTimeInImg();
        break;
      case "timeOut":
        timeOut();
        break;
      case "editActivity":
        editActivity();
        break;
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
}
function ratingBehavior()
{
  return json_encode(
    array(
      array(
        "title" => "Attends regularly",
        "name" => "behavior_a",
        "value" => 0
      ),
      array(
        "title" => "Starts the work promptly",
        "name" => "behavior_b",
        "value" => 0
      ),
      array(
        "title" => "Courteous and Considerate",
        "name" => "behavior_c",
        "value" => 0
      ),
      array(
        "title" => "Express his/her ideas well",
        "name" => "behavior_d",
        "value" => 0
      ),
      array(
        "title" => "Listen attentively to trainer",
        "name" => "behavior_e",
        "value" => 0
      ),
      array(
        "title" => "Display interest in his/her work",
        "name" => "behavior_f",
        "value" => 0
      ),
      array(
        "title" => "Careful in handling office facilities and equipment",
        "name" => "behavior_g",
        "value" => 0
      ),
      array(
        "title" => "Works to the best of his/her ability.",
        "name" => "behavior_h",
        "value" => 0
      ),
      array(
        "title" => "Works to develop a variety of skills.",
        "name" => "behavior_i",
        "value" => 0
      ),
      array(
        "title" => "Cooperates well with others.",
        "name" => "behavior_j",
        "value" => 0
      ),
      array(
        "title" => "Is generally  a good follower",
        "name" => "behavior_k",
        "value" => 0
      ),
      array(
        "title" => "Accepts responsibility",
        "name" => "behavior_l",
        "value" => 0
      ),
      array(
        "title" => "Volunteers for an assignment",
        "name" => "behavior_m",
        "value" => 0
      ),
      array(
        "title" => "Makes worth with suggestion",
        "name" => "behavior_n",
        "value" => 0
      ),
      array(
        "title" => "Exhibits orderly/ safe working habits",
        "name" => "behavior_o",
        "value" => 0
      ),
      array(
        "title" => "Applies principles to actual work station",
        "name" => "behavior_p",
        "value" => 0
      ),
      array(
        "title" => "Knowledge in assigned job proceedings",
        "name" => "behavior_q",
        "value" => 0
      ),
      array(
        "title" => "Ability to plan activities",
        "name" => "behavior_r",
        "value" => 0
      ),
      array(
        "title" => "Initiative/ resourcefulness",
        "name" => "behavior_s",
        "value" => 0
      ),
      array(
        "title" => "Judgment and common sense",
        "name" => "behavior_t",
        "value" => 0
      ),
      array(
        "title" => "Interest and good attitude towards work",
        "name" => "behavior_u",
        "value" => 0
      ),
      array(
        "title" => "Prepare report accurately",
        "name" => "behavior_v",
        "value" => 0
      ),
      array(
        "title" => "Submits reports on time",
        "name" => "behavior_w",
        "value" => 0
      ),
    )
  );
}

function generatedRadio($len, $name, $grade = null)
{
  $tds = array();

  for ($i = 1; $i <= $len; $i++) {
    array_push($tds, "<td class='v-align-middle text-center'>
                        <div class='form-group' style='margin: 0;'>
                          <input type='radio' value='$i' name='$name' class='radio-big rating-radio' " . ($grade != null && $i == $grade ? "checked" : "") . " required>
                        </div>
                      </td>");
  }

  return implode("\n", $tds);
}

function editActivity()
{
  global $con, $_POST;

  $query = mysqli_query(
    $con,
    "UPDATE attendance SET activity='" . (nl2br($_POST["did"])) . "' WHERE attendance_id='$_POST[attendanceId]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Activity successfully updated.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function getTotalAndRemainingTime($userId)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users u INNER JOIN attendance a ON u.id = a.user_id WHERE a.time_in is NOT NULL and a.time_out is NOT NULL and u.role = 'student' and id='$userId'"
  );

  $hours = 0;
  $mins = 0;
  $secs = 0;
  $totalTime = 0;

  $arr = array(
    "rendered" => "",
    "remaining" => ""
  );

  while ($row = mysqli_fetch_object($query)) {
    $diff = dateDiff("$row->date $row->time_in", "$row->date $row->time_out");
    $hoursInSecs = $diff['hours'] * 60 * 60;
    $minsInSecs = $diff['minutes'] * 60;

    $totalTime += $hoursInSecs + $minsInSecs + $diff['seconds'];
  }
  $hours = intval($totalTime / 3600);
  $totalTime = $totalTime - ($hours * 3600);
  $mins = intval($totalTime / 60);
  $secs = $totalTime - ($mins * 60);

  $arr["rendered"] = "$hours hrs $mins mins $secs secs";

  $getTotalHours = mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM setting"
    )
  )->hours - 1;

  $remainHours = $getTotalHours < 0 ? 0 : $getTotalHours;
  $remainMin = 59;
  $remainSec = 60;

  if ($secs != 0) {
    $remainSec -= $secs;
  }

  if ($mins != 0) {
    $remainMin -= $mins;
  }

  if ($hours != 0) {
    $remainHours -= $hours;
  }
  $arr["remaining"] = "$remainHours hrs $remainMin mins $remainSec secs";

  return $arr;
}

function getLineChartData($currentUser = null)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM attendance a INNER JOIN users u ON a.user_id = u.id " . ($currentUser != null && $currentUser->role == "admin" && $currentUser->office_account_id != null ? "WHERE u.deployment_id='$currentUser->office_account_id'" : "") . ""
  );
  $error = mysqli_error($con);

  $data = array();

  while ($row = mysqli_fetch_object($query)) {
    array_push($data, $row);
  }

  return $data;
}

function getTotalTimeOut($currentUser = null)
{
  global $con;

  $dateNow = date("Y-m-d");

  $query = mysqli_query(
    $con,
    "SELECT * FROM attendance a INNER JOIN users u ON a.user_id=u.id WHERE u.role='student' and a.date='$dateNow' and a.time_out is NOT NULL " . ($currentUser != null && $currentUser->role == "admin" && $currentUser->office_account_id != null ? " and u.deployment_id='$currentUser->office_account_id'" : "") . ""
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_num_rows($query);
  }

  return 0;
}

function getTotalTimeIn($currentUser = null)
{
  global $con;

  $dateNow = date("Y-m-d");

  $query = mysqli_query(
    $con,
    "SELECT * FROM attendance a INNER JOIN users u ON a.user_id=u.id WHERE u.role='student' and a.date='$dateNow' " . ($currentUser != null && $currentUser->role == "admin" && $currentUser->office_account_id != null ? " and u.deployment_id='$currentUser->office_account_id'" : "") . ""
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_num_rows($query);
  }

  return 0;
}

function getAdminCount()
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE role='admin'"
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_num_rows($query);
  }

  return 0;
}

function getStudentCount($currentUser = null)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE role='student'" . ($currentUser != null && $currentUser->role == "admin" && $currentUser->office_account_id != null ? " and deployment_id='$currentUser->office_account_id'" : "") . ""
  );

  if (mysqli_num_rows($query) > 0) {
    return mysqli_num_rows($query);
  }

  return 0;
}

function dateDiff($start, $end)
{
  $date1 = strtotime($start);
  $date2 = strtotime($end);

  $diff = abs($date2 - $date1);


  $years = floor($diff / (365 * 60 * 60 * 24));


  $months = floor(($diff - $years * 365 * 60 * 60 * 24)
    / (30 * 60 * 60 * 24));


  $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
    $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));


  $hours = floor(($diff - $years * 365 * 60 * 60 * 24
    - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
    / (60 * 60));


  $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
    - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
    - $hours * 60 * 60) / 60);


  $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
    - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
    - $hours * 60 * 60 - $minutes * 60));

  return (array(
    "year" => $years,
    "months" => $months,
    "days" => $days,
    "hours" => $hours,
    "minutes" => $minutes,
    "seconds" => $seconds
  ));
}

function timeOut()
{
  global $con, $_POST;

  $userId = $_POST["userId"];
  $did = nl2br($_POST["did"]);

  $getAttendanceQ = mysqli_query(
    $con,
    "SELECT * FROM attendance WHERE `user_id`='$userId' and activity is NULL and time_out is NULL"
  );

  $query = null;

  while ($row = mysqli_fetch_object($getAttendanceQ)) {
    $dbTimeInDate = $row->date;
    $dbTimeInTime = $row->time_in;
    $dateNow = date("Y-m-d");

    if ($dbTimeInDate == $dateNow) {
      $timeOutTime = date("H:i:s");
      $query = mysqli_query(
        $con,
        "UPDATE attendance SET time_out='$timeOutTime', activity='$did' WHERE attendance_id='$row->attendance_id'"
      );
    } else {
      $timeOutTime = date("H:i:s", strtotime("$dbTimeInDate $dbTimeInTime" . " +8 hours"));
      $query = mysqli_query(
        $con,
        "UPDATE attendance SET time_out='$timeOutTime', activity='No Time out' WHERE attendance_id='$row->attendance_id'"
      );
    }
  }

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Successfully time out.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function uploadTimeInImg()
{
  global $_FILES;

  $file = $_FILES['webcam'];

  $response["img_url"] = "";
  if (intval($file["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($file['name']);
    $target_dir = "../media/";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], "$target_dir/$uploadFile")) {
      $response["img_url"] = "/media/$uploadFile";
    }
  }

  returnResponse($response);
}

function timeIn()
{
  global $con, $_POST;
  $dateNow = date("Y-m-d");
  $timeNow = date("H:i:s");

  $userId = $_POST["userId"];
  $imgUrl = $_POST["imgUrl"];

  if (!isTimeIn($userId)) {
    $query = mysqli_query(
      $con,
      "INSERT INTO attendance(`user_id`, `date`, time_in, `image`) VALUES('$userId', '$dateNow', '$timeNow', '$imgUrl')"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Successfully time in.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($con);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "You already time in. Please time out before timing in.";
  }

  returnResponse($response);
}

function isTimeIn($user_id)
{
  global $con;

  $dateNow = date("Y-m-d");

  $query = mysqli_query(
    $con,
    "SELECT * FROM attendance WHERE `user_id`='$user_id' and `date`='$dateNow'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
}

function updateSetting()
{
  global $con, $_POST;

  $query = mysqli_query(
    $con,
    "UPDATE setting SET `hours`='$_POST[hours]' WHERE setting_id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Setting updated successfully";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function changePassword()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $currentPassword = $_POST["password"];
  $newPassword = $_POST["newPassword"];
  $renewPassword = $_POST["renewPassword"];
  $role = $_POST["role"];

  $verifyPassword = json_decode(validatePassword($id, $newPassword, $renewPassword, $currentPassword));
  if ($verifyPassword->validate) {
    $passwordHash = $verifyPassword->hash;
    $query = mysqli_query(
      $con,
      "UPDATE users SET `password`='$passwordHash' " . ($role == "admin" ? ", isNew='0' " : "") . " WHERE id='$id'"
    );
    if ($query) {
      $response["success"] = true;
      $response["message"] = "Password successfully updated.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($con);
    }
  } else {
    $response["success"] = false;
    $response["message"] = $verifyPassword->message;
  }

  returnResponse($response);
}

function validatePassword($user_id, $password, $confirm_password, $old_password)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE id='$user_id'"
  );

  $arr = array();

  if (mysqli_num_rows($query) > 0) {
    $user = getUserById($user_id);

    if ($password == $confirm_password && $password != $old_password) {
      if (password_verify($old_password, $user->password)) {
        $arr["validate"] = true;
        $arr["hash"] = password_hash($password, PASSWORD_ARGON2I);
      } else {
        $arr["validate"] = false;
        $arr["message"] = "Current password error";
      }
    } else if ($password == $old_password) {
      $arr["validate"] = false;
      $arr["message"] = "<strong>Current password</strong> and <strong>New password</strong> should not be the same.";
    } else {
      $arr["validate"] = false;
      $arr["message"] = "<strong>New password</strong> and <strong>Confirm password</strong> not match.";
    }
  } else {
    $arr["validate"] = false;
    $arr["message"] = "Could not find user.";
  }
  return json_encode($arr);
}

function updateUserData()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];

  if (!isEmailExist($email, $id)) {
    $query = mysqli_query(
      $con,
      "UPDATE users SET fname='$fname', lname='$lname', mname='$mname', email='$email' WHERE id='$id'"
    );
    if ($query) {
      $response["success"] = true;
      $response["message"] = "Profile successfully updated";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($con);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist";
  }

  returnResponse($response);
}

function updateDeployment()
{
  global $con, $_POST;

  $officeId = $_POST["officeId"];
  $userIds = $_POST["userIds"];

  $query = mysqli_query(
    $con,
    "UPDATE users SET deployment_id='$officeId' WHERE id IN (" . implode(',', $userIds) . ")"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Successfully deployed all selected students";
  } else {
    $response["success"] = false;
    $response["message"] = "Error occurred while deploying other student. Please try again later";
  }

  returnResponse($response);
}

function register()
{
  global $con, $_POST;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $section = $_POST["section"];
  $course = $_POST["course"];
  $office_id = isset($_POST["office_id"]) ? $_POST["office_id"] : null;
  $email = $_POST["email"];

  $password = password_hash($_POST["password"], PASSWORD_ARGON2I);

  if (!isEmailExist($email)) {
    $query = mysqli_query(
      $con,
      "INSERT INTO users(fname, mname, lname, course_id, section, deployment_id, `role`, email, `password`) VALUES('$fname', '$mname', '$lname', '$course', '$section', '$office_id', 'student', '$email', '$password')"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Successfully registered. You can now login.";
    } else {
      $response["success"] = false;
      $response["message"] = "Error occurred Please try again later.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist";
  }

  returnResponse($response);
}

function getAllOffice()
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM office"
  );

  $data = array();

  if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_object($query)) {
      array_push($data, $row);
    }
  }
  return $data;
}

function editAdmin()
{
  global $con, $_POST;

  $id = $_POST["id"];

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $office_id = $_POST["office_id"];
  $email = $_POST["email"];

  $password = password_hash($email, PASSWORD_ARGON2I);

  $user = getUserById($id);

  if (!isEmailExist($email, $id)) {
    $query = mysqli_query(
      $con,
      "UPDATE users SET fname='$fname', mname='$mname', lname='$lname', office_account_id='$office_id' " . ($user->isNew == "0" ? "" : ", password='$password'") . " WHERE id='$id'"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Admin successfully updated.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($con);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already existed.";
  }

  returnResponse($response);
}

function deleteUser()
{
  global $con, $_POST;

  $query = mysqli_query(
    $con,
    "DELETE FROM users WHERE id='$_POST[userId]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Admin successfully deleted.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function addAdmin()
{
  global $con, $_POST;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $office_id = $_POST["office_id"];
  $email = $_POST["email"];

  $password = password_hash($email, PASSWORD_ARGON2I);

  if (!isEmailExist($email)) {
    $query = mysqli_query(
      $con,
      "INSERT INTO users(fname, mname, lname, email, `password`, `role`, isNew, office_account_id) VALUES('$fname', '$mname', '$lname', '$email', '$password', 'admin', '1', '$office_id')"
    );

    if ($query) {
      $response["success"] = true;
      $response["message"] = "Admin successfully added.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($con);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already existed.";
  }

  returnResponse($response);
}

function isEmailExist($email, $id = null)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE " . ($id != null ? "id != '$id' and " : "") . " email = '$email'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
  return false;
}

function deleteOffice()
{
  global $con, $_POST;

  $query = mysqli_query(
    $con,
    "DELETE FROM office WHERE id='$_POST[id]'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Office successfully deleted.";
  } else {
    $response["success"] = false;
    $response["message"] = "An error occurred. Please try again later";
  }

  returnResponse($response);
}

function saveOffice()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $officeName = $_POST["officeName"];
  $action = $_POST["action"];

  $queryStr = "";

  if (!isOfficeExist(strtolower($officeName), $id)) {
    if ($action == "add") {
      $queryStr = "INSERT INTO office(`name`) VALUES('$officeName')";
    } elseif ($action == "edit") {
      $queryStr = "UPDATE office SET `name`='$officeName' WHERE id='$id'";
    } else {
      $response["success"] = false;
      $response["message"] = "An error occurred. Please try again later";
    }

    if ($queryStr != "") {
      $query = mysqli_query($con, $queryStr);
      if ($query) {
        $response["success"] = true;
        $response["message"] = "Office successfully added.";
      } else {
        $response["success"] = false;
        $response["message"] = "An error occurred. Please try again later";
      }
    }
  } else {
    $response["success"] = false;
    $response["message"] = "$officeName already exist.";
  }

  returnResponse($response);
}

function isOfficeExist($name, $id)
{
  global $con;

  $query = mysqli_query(
    $con,
    "SELECT * FROM office WHERE " . ($id == "" ? "" : " id != '$id' and ") . " LOWER(`name`) LIKE '%$name%'"
  );

  if (mysqli_num_rows($query) > 0) {
    return true;
  }
  return false;
}

function login()
{
  global $con, $_POST, $_SESSION;

  $email = $_POST["email"];
  $password = $_POST["password"];

  $query = mysqli_query(
    $con,
    "SELECT * FROM users WHERE email='$email'"
  );

  if (mysqli_num_rows($query) > 0) {
    $user = getUserByEmail($email);

    if (password_verify($password, $user->password)) {
      $response["success"] = true;
      $response["role"] = $user->role;
      $response["isNew"] = $user->isNew ? true : false;
      $_SESSION["id"] = $user->id;
    } else {
      $response["success"] = false;
      $response["message"] = "Password not match";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "User doesn't exist.";
  }

  returnResponse($response);
}

function getUserByEmail($email)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM users WHERE email = '$email'"
    )
  );
}

function getUserById($id)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM users WHERE id = '$id'"
    )
  );
}

function logout()
{
  global $_SESSION;
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();
  header("location: ../");
}

function returnResponse($params)
{
  print_r(
    json_encode($params)
  );
}
