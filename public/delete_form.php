<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}
?>
<?php if (DEBUG==1): ?>
<form action="delete.php" method="post">
  <input type="text" name="files[]">
  <input type="submit" value="<?php echo _('Delete') ?>">
</form>
<?php endif ?>
