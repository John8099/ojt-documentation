<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "docu";

$response = array(
  "success" => false,
  "message" => ""
);

try {
  $con = mysqli_connect($host, $user, $password, $db);
} catch (Exception $e) {
  $response["message"] = $e->getMessage();
  returnResponse($response);
}
