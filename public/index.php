<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (isset($_GET['dir'])) {
        $current_folder = $_GET['dir'];
    }

    if (empty($_SESSION['directory'])) {
        $_SESSION['directory'] = array();
    }

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($sl_connect_info['host'], $sl_connect_info['ssl'], $sl_connect_info['port']);
    $ftp -> login($sl_connect_info['username'], $sl_connect_info['userpass']);

    if (!empty($sl_connect_info['pasv'])) {
        $ftp -> pasv($sl_connect_info['pasv']);
    }

    if (empty($current_folder)) {
        $current_folder = '.';
    }

    $total = $ftp -> count($current_folder);

    if (!key_exists('root', $_SESSION['directory'])) {
        $_SESSION['directory'][DIRECTORY_SEPARATOR] = DIRECTORY_SEPARATOR;
    }

    if (isset($_GET['dir'])) {
        $_SESSION['directory'][$current_folder] = $current_folder . '(' . $total . _('Files') . ')';
    }

    $_SESSION['directory'] = array_unique($_SESSION['directory']);

    $list = $ftp -> scanDir($current_folder);

    $dir_param = '';
    if ($current_folder != '.') {
        $dir_param = '?dir=' . $current_folder;
    } ?>
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
<body>
	<header>
		<div class="container-fluid">
      <div class="row">
        <nav class="col-xs-12">
          <ul class="nav nav-pills">
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>index.php<?php echo $dir_param ?>" class="btn btn-default"><?php echo _('Refresh') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>upload_form.php<?php echo $dir_param ?>" id="upload" class="btn btn-default" target="_blank"><?php echo _('Upload') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>mkdir_form.php<?php echo $dir_param ?>" data-target="#myModal" data-toggle="modal" class="modal_link btn btn-default"><?php echo _('New Folder') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>mkzip.php<?php echo $dir_param ?>" id="download" class="btn btn-default disabled"><?php echo _('Download') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>rename_form.php<?php echo $dir_param ?>" id="rename" data-target="#myModal" data-toggle="modal" class="modal_link btn btn-default disabled"><?php echo _('Rename') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>delete.php<?php echo $dir_param ?>" id="delete" class="btn btn-default disabled"><?php echo _('Delete') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <?php if (isset($_SESSION['sl_connect_info']['host'])): ?>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>index.php?logout=true" id="logout" class="btn btn-default"><?php echo _('Logout') ?><span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <?php endif ?>
          </ul>
        </nav>
      </div>
		</div>
	</header>
	<div id="mom">
		<div id="main">
			<input type="hidden" id="web_root_directory" value="<?php echo WEB_ROOT_DIRECTORY?>" />
			<input type="hidden" id="directory_separator" value="<?php echo DIRECTORY_SEPARATOR ?>" />
			<input type="hidden" id="current_folder" value="<?php echo $current_folder ?>" />
	<div class="table-responsive">
    <div id="directory_info">
	<label for="visited_directory"><?php echo _('Current Directory') ?></label>  : <?php if ($current_folder=='.'): ?><?php echo DIRECTORY_SEPARATOR ?><?php else: ?><?php echo $current_folder ?>(<?php echo $total ?><?php echo _('Files') ?>)<?php endif ?>
	<span style="margin:0 20px">||</span> <label for="visited_directory"><?php echo _('Visited Directory') ?></label>
		<?php if (count($_SESSION['directory'])): ?>
		<select name="visited_directory" id="visited_directory">
			<?php foreach ($_SESSION['directory'] as $s_key=>$s_directory): ?>
			<option value="<?php echo $s_key ?>" <?php if ($s_key==$current_folder): ?>selected="selected"<?php endif ?>><?php echo $s_directory ?></option>
			<?php endforeach ?>
		</select>
		<?php endif ?>
  </div>
	<table id="file_list" class="table table-striped">
		<colgroup>
			<col style="width:10%" />
			<col />
			<col style="width:20%" />
			<col style="width:10%" />
			<col style="width:10%" class="hidden-xs" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" id="check_all" /></th>
				<th><?php echo _('Name') ?></th>
				<th><?php echo _('Size') ?></th>
				<th><?php echo _('Date') ?></th>
				<th class="hidden-xs"><?php echo _('Time') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($current_folder!='.'):
            $pathinfo = pathinfo($current_folder);

    if ($pathinfo['dirname'] == DIRECTORY_SEPARATOR) {
        $link = WEB_ROOT_DIRECTORY . 'index.php';
    } else {
        $link = WEB_ROOT_DIRECTORY . 'index.php?dir=' . $pathinfo['dirname'];
    } ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">
					<a href="<?php echo $link ?>"><?php echo _('Go To Parent Directory') ?></a>
				</td>
			</tr>
			<?php endif ?>
			<?php if (count($list)): ?>
			<?php foreach ($list as $index=>$value): ?>
			<tr>
				<td>
					<input type="checkbox" name="file_check[]" id="file_check<?php echo $index ?>" value="<?php echo $value['name'] ?>" style="vertical-align:top" />
					<input type="hidden" name="type[]" value="<?php echo $value['type'] ?>" />
					<input type="hidden" name="name[]" value="<?php echo $value['name'] ?>" />
					<?php if ($value['type']=='directory'): ?>
							<label for="file_check<?php echo $index ?>"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_folder.gif" width="16" height="16" alt="<?php echo _('Direcotry') ?>" /></label>
					<?php else: ?>
							<label for="file_check<?php echo $index ?>"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_file.gif" width="16" height="16" alt="<?php echo _('File') ?>" /></label>
					<?php endif ?>
				</td>
				<td>
					<?php if ($value['type']=='directory'): ?>
					<a href="<?php echo WEB_ROOT_DIRECTORY?>index.php?dir=<?php if ($current_folder!='.'): ?><?php echo $current_folder ?><?php echo DIRECTORY_SEPARATOR ?><?php endif ?><?php echo $value['name'] ?>" ><?php echo $value['name'] ?></a>
					<?php else: ?>
					<a href="<?php echo WEB_ROOT_DIRECTORY?>download.php?file=<?php echo $current_folder ?><?php echo DIRECTORY_SEPARATOR ?><?php echo $value['name'] ?>" ><?php echo $value['name'] ?></a>
					<?php endif ?>
				</td>
				<td>
					<?php echo Config\bytesToSize1024($value['size']) ?>
				</td>
				<td>
					<?php echo $value['month'] ?>/<?php echo $value['day'] ?>
				</td>
				<td class="hidden-xs">
					<?php echo $value['time'] ?>
				</td>
			</tr>
			<?php endforeach ?>
			<?php else: ?>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<?php endif ?>
		</tbody>
	</table>
	</div>
  <div id="sl_ad">
  <?php if (DEBUG==1): ?>
  <div style="width:100%;padding-top:10%;background:red">&nbsp;</div>
  <?php else: ?>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- default_ad -->
  <ins class="adsbygoogle"
       style="display:block"
       data-ad-client="ca-pub-5400903051441488"
       data-ad-slot="8412654331"
       data-ad-format="auto"></ins>
  <script>
  (adsbygoogle = window.adsbygoogle || []).push({});
  </script>
  <?php endif ?>
  </div>

		</div>
  </div>
	<footer>
    <div class="container-fluid">
    <div class="row">
		<!--<?php if ($sl_connect_info['host']=='localhost'): ?>
		<dl class="col-xs-12 col-sm-8 col-md-6 col-lg-9">
			<dt class="col-xs-6 col-sm-5 col-md-3 col-lg-2"><?php echo _('Free Space') ?></dt>
      <dd class="col-xs-6 col-sm-7 col-md-9 col-lg-10"><?php echo Config\bytesToSize1024(\disk_free_space(TMP_DIR)) ?></dd>
      <dt class="col-xs-6 col-sm-5 col-md-3 col-lg-2"><?php echo _('All Space') ?></dt>
      <dd class="col-xs-6 col-sm-7 col-md-9 col-lg-10"><?php echo Config\bytesToSize1024(\disk_total_space(TMP_DIR)) ?></dd>
		</dl>
		<?php else: ?>
		<dl class="col-xs-12 col-sm-8 col-md-6 col-lg-9">
			<dt class="col-xs-6 col-sm-5 col-md-3 col-lg-2"><?php echo _('Temp Upload Folder Free Space') ?></dt>
      <dd class="col-xs-6 col-sm-7 col-md-9 col-lg-10"><?php echo Config\bytesToSize1024(\disk_free_space(TMP_DIR)) ?></dd>
		</dl>
  <?php endif ?> -->
    <address class="col-xs-12 col-sm-8 col-md-6 col-lg-9"><a href="http://www.sleepinglion.pe.kr" title="sleepinglion`s homepage" target="_blank" />SleepingLion</a></address>
    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3">
      <?php include __DIR__ . DIRECTORY_SEPARATOR .'locale_form.php' ?>
    </div>
  </div>
</div>
	</footer>
	<div class="slboard_overlay" id="overlay"></div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog"></div>
	<script src="<?php echo ASSET_DIRECTORY?>js/jquery-2.1.1.min.js" defer></script>
	<script src="<?php echo ASSET_DIRECTORY?>js/bootstrap.min.js" defer></script>
  <script src="<?php echo ASSET_DIRECTORY?>js/lang.js.php"></script>
	<script src="<?php echo ASSET_DIRECTORY?>js/index.js" defer></script>
</body>
</html>
<?php

} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'error.php';
}
?>
