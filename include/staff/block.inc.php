<?php if ($success): ?>
  <h1 class="success">Form submitted successfully!</h1>
<?php else: ?>
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


<!-- To list all uploaded files after an HR uploads one  -->
<?php
$uploadDir = '../uploads/';
$uploadedFiles = [];

if (is_dir($uploadDir)) {
  $allFiles = scandir($uploadDir);
  // Filter out . and .. entries
  $uploadedFiles = array_filter($allFiles, function ($file) use ($uploadDir) {
    return !in_array($file, ['.', '..']) && is_file($uploadDir . $file);
  });
}
?>

<?php if (!empty($uploadedFiles)): ?>
  <h3>Uploaded Files</h3>
  <table class="list" border="0" cellspacing="1" cellpadding="2" width="100%">
    <thead>
      <tr>
        <th>Filename</th>
        <th>Download</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($uploadedFiles as $file): ?>
        <tr>
          <td><?= htmlspecialchars($file) ?></td>
          <td><a href="download.php?file=<?= rawurlencode($file) ?>">Download</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
