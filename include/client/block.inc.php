<?php
if (!defined('OSTCLIENTINC')) die('Access Denied!');
$info = array();
if ($thisclient && $thisclient->isValid()) {
  $info = array(
    'name' => $thisclient->getName(),
    'email' => $thisclient->getEmail(),
    'phone' => $thisclient->getPhoneNumber()
  );
}

$info = ($_POST && $errors) ? Format::htmlchars($_POST) : $info;

$form = null;
if (!$info['topicId']) {
  if (array_key_exists('topicId', $_GET) && preg_match('/^\d+$/', $_GET['topicId']) && Topic::lookup($_GET['topicId']))
    $info['topicId'] = intval($_GET['topicId']);
  else
    $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic = Topic::lookup($info['topicId']))) {
  foreach ($topic->getForms() as $F) {
    if (!$F->hasAnyVisibleFields())
      continue;
    if ($_POST) {
      $F = $F->instanciate();
      $F->isValidForClient();
    }
    $forms[] = $F->getForm();
  }
}

?>

<?php if ($success): ?>
  <h1 class="success">Form submitted successfully!</h1>
<?php else: ?>

  <h1><?php echo __('Fill Pumori Right Form'); ?></h1>
  <p><?php echo __('Please fill in the form below to open get pumori right.'); ?></p>
  <?php if (!empty($messages)): ?>
    <div class="error">
      <ul>
        <?php foreach ($messages as $message): ?>
          <p style="color:<?= $message['color'] ?>;">
            <?= $message['text'] ?>
          </p>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>


  <form id="pumoriRightForm" method="post" action="block.php" enctype="multipart/form-data">
    <?php csrf_token(); ?>

    <div class="form-group">
      <label for="blockFile">Upload Excel File</label>
      <input type="file" id="blockFile" name="hrFile">
    </div>


    <hr>
    <p class="buttons" style="text-align:center;">
      <input type="submit" name="submit" value="<?php echo __('Submit'); ?>">
      <input type="reset" name="reset" value="<?php echo __('Reset'); ?>">
      <input type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
            $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.plugin.draft.deleteDraft();
            });
            window.location.href='index.php';">
    </p>
  </form>
<?php endif; ?>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("change", function(e) {
      console.log(e.target)
      if (
        e.target.matches('input[type="checkbox"][data-group="singlePrivilege"]')
      ) {
        console.log("Matched")
        const allCheckboxes = document.querySelectorAll(
          'input[type="checkbox"][data-group="singlePrivilege"]'
        );

        allCheckboxes.forEach((box) => {
          if (box !== e.target) box.checked = false;
        });
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('#branchSelect').select2({
      placeholder: 'Select a branch',
      allowClear: true
    });
  });
</script>
