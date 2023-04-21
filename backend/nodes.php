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
      case "updateStudentPersonalData":
        updateStudentPersonalData();
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
      case "editTimeOut":
        editTimeOut();
        break;
      case "editActivity":
        editActivity();
        break;
      case "uploadProfile":
        uploadProfile();
        break;
      case "removeProfile":
        removeProfile();
        break;
      case "updateFamilyData":
        updateFamilyData();
        break;
      case "updateEducationData":
        updateEducationData();
        break;
      case "updateEmergencyData":
        updateEmergencyData();
        break;
      case "saveUpload":
        saveUpload();
        break;
      case "saveEvaluation":
        saveEvaluation();
        break;
      case "getNotificationCount":
        getNotificationCount();
        break;
      case "updateNotificationCount":
        updateNotificationCount();
        break;
      case "getNotificationData":
        getNotificationData();
        break;
      case "getAllOffice":
        getAllOffice();
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

function getNotificationData()
{
  global $con, $_SESSION, $SERVER_NAME;

  $notificationQ = mysqli_query(
    $con,
    "SELECT * FROM `notification` WHERE admin_id='$_SESSION[id]' ORDER BY notification_id DESC LIMIT 5"
  );

  $html = '';
  if (mysqli_num_rows($notificationQ) > 0) {
    while ($notificationData = mysqli_fetch_object($notificationQ)) {
      $studentData = getUserById($notificationData->user_id);
      if ($studentData) {
        $studentFullName = "";

        if ($studentData->mname != null) {
          $studentFullName = ucwords("$studentData->fname " . $studentData->mname[0] . ". $studentData->lname");
        } else {
          $studentFullName = ucwords("$studentData->fname  $studentData->lname");
        };

        $html .= "
        <li>
          <hr class='dropdown-divider'>
        </li>
        <li class='notification-item " . ($notificationData->unread == 0 ? 'active' : '') . "'>
          <div style='margin-right: 10px;'>
            <img src='$SERVER_NAME/profile/" . ($studentData->avatar ? $studentData->avatar : 'default.png') . "' alt='Profile' class='rounded-circle' style='width: 50px'>
          </div>
          <div>
            <h4>$studentFullName</h4>
            <p>$notificationData->notification</p>
            <p>" . get_time_ago(strtotime($notificationData->createdAt)) . "</p>
          </div>
        </li>
        <li>
          <hr class='dropdown-divider'>
        </li>
        ";
      }
    }
  } else {
    $html = "<li class='notification-item justify-content-center align-items-center'>
    <h4>No notifications yet</h4>
  </li>";
  }

  $html .= "
    <li class='dropdown-footer'>
      <a href='./notifications' class='btn btn-link btn-sm'>Show all notifications</a>
    </li>";

  echo ($html);
}

function updateNotificationCount()
{
  global $con, $_SESSION;

  $query = mysqli_query(
    $con,
    "UPDATE `notification` SET unread='1' WHERE admin_id='$_SESSION[id]'"
  );
}

function getNotificationCount()
{
  global $con, $_SESSION;

  $query = mysqli_query(
    $con,
    "SELECT * FROM `notification` WHERE admin_id='$_SESSION[id]' and unread='0'"
  );

  $count = mysqli_num_rows($query);

  print_r($count == 0 ? null : $count);
}

