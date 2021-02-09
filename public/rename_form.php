<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}

if (isset($_GET['file'])) {
    $file=$_GET['file'];
}

if (isset($_GET['type'])) {
    $type=$_GET['type'];
}

?>

<?php if (isset($_GET['no_layout'])): ?>

  <div class="modal-dialog modal-lg">
  	<form action="rename.php" method="post" class="modal-content">
  		<div class="modal-header">
  			<h4 class="modal-title" id="myModalLabel"><?php echo _('Rename') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
  		</div>
  		<div class="modal-body">
  			<input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
          <?php if (isset($file)): ?>
          <div class="form-group">
            <input type="hidden" name="files[0][old_name]" value="<?php echo $file ?>" />
    				<label>
              <?php if ($type=='directory'): ?>
    							<label for="file_check"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_folder.gif" width="16" height="16" alt="<?php echo _('Direcotry') ?>" /></label>
    					<?php else: ?>
    							<label for="file_check"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_file.gif" width="16" height="16" alt="<?php echo _('File') ?>" /></label>
    					<?php endif ?>
            </label>
            <input id="new_name0" class="form-control" name="files[0][new_name]" type="text" value="<?php echo $file ?>" />
          </div>
          <?php else: ?>
          <div class="form-group" style="display:none">
    				<input type="hidden" />
    				<label></label>
    				<input type="text" class="form-control" />
    			</div>
          <?php endif ?>
  		</div>
  		<div class="modal-footer">
  			<button type="submit" class="btn btn-primary"><?php echo _('Confirm') ?></button>
  		</div>
  	</form>
  </div>

<?php else: ?>
<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
  <meta charset="utf-8">
  <title><?php echo _('SL FTP') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
  <meta name="author" content="Sleeping-Lion">
  <link href="<?php echo ASSET_DIRECTORY?>images/favicon.ico" type="image/x-icon" rel="shortcut icon">
  <link href="<?php echo ASSET_DIRECTORY?>css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
  <link href="<?php echo ASSET_DIRECTORY?>css/index.css" media="all" type="text/css" rel="stylesheet">
</head>
<body id="sl_rename_form">
    <div class="container">
      <div class="row">
        <?php include __DIR__ . DIRECTORY_SEPARATOR .'ad.php' ?>
      <article class="card">
        <div class="card-header">
          <h3><?php echo _('Rename') ?></h3>
        </div>
        <div class="card-body">

	   <form action="rename.php" method="post">
       <div class="form-group">
        <input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
        <input value="<?php echo $file ?>" name="files[0][old_name]" type="hidden">
 				<label><?php echo _('Name') ?></label>
 				<input type="text" name="files[0][new_name]" value="<?php echo $file ?>" class="form-control" />
 			</div>
      <div class="">
			     <button type="submit" class="btn btn-primary"><?php echo _('Confirm') ?></button>
         </div>
       </form>
     </div>
    </article>
    </div>
  </div>
</body>
</html>
<?php endif ?>
