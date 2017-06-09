<?php

    if (isset($_GET['dir'])) {
        $current_folder=filter_var($_GET['dir'], FILTER_SANITIZE_STRING);
    }

    if (empty($current_folder)) {
        $current_folder='.';
    }

    $dir_param='';
    if ($current_folder!='.') {
        $dir_param='?dir='.$current_folder;
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo _('SL FTP') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $sl_connect_info['web_root_directory'] ?>images/favicon.ico" type="image/x-icon" rel="shortcut icon"/>
	<link href="<?php echo $sl_connect_info['web_root_directory'] ?>css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet" />
	<link href="<?php echo $sl_connect_info['web_root_directory'] ?>css/index.css" media="all" type="text/css" rel="stylesheet" />
</head>
<body>
	<header>
		<div class="container">
		<div class="row">
		</div>
		</div>
	</header>
	<div id="mom">
		<div id="main">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 bg-danger" style="padding:20px;margin:20px">
						<h3 class="text-danger"><?php echo _('Error') ?></h3>
						<?php if (DEBUG==1): ?>
						<p><?php echo $e->getCode() ?> : <?php echo $e->getMessage() ?><p>
						<?php else: ?>
						<p><?php echo _('Sorry Error') ?></p>
						<?php endif ?>
						<a href="<?php echo $sl_connect_info['web_root_directory'] ?>index.php<?php echo $dir_param ?>" class="btn btn-primary"><?php echo _('Confirm') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer>

	</footer>
</body>
</html>
