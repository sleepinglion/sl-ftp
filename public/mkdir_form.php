<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}
?>

<?php if (isset($_GET['no_layout'])): ?>

  <div class="modal-dialog modal-lg">
  	<form action="mkdir.php" method="post" class="modal-content">
  		<div class="modal-header">
  			<h4 class="modal-title" id="myModalLabel"><?php echo _('New Folder') ?></h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">×</span>
        	</button>
  		</div>
  		<div class="modal-body">
  			<input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
  			<input type="text" name="folder_name" class="form-control" required="required" />
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
          <h3><?php echo _('New Folder') ?></h3>
        </div>
        <div class="card-body">
          <form action="mkdir.php" method="post">
            <div class="form-group">
        			<input type="hidden" name="dir" value="<?php if (empty($current_folder)): ?><?php else: ?><?php echo $current_folder ?><?php endif ?>" />
        			<input type="text" name="folder_name" class="form-control" required="required" />
        		</div>
        		<div class="form-group actions">
        			<button type="submit" class="btn btn-primary"><?php echo _('Confirm') ?></button>
        		</div>
        	</form>
     </div>
   </article>
   </section>
      </div>
    </div>
  </body>
</html>
<?php endif ?>
