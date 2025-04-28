<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once '../vendor/autoload.php'; // Make sure path is correct

require('staff.inc.php');
$allowed_dept_id = 4;
if (!$thisstaff || $thisstaff->getDeptId() != $allowed_dept_id) {
  require_once(STAFFINC_DIR . 'header.inc.php');
  echo '<div class="error">
            <h2>Access Forbidden</h2>
            <p>You do not have permission to access this page.</p>
          </div>';
  require_once(STAFFINC_DIR . 'footer.inc.php');
  exit;
}
$success = false;
$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

  $file = $_FILES['hrFile'];
  if ($file['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    $filename = uniqid()  . '-' . $file['name'];
    // echo "The uploaded file name is {$filename}";

    $allowedExtensions = ['xlsx'];
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    // echo "The uploaded file extension is {$fileExtension}";

    if (in_array($fileExtension, $allowedExtensions)) {
      if (move_uploaded_file($file['tmp_name'], $uploadDir .  $filename)) {
        $messages[] = ['text' => 'File uploaded successfully!',  'color' => 'green'];


        $spreadsheet = IOFactory::load($uploadDir . $filename);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();


        foreach ($rows as $index => $row) {
          if ($index === 0) continue; // skip headers

          list($eid, $employeeName, $branch, $leaveName, $startDate, $endDate, $days) = $row;

          $sql = sprintf(
            "INSERT INTO ost_block_multiple 
                (eid, employee_name, branch, leave_name, start_date, end_date, days)
                VALUES (%s, %s, %s, %s, %s, %s, %s)",
            db_input($eid),
            db_input($employeeName),
            db_input($branch),
            db_input($leaveName),
            db_input($startDate),
            db_input($endDate),
            db_input($days)
          );

          if (db_query($sql)) {
            $success = true;
          } else {
            $messages[] = ['text' => 'Database insert failed: ' . db_error(),  'color' => 'red'];
          }
        }
      } else {
        $messages[] = ['text' => 'File Upload Error !',  'color' => 'red'];
      }
    } else {
      $messages[] = ['text' => 'File must be of type Excel with extension XLSX!',  'color' => 'red'];
    }
  }
}
//Navigation
$nav->setTabActive('blocks');

require_once(STAFFINC_DIR . 'header.inc.php');
require_once(STAFFINC_DIR . 'block.inc.php');
require_once(STAFFINC_DIR . 'footer.inc.php');
