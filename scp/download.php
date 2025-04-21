<?php
require('staff.inc.php'); // osTicket environment

$uploadDir = '../uploads/'; // adjust based on your directory structure
$allowed_dept_id = 4;

// Protect access
if (!$thisstaff || $thisstaff->getDeptId() != $allowed_dept_id) {
  require_once(STAFFINC_DIR . 'header.inc.php');
  echo '<div class="error"><h2>Access Forbidden</h2></div>';
  require_once(STAFFINC_DIR . 'footer.inc.php');
  exit;
}

// Get filename
if (!isset($_GET['file'])) {
  die('Missing file.');
}

$filename = basename($_GET['file']); // prevent path traversal
$filepath = $uploadDir . $filename;

if (!file_exists($filepath)) {
  die('File not found.');
}

// Send proper headers to trigger download
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
exit;
