<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (!empty($_POST)):
        if (empty($_POST['host'])) {
            throw new \Exception("Error Processing Request", 1);
        }

    if (empty($_POST['username'])) {
        throw new \Exception("Error Processing Request", 1);
    }

    $host=filter_var($_POST['host'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username=filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userpass=filter_var($_POST['userpass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $port=filter_var($_POST['port'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $ssl=false;
    $pasv=false;

    if (!empty($_POST['ssl'])) {
        $ssl=true;
    }

    if (!empty($_POST['pasv'])) {
        $pasv=true;
    }

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($host, $ssl, $port);

    if ($ftp -> login($username, $userpass)) {
        $_SESSION['sl_connect_info']=array();
        $_SESSION['sl_connect_info']['host']=$host;
        $_SESSION['sl_connect_info']['username']=$username;
        $_SESSION['sl_connect_info']['userpass']=$userpass;
        $_SESSION['sl_connect_info']['port']=$port;
        $_SESSION['sl_connect_info']['ssl']=$ssl;
        $_SESSION['sl_connect_info']['pasv']=$pasv;
    } else {
        $_SESSION['sl_connect_info']=null;
        echo 'nonono';
    }

    header('Location: ' . WEB_ROOT_DIRECTORY . 'index.php'); else:
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
	<link href="<?php echo CSS_DIRECTORY?>index.css" media="all" type="text/css" rel="stylesheet">
</head>
<body id="sl_ftp_connect">
<div id="mom" class="mt-0">
		<div id="main" class="pt-0">
		<div class="container">
      <div class="row">
        <div class="col-12">
        <?php include __DIR__ . DIRECTORY_SEPARATOR .'ad.php' ?>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="connect_form" method="post">
        <div class="card">
      		<div class="card-header">
      			<h3><?php echo _('Connect Info') ?></h3>
      		</div>
      		<div class="card-body">
            <div class="row">
          <div class="col-12 col-lg-10 mt-3 form-group">
            <label><?php echo _('host') ?></label>
            <input type="text" name="host" class="form-control form-control-lg" required="required">
          </div>
          <div class="col-12 col-lg-2 mt-3 form-group">
            <label><?php echo _('port') ?></label>
            <input type="number" name="port" value="21" class="form-control form-control-lg" required="required">
          </div>
          <div class="col-12 mt-3 form-group">
            <label><?php echo _('username') ?></label>
            <input type="text" name="username" class="form-control form-control-lg" required="required">
          </div>
          <div class="col-12 mt-3 form-group">
            <label><?php echo _('userpass') ?></label>
            <input type="password" name="userpass" class="form-control form-control-lg">
          </div>
          <div class="col-12 form-group mt-3">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="ssl" value="1" /> <?php echo _('sftp') ?>
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                  <input type="checkbox" name="pasv_mode" value="1" /> <?php echo _('pasv mode') ?>
                </label>
              </div>
          </div>





          </div>
      </div>
        </div>
        <div class="d-grid gap-2 mt-3">
               <input type="submit" value="<?php echo _('connect') ?>" class="btn btn-primary btn-lg" />
          </div>
      </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <?php
include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>

    <script src="<?php echo JS_DIRECTORY ?>jquery.min.js"></script>
  	<script src="<?php echo BOOTSTRAP_JS_DIRECTORY?>bootstrap.min.js" defer></script>
  	<script src="<?php echo JS_DIRECTORY?>index.js" defer></script>
</body>
</html>
<?php
  endif;
} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'error.php';
}
?>
