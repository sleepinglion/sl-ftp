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


    $host=filter_var($_POST['host'], FILTER_SANITIZE_STRING);
    $username=filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $userpass=filter_var($_POST['userpass'], FILTER_SANITIZE_STRING);
    $port=filter_var($_POST['port'], FILTER_SANITIZE_STRING);
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

    header('Location: ' . $sl_connect_info['web_root_directory'] . 'index.php'); else:
?>
<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo _('SL FTP') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Sleeping-Lion">
	<link href="<?php echo $sl_connect_info['web_root_directory']?>images/favicon.ico" type="image/x-icon" rel="shortcut icon"/>
	<link href="<?php echo $sl_connect_info['web_root_directory']?>css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet" />
	<link href="<?php echo $sl_connect_info['web_root_directory']?>css/index.css" media="all" type="text/css" rel="stylesheet" />
</head>
<body id="sl_ftp_connect">
		<div class="container">
      <div class="row">
        <div id="sl_ad" style="margin-bottom:20px">
        <?php if (DEBUG==1): ?>
        <div style="width:728px;height:90px;background:red;">&nbsp;</div>
        <?php else: ?>
          <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
          <!-- 250정사각형 -->
          <ins class="adsbygoogle"
               style="display:inline-block;width:728px;height:90px"
               data-ad-client="ca-pub-5400903051441488"
               data-ad-slot="7215435937"></ins>
          <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        <?php endif ?>
        </div>
        <form action="" id="connect_form" method="post" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo _('host') ?></label>
            <div class="col-sm-7">
              <input type="text" name="host" class="form-control" required="required" />
            </div>
            <label class="col-sm-1 control-label"><?php echo _('port') ?></label>
            <div class="col-sm-2">
              <input type="number" name="port" value="21" class="form-control" required="required" />
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
              <input type="password" name="userpass" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
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
          <div class="form-group">
             <div class="col-sm-offset-2 col-sm-10">
               <input type="submit" value="<?php echo _('connect') ?>" class="btn btn-primary btn-block" />
             </div>
          </div>
        </form>
        <?php include __DIR__ . DIRECTORY_SEPARATOR .'locale_form.php' ?>
      </div>
		</div>
    <script src="<?php echo $sl_connect_info['web_root_directory']?>js/jquery-2.1.1.min.js" defer></script>
  	<script src="<?php echo $sl_connect_info['web_root_directory']?>js/bootstrap.min.js" defer></script>
  	<script src="<?php echo $sl_connect_info['web_root_directory']?>js/index.js" defer></script>
</body>
</html>
<?php
  endif;
} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
?>
