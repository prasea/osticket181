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
$messages = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

  $file = $_FILES['hrFile'];
  if ($file['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';

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
        $success = true;
      } else {
        $messages[] = ['text' => 'File Upload Error !',  'color' => 'red'];
      }
    } else {
      $messages[] = ['text' => 'File must be of type Excel with extension XLSX!',  'color' => 'red'];
    }
  }
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
