<?php
// $listOfFiles = listOfFilesUploadedInDb();

function folderFiles($dir, $arrFilesInDb)
{
  $folder = scandir($dir);

  unset($folder[array_search('.', $folder, true)]);
  unset($folder[array_search('..', $folder, true)]);

  if (count($folder) < 1)
    return;

  foreach ($folder as $ff) {
    if (is_dir($dir . '/' . $ff)) {
      folderFiles($dir . '/' . $ff, $arrFilesInDb);
    }
    if (!in_array($ff, $arrFilesInDb) && !is_dir($dir . '/' . $ff)) {
      unlink(__DIR__ . "/" . $dir . "/" . $ff);
    }
  }
  return "true";
}

function listOfFilesUploadedInDb($conn)
{
  $listOfFiles = ["default.png"];

  $userQ = mysqli_query($conn, "SELECT * FROM users");
  while ($a = mysqli_fetch_object($userQ)) {
    array_push($listOfFiles, $a->avatar);
  }

  $attendanceQ = mysqli_query($conn, "SELECT * FROM attendance");
  while ($b = mysqli_fetch_object($attendanceQ)) {
    $exploded = explode("/", $b->image);
    array_push($listOfFiles, $exploded[count($exploded) - 1]);
  }

  $formsQ = mysqli_query($conn, "SELECT * FROM forms");
  while ($c = mysqli_fetch_object($formsQ)) {
    array_push($listOfFiles, $c->file_name);
  }

  return $listOfFiles;
}

// echo "
// <script>
// console.log('" . $result . "')
// </script>
// ";
