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
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
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
