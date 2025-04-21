<?php

/*********************************************************************
    open.php

    New tickets handle.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
 **********************************************************************/
require('client.inc.php');
define('SOURCE', 'Web'); //Ticket source.
$ticket = null;
$errors = array();
$success = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $date = sanitize_input($_POST['date'] ?? '');
  $request_type = sanitize_input($_POST['requestType'] ?? '');
  $staff_name = sanitize_input($_POST['staffName'] ?? '');
  $branch_name = sanitize_input($_POST['branchName'] ?? '');
  $staff_id = sanitize_input($_POST['staffId'] ?? '');
  $designation = sanitize_input($_POST['designation'] ?? '');
  $pumori_login_id = sanitize_input($_POST['pumLoginId'] ?? '');
  $existing_privilege = sanitize_input($_POST['existingPrevileges'] ?? '');

  $checkerPrivilege = $_POST['checkerPrivilege'] ?? [];
  $makerPrivilege = $_POST['makerPrivilege'] ?? [];
  $goldLoan = isset($_POST['goldLoan']) ? ['goldLoan'] : [];
  $requested_privileges = implode(',', array_merge($checkerPrivilege, $makerPrivilege, $goldLoan));

  // Validation rules
  if (empty($date)) {
    $errors["date"] = "Date is required";
  }

  if (!in_array($request_type, ['new', 'amend', 'block'])) {
    $errors["request_type"] = "Invalid request type selected";
  }

  if (empty($staff_name) || !preg_match("/^[a-zA-Z ]*$/", $staff_name)) {
    $errors["staff_name"] = "Only letters and white space allowed in staff name";
  }

  if (empty($branch_name)) {
    $errors["branch_name"] = "Branch name is required";
  }

  if (empty($staff_id) || !is_numeric($staff_id)) {
    $errors["staff_id"] = "Valid numeric staff ID is required";
  }

  if (empty($designation)) {
    $errors["designation"] = "Designation is required";
  }

  if (empty($pumori_login_id) || !is_numeric($pumori_login_id)) {
    $errors["pumori_login_id"] = "Valid numeric Pumori login ID is required";
  }

  if (empty($existing_privilege)) {
    $errors["existing_privilege"] = "Existing privilege is required";
  }

  if (empty($requested_privileges)) {
    $errors["requested_privileges"] = "At least one privilege must be selected";
  }



  // echo "The value of fields {$date}  {$request_type},  {$staff_name}, {$branch_name}, {$staff_id}, {$designation}, {$pumori_login_id}, {$existing_privilege}, {$requested_privilege}";
  if (empty($errors)) {
    $sql = sprintf(
      "INSERT INTO ost_pumori_rights 
          (date, request_type, staff_name, branch_name, staff_id, designation, pumori_login_id, existing_privilege, requested_privilege)
          VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
      db_input($date),
      db_input($request_type),
      db_input($staff_name),
      db_input($branch_name),
      db_input($staff_id),
      db_input($designation),
      db_input($pumori_login_id),
      db_input($existing_privilege),
      db_input($requested_privileges)
    );

    if (db_query($sql)) {
      $success = true;
    } else {
      $errors['db'] = 'Database insert failed: ' . db_error();
    }
  }
}


function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//page
$nav->setActiveNav('new');
if ($cfg->isClientLoginRequired()) {
  if ($cfg->getClientRegistrationMode() == 'disabled') {
    Http::redirect('view.php');
  } elseif (!$thisclient) {
    require_once 'secure.inc.php';
  } elseif ($thisclient->isGuest()) {
    require_once 'login.php';
    exit();
  }
}

require(CLIENTINC_DIR . 'header.inc.php');
if (
  $ticket
  && (
    (($topic = $ticket->getTopic()) && ($page = $topic->getPage()))
    || ($page = $cfg->getThankYouPage())
  )
) {
  // Thank the user and promise speedy resolution!
  echo Format::viewableImages(
    $ticket->replaceVars(
      $page->getLocalBody()
    ),
    ['type' => 'P']
  );
} else {
  require(CLIENTINC_DIR . 'block.inc.php');
}
require(CLIENTINC_DIR . 'footer.inc.php');
