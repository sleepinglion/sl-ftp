<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (!empty($_POST)):
        if (empty($_POST['host'])) {
            throw new \Exception("Error Processing Request", 1);
        }

    if (empty($_POST['username'])) {
        throw new \Exception("Error Processing Request", 1);
    }

    if (empty($_POST['userpass'])) {
        throw new \Exception("Error Processing Request", 1);
    }

    $_SESSION['sl_connect_info']=array();
    $_SESSION['sl_connect_info']['host']=filter_var($_POST['host'], FILTER_SANITIZE_STRING);
    $_SESSION['sl_connect_info']['username']=filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $_SESSION['sl_connect_info']['userpass']=filter_var($_POST['userpass'], FILTER_SANITIZE_STRING);
    $_SESSION['sl_connect_info']['no_default']=true;

    $config = new Config\config($_SESSION['sl_connect_info']);

    header('Location: ' . $config -> web_root_directory . 'index.php'); else:
        $config = new Config\config(); ?>
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
<body id="sl_ftp_connect">
		<div class="container">
      <div class="row">
        <form action="" method="post" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo _('host') ?></label>
            <div class="col-sm-10">
              <input type="text" name="host" class="form-control" required="required" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo _('username') ?></label>
            <div class="col-sm-10">
              <input type="text" name="username" class="form-control" required="required" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo _('userpass') ?></label>
            <div class="col-sm-10">
              <input type="password" name="userpass" class="form-control" required="required" />
            </div>
          </div>
          <div class="form-group">
             <div class="col-sm-offset-2 col-sm-10">
               <input type="submit" value="<?php echo _('connect') ?>" />
             </div>
          </div>
        </form>
      </div>
		</div>
</body>
<?php
  endif;
} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
?>
