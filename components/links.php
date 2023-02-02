<?php
$self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";
$role = $user->role == "student" ? "student" : "admin";

$links = array(
  array(
    "title" => "Dashboard",
    "url" => "$SERVER_NAME/pages/$role/",
    "allowedViews" => array("super-admin", "admin"),
    "config" => array(
      "icon" => "grid-fill"
    )
  ),
  array(
    "title" => "Offices",
    "url" => "$SERVER_NAME/pages/$role/office-list",
    "allowedViews" => array("super-admin"),
    "config" => array(
      "icon" => "laptop-fill"
    )
  ),
  array(
    "title" => "Admins",
    "url" => "$SERVER_NAME/pages/$role/admin-list",
    "allowedViews" => array("super-admin"),
    "config" => array(
      "icon" => "person-badge-fill"
    )
  ),
  array(
    "title" => "Students",
    "url" => "$SERVER_NAME/pages/$role/student-list",
    "allowedViews" => array("super-admin"),
    "config" => array(
      "icon" => "people-fill"
    )
  ),
  array(
    "title" => "Attendance",
    "url" => "$SERVER_NAME/pages/$role/attendance",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "clock-fill"
    )
  ),
  array(
    "title" => "Students",
    "url" => "$SERVER_NAME/pages/$role/deployed-list",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "people-fill"
    )
  ),
  array(
    "title" => "DTR Records",
    "url" => "$SERVER_NAME/pages/dtr",
    "allowedViews" => array("super-admin", "admin", "student"),
    "config" => array(
      "icon" => "folder-fill"
    )
  ),
  // array(
  //   "title" => "Rating",
  //   "url" => "$SERVER_NAME/pages/$role/rating.php",
  //   "allowedViews" => array("admin"),
  //   "config" => array(
  //     "icon" => "star-fill"
  //   )
  // ),
  array(
    "title" => "Settings",
    "url" => "$SERVER_NAME/pages/$role/settings",
    "allowedViews" => array("super-admin"),
    "config" => array(
      "icon" => "gear-fill"
    )
  ),
);
