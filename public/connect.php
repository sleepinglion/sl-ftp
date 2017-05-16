<?php

try {
		
	require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
	
	if(!empty($_POST)):
		if(empty($_POST['host']))
			throw new Exception("Error Processing Request", 1);
	
		if(empty($_POST['username']))
			throw new Exception("Error Processing Request", 1);
	
		if(empty($_POST['userpass']))
			throw new Exception("Error Processing Request", 1);
	
		$_SESSION['sl_connect_info']=array();
		$_SESSION['sl_connect_info']['host']=filter_var($_POST['host'],FILTER_SANITIZE_STRING);
		$_SESSION['sl_connect_info']['username']=filter_var($_POST['username'],FILTER_SANITIZE_STRING);
		$_SESSION['sl_connect_info']['userpass']=filter_var($_POST['userpass'],FILTER_SANITIZE_STRING);
		$_SESSION['sl_connect_info']['no_default']=true;
	
    	header('Location: ' . $config -> web_root_directory . 'index.php');
else:
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>FTP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $config->web_root_directory ?>images/favicon.ico" type="image/x-icon" rel="shortcut icon"/>
	<link href="<?php echo $config->web_root_directory ?>css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet" />
	<link href="<?php echo $config->web_root_directory ?>css/index.css" media="all" type="text/css" rel="stylesheet" />
</head>
<body>
	<header>
		<div class="container">
			<form action="" method="post">
				<div class="form-group">
					<label><?php echo _('host') ?></label>
					<input type="text" name="host" required="required" />
				</div>
				<div class="form-group">
					<label><?php echo _('username') ?></label>					
					<input type="text" name="username" required="required" />
				</div>
				<div class="form-group">
					<label><?php echo _('userpass') ?></label>
					<input type="password" name="userpass" required="required" />					
				</div>
				<div>
					<input type="submit" value="FTP접속" />
				</div>								
			</form>
		</div>
	</header>
</body>
<?php endif ?>
<?php
} catch (Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
?>