<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}

if (isset($_GET['file'])) {
    $file=$_GET['file'];
}
?>

<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
  <meta charset="utf-8">
  <title><?php echo _('SL FTP') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
  <meta name="author" content="Sleeping-Lion">
	<link href="<?php echo IMAGE_DIRECTORY?>favicon.ico" type="image/x-icon" rel="shortcut icon">
	<link href="<?php echo BOOTSTRAP_CSS_DIRECTORY?>bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
  <link href="<?php echo ASSET_DIRECTORY?>css/index.css" media="all" type="text/css" rel="stylesheet">
</head>
<body id="sl_rename_form">
  <div class="container">
    <div class="row">
      <?php include __DIR__ . DIRECTORY_SEPARATOR .'ad.php' ?>
      <article class="card">
        <div class="card-header">
          <h3><?php echo _('Delete Confirm') ?></h3>
        </div>
        <div class="card-body">
          <form action="delete.php" method="post">
            <p><?php echo sprintf(_("Are You Sure Delete %s"), $file) ?></p>
        		<input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
            <input type="hidden" name="files[]" value="<?php echo $file ?>"  />
            <div class="form-group actions">
              <a href="<?php echo WEB_ROOT_DIRECTORY?>index.php" class="btn btn-default"><?php echo _('Cancel') ?></a>
			        <button type="submit" class="btn btn-danger"><?php echo _('Delete') ?></button>
            </div>
          </form>
        </div>
      </article>
    </div>
  </div>
</body>
</html>