function saveUpload()
{
  global $con, $_POST, $_FILES, $_SESSION;

  $uploadType = $_POST['uploadType'];
  $target_dir = "../uploads/$_SESSION[id]/";

  $queryStr = "";
  $action = "insert";
  if ($uploadType == "journal") {
    $fileName = saveFile($_FILES["pdfFile"], $target_dir);

    if ($fileName) {
      $queryStr = "INSERT INTO forms(user_id, file_name, form_type) VALUES('$_SESSION[id]', '$fileName' ,'$uploadType')";
    }
  } else {
    $fileName = saveFile($_FILES["pdfFile"], $target_dir);

    $formQ = mysqli_query(
      $con,
      "SELECT * FROM forms WHERE user_id='$_SESSION[id]' and form_type <> 'journal' and form_type='$uploadType'"
    );

    if (mysqli_num_rows($formQ) > 0) {
      $queryStr = "UPDATE forms SET file_name='$fileName' WHERE user_id='$_SESSION[id]' and form_type='$uploadType'";
      $action = "update";
    } else {
      if ($fileName) {
        $queryStr = "INSERT INTO forms(user_id, file_name, form_type) VALUES('$_SESSION[id]', '$fileName' ,'$uploadType')";
      }
    }
  }

  $comm = mysqli_query($con, $queryStr);

  if ($comm) {
    $response["success"] = true;
    if ($action == "insert") {
      $response["message"] = "Form successfully uploaded";

      $notification = "submitted " . getFormName($uploadType);
      saveNotification($notification, $_SESSION["id"]);
    } else {
      $response["message"] = "Form successfully updated";

      $notification = "updated " . getFormName($uploadType);
      saveNotification($notification, $_SESSION["id"]);
    }
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function getFormName($id)
{
  switch ($id) {
    case "applicationLetter":
      return "Application Letter";
      break;
    case "endorsement":
      return "Endorsement Letter";
      break;
    case "cv":
      return "Curriculum Vitae";
      break;
    case "journal":
      return "Journal of Daily Activities";
      break;
    case "waiver":
      return "Waiver";
      break;
    default:
      return "Form";
      null;
  }
}

function get_time_ago($time)
{
  $time_difference = time() - $time;

  if ($time_difference < 1) {
    return 'less than 1 second ago';
  }
  $condition = array(
    12 * 30 * 24 * 60 * 60 =>  'year',
    30 * 24 * 60 * 60       =>  'month',
    24 * 60 * 60            =>  'day',
    60 * 60                 =>  'hour',
    60                      =>  'minute',
    1                       =>  'second'
  );

  foreach ($condition as $secs => $str) {
    $d = $time_difference / $secs;

    if ($d >= 1) {
      $t = round($d);
      return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
    }
  }
}

function saveNotification($notification, $user_id)
{
  global $con;

  $admins = getAdminIds($user_id);

  foreach ($admins as $adminId) {
    mysqli_query(
      $con,
      "INSERT INTO `notification`(user_id, admin_id, `notification`, unread) VALUES('$user_id', '$adminId', '$notification', 'false')"
    );
  }
}

function getAdminIds($user_id)
{
  global $con;

  $user = getUserById($user_id);

  $ids = [];

  $superAdminQ = mysqli_query(
    $con,
    "SELECT * FROM users WHERE `role`='super-admin'"
  );

  if (mysqli_num_rows($superAdminQ) > 0) {
    while ($admin = mysqli_fetch_object($superAdminQ)) {
      array_push($ids, $admin->id);
    }
  }

  $adminsQ = mysqli_query(
    $con,
    "SELECT * FROM users WHERE `role`='admin' and office_account_id='$user->deployment_id'"
  );

  if (mysqli_num_rows($adminsQ) > 0) {
    while ($admin = mysqli_fetch_object($adminsQ)) {
      array_push($ids, $admin->id);
    }
  }

  return $ids;
}

function saveFile($file, $target_dir)
{
  $fileName = null;
  if (intval($file["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($file['name']);

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], "$target_dir/$uploadFile")) {
      $fileName = $uploadFile;
    }
  }
  return $fileName;
}

function hasUploaded($user_id, $uploadType)
{
}

function saveEvaluation()
{
  global $con, $_POST, $_SESSION;

  $evaluation_id = isset($_POST["evaluation_id"]) ? $_POST["evaluation_id"] : null;

  $evaluation = json_encode($_POST["evaluationData"]);

  if ($evaluation_id) {
    $query = mysqli_query(
      $con,
      "UPDATE evaluation SET evaluation ='$evaluation' WHERE evaluation_id='$evaluation_id'"
    );
  } else {
    $adminId = $_SESSION['id'];
    $user_id = $_POST['user_id'];

    $query = mysqli_query(
      $con,
      "INSERT INTO evaluation(admin_id, user_id, evaluation) VALUES('$adminId', '$user_id', '$evaluation')"
    );
  }
  if ($query) {
    $response["success"] = true;

    if ($evaluation_id) {
      $response["message"] = "Student evaluation updated successfully.";
      $response["location"] = "./evaluated-students.php";
    } else {
      $response["message"] = "Student evaluated successfully.";
      $response["location"] = "./deployed-list";
    }
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function removeProfile()
{
  global $con, $SERVER_NAME, $_SESSION;
  $user = getUserById($_SESSION["id"]);

  $response["success"] = false;

  if (unlink("../profile/$user->avatar")) {
    $query = mysqli_query(
      $con,
      "UPDATE users SET avatar=NULL WHERE id='$user->id'"
    );

    if ($query) {
      $response["success"] = true;
      $response["img_url"] = "$SERVER_NAME/profile/default.png";
    }
  }

  returnResponse($response);
}

function uploadProfile()
{
  global $con, $_FILES, $SERVER_NAME, $_SESSION;

  $response["success"] = false;
  $file = $_FILES["inputProfile"];

  if (intval($file["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($file['name']);
    $target_dir = "../profile/";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], "$target_dir/$uploadFile")) {
      $user = getUserById($_SESSION["id"]);

      $query = mysqli_query(
        $con,
        "UPDATE users SET avatar='$uploadFile' WHERE id='$user->id'"
      );

      $error = mysqli_error($con);

      if ($query) {
        $response["success"] = true;
        $response["img_url"] = "$SERVER_NAME/profile/$uploadFile";
        if ($user->avatar) {
          unlink("$target_dir/$user->avatar");
        }
      } else {
        $response["success"] = false;
        unlink("$target_dir/$uploadFile");
      }
    }
  }

  returnResponse($response);
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

  $did = nl2br($_POST["did"]);

  $query = mysqli_query(
    $con,
    "UPDATE attendance SET activity='$did' WHERE attendance_id='$_POST[attendanceId]'"
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

function editTimeOut()
{
  global $con, $_POST;

  $attendance_id = $_POST["attendance_id"];
  $time_out = $_POST["time_out"];

  $query = mysqli_query(
    $con,
    "UPDATE attendance SET time_out='$time_out', activity='Time out updated by administrator' WHERE attendance_id='$attendance_id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Time out successfully updated";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
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
      // $timeOutTime = date("H:i:s", strtotime("$dbTimeInDate $dbTimeInTime" . " +8 hours"));
      $query = mysqli_query(
        $con,
        "UPDATE attendance SET activity='No Time out' WHERE attendance_id='$row->attendance_id'"
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

  $logType = strtoupper(date("A"));

  if (!isTimeIn($userId)) {
    $query = mysqli_query(
      $con,
      "INSERT INTO attendance(`user_id`, `date`, time_in, `image`, log_type) VALUES('$userId', '$dateNow', '$timeNow', '$imgUrl', '$logType')"
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

  if (mysqli_num_rows($query) > 1) {
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

function updateStudentPersonalData()
{
  global $con, $_POST;

  $id = $_POST["id"];

  $fname = ucwords($_POST["fname"]);
  $mname = ucwords($_POST["mname"]);
  $lname = ucwords($_POST["lname"]);
  $section = ucwords($_POST["section"]);
  $course = $_POST["course"];
  $contact = $_POST["contact"];
  $date_of_birth = $_POST["date_of_birth"];
  $place_of_birth = ucwords($_POST["place_of_birth"]);
  $civil_status = ucwords($_POST["civil_status"]);
  $gender = ucwords($_POST["gender"]);
  $height = $_POST["height"];
  $weight = $_POST["weight"];
  $special_skills = ucwords($_POST["special_skills"]);
  $physical_disability = ucwords($_POST["physical_disability"]);
  $mental_disability = ucwords($_POST["mental_disability"]);
  $criminal_liability = ucwords($_POST["criminal_liability"]);
  $city_add = ucwords($_POST["city_add"]);
  $prov_add = ucwords($_POST["prov_add"]);

  $email = $_POST["email"];

  if (!isEmailExist($email, $id)) {
    $query = mysqli_query(
      $con,
      "UPDATE users SET 
        fname='$fname',
        lname='$lname',
        mname='$mname',
        contact='$contact',
        city_address=" . ($city_add ? "'$city_add'" : "NULL") . ",
        provincial_address=" . ($prov_add ? "'$prov_add'" : "NULL") . ",
        date_of_birth='$date_of_birth',
        place_of_birth='$place_of_birth',
        civil_status='$civil_status',
        gender='$gender',
        height='$height',
        `weight`='$weight',
        special_skills='$special_skills',
        special_skills='$special_skills',
        physical_disability=" . ($physical_disability ? "'$physical_disability'" : "NULL") . ",
        mental_disability=" . ($mental_disability ? "'$mental_disability'" : "NULL") . ",
        criminal_liability=" . ($criminal_liability ? "'$criminal_liability'" : "NULL") . ",
        email='$email',
        section='$section',
        course_id='$course'
        WHERE 
        id = '$id'
      "
    );
    if ($query) {
      $response["success"] = true;
      $response["message"] = "Personal Data successfully updated";
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

function updateUserData()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $fname = ucwords($_POST["fname"]);
  $mname = ucwords($_POST["mname"]);
  $lname = ucwords($_POST["lname"]);
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

function updateFamilyData()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $father = ucwords($_POST["father"]);
  $father_occ = ucwords($_POST["father_occ"]);
  $mother = ucwords($_POST["mother"]);
  $mother_occ = ucwords($_POST["mother_occ"]);

  $query = mysqli_query(
    $con,
    "UPDATE family_data SET father_name='$father', father_occupation='$father_occ', mother_name='$mother', mother_occupation='$mother_occ' WHERE user_id='$id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Family data successfully updated";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function updateEducationData()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $elementary = ucwords($_POST["elementary"]);
  $elem_grad = $_POST["elem_grad"];
  $secondary = ucwords($_POST["secondary"]);
  $sec_grad = $_POST["sec_grad"];
  $vocational = ucwords($_POST["vocational"]);
  $voc_grad = $_POST["voc_grad"];
  $college = ucwords($_POST["college"]);

  $query = mysqli_query(
    $con,
    "UPDATE educational SET
      elementary='$elementary',
      elem_grad='$elem_grad',
      `secondary`='$secondary',
      sec_grad='$sec_grad',
      vocational=" . ($vocational ? "'$vocational'" : "NULL") . ",
      voc_grad=" . ($voc_grad ? "'$voc_grad'" : "NULL") . ",
      college='$college'
      WHERE user_id = '$id'
    "
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Educational data successfully updated";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function updateEmergencyData()
{
  global $con, $_POST;

  $id = $_POST["id"];
  $name = ucwords($_POST["name"]);
  $relationship = ucwords($_POST["relationship"]);
  $address = ucwords($_POST["address"]);
  $incase_contact = $_POST["incase_contact"];

  $query = mysqli_query(
    $con,
    "UPDATE emergency_data SET `name`='$name', relationship='$relationship', `address`='$address', contact='$incase_contact' WHERE user_id = '$id'"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Incase of Emergency data successfully updated";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($con);
  }

  returnResponse($response);
}

function register()
{
  global $con, $_POST;

  // Personal Data
  $fname = ucwords($_POST["fname"]);
  $mname = ucwords($_POST["mname"]);
  $lname = ucwords($_POST["lname"]);
  $section = ucwords($_POST["section"]);
  $course = $_POST["course"];
  $office_id = isset($_POST["office_id"]) ? $_POST["office_id"] : null;
  $contact = $_POST["contact"];
  $date_of_birth = $_POST["date_of_birth"];
  $place_of_birth = ucwords($_POST["place_of_birth"]);
  $civil_status = ucwords($_POST["civil_status"]);
  $gender = ucwords($_POST["gender"]);
  $height = $_POST["height"];
  $weight = $_POST["weight"];
  $special_skills = ucwords($_POST["special_skills"]);
  $physical_disability = ucwords($_POST["physical_disability"]);
  $mental_disability = ucwords($_POST["mental_disability"]);
  $criminal_liability = ucwords($_POST["criminal_liability"]);
  $city_add = ucwords($_POST["city_add"]);
  $prov_add = ucwords($_POST["prov_add"]);

  // Incase of Emergency Data
  $name = ucwords($_POST["name"]);
  $relationship = ucwords($_POST["relationship"]);
  $address = ucwords($_POST["address"]);
  $incase_contact = $_POST["incase_contact"];

  // Family Data
  $father = ucwords($_POST["father"]);
  $father_occ = ucwords($_POST["father_occ"]);
  $mother = ucwords($_POST["mother"]);
  $mother_occ = ucwords($_POST["mother_occ"]);

  // Educational Data
  $elementary = ucwords($_POST["elementary"]);
  $elem_grad = $_POST["elem_grad"];
  $secondary = ucwords($_POST["secondary"]);
  $sec_grad = $_POST["sec_grad"];
  $vocational = ucwords($_POST["vocational"]);
  $voc_grad = $_POST["voc_grad"];
  $college = ucwords($_POST["college"]);

  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_ARGON2I);

  if (!isEmailExist($email)) {

    $personalDataStr = "INSERT INTO
                users(
                  fname,
                  lname,
                  mname,
                  contact,
                  city_address,
                  provincial_address,
                  date_of_birth,
                  place_of_birth,
                  civil_status,
                  gender,
                  height,
                  `weight`,
                  special_skills,
                  physical_disability,
                  mental_disability,
                  criminal_liability,
                  email,
                  `password`,
                  course_id,
                  section,
                  deployment_id,
                  `role`
                ) VALUES (
                  '$fname',
                  '$lname',
                  '$mname',
                  '$contact',
                  " . ($city_add ? "'$city_add'" : "NULL") . ",
                  " . ($prov_add ? "'$prov_add'" : "NULL") . ",
                  '$date_of_birth',
                  '$place_of_birth',
                  '$civil_status',
                  '$gender',
                  '$height',
                  '$weight',
                  '$special_skills',
                  " . ($physical_disability ? "'$physical_disability'" : "NULL") . ",
                  " . ($mental_disability ? "'$mental_disability'" : "NULL") . ",
                  " . ($criminal_liability ? "'$criminal_liability'" : "NULL") . ",
                  '$email',
                  '$password',
                  '$course',
                  '$section',
                  " . ($office_id ? "'$office_id'"  : "NULL") . ",
                  'student'
                )
            ";

    $query = mysqli_query($con, $personalDataStr);

    if ($query) {
      $response["success"] = true;
      $user_id = mysqli_insert_id($con);

      $other_query_str = "INSERT INTO 
                          emergency_data(
                            user_id, 
                            `name`, 
                            relationship, 
                            `address`, 
                            contact
                          ) VALUE(
                            '$user_id',
                            '$name',
                            '$relationship',
                            '$address',
                            '$incase_contact'
                          );
                          ";

      $other_query_str .= "INSERT INTO
                          family_data(
                            user_id,
                            father_name,
                            father_occupation,
                            mother_name,
                            mother_occupation
                          ) VALUES(
                            '$user_id',
                            '$father',
                            '$father_occ',
                            '$mother',
                            '$mother_occ'
                          );
                          ";

      $other_query_str .= "INSERT INTO 
                          educational(
                            user_id,
                            elementary,
                            elem_grad,
                            `secondary`,
                            sec_grad,
                            vocational,
                            voc_grad,
                            college
                          ) VALUES (
                            '$user_id',
                            '$elementary',
                            '$elem_grad',
                            '$secondary',
                            '$sec_grad',
                            " . ($vocational ? "'$vocational'" : "NULL") . ",
                            " . ($voc_grad ? "'$voc_grad'" : "NULL") . ",
                            '$college'
                          );
                          ";

      $otherQueryComm = mysqli_multi_query($con, $other_query_str);

      $response["message"] = "Successfully registered. You can now login.";
    } else {
      $error = mysqli_error($con);
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
  returnResponse($data);
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

  $fname = ucwords($_POST["fname"]);
  $mname = ucwords($_POST["mname"]);
  $lname = ucwords($_POST["lname"]);
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

function getUserFamilyData($user_id)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM family_data WHERE user_id = '$user_id'"
    )
  );
}

function getUserEmergencyData($user_id)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM emergency_data WHERE user_id = '$user_id'"
    )
  );
}

function getUserEducationData($user_id)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT * FROM educational WHERE user_id = '$user_id'"
    )
  );
}

function getStudentFullData($id)
{
  global $con;
  return mysqli_fetch_object(
    mysqli_query(
      $con,
      "SELECT 
      u.*,
      f.*,
      ed.*,
      em.name,
      em.relationship,
      em.address,
      em.contact AS incase_contact
      FROM users u 
      INNER JOIN family_data f 
      ON u.id = f.user_id
      INNER JOIN educational ed
      ON u.id = ed.user_id
      INNER JOIN emergency_data em
      ON u.id = em.user_id
      WHERE u.id='$id'"
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
