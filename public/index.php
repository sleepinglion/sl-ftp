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
	<link href="<?php echo IMAGE_DIRECTORY?>favicon.ico" type="image/x-icon" rel="shortcut icon">
	<link href="<?php echo BOOTSTRAP_CSS_DIRECTORY?>bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
	<link href="<?php echo CSS_DIRECTORY?>index.css" media="all" type="text/css" rel="stylesheet">
</head>
<body>
	<header>
		<div class="container">
      <div class="row">
        <div id="directory_info" class="col-12 col-sm-6">
          <div class="col-12">
      <label for="visited_directory"><?php echo _('Current Directory') ?></label>  : <?php if ($current_folder=='.'): ?><?php echo DIRECTORY_SEPARATOR ?><?php else: ?><?php echo $current_folder ?>(<?php echo $total ?><?php echo _('Files') ?>)<?php endif ?>
      </div>
      <div class="col-12">
      <label for="visited_directory"><?php echo _('Visited Directory') ?></label>
        <?php if (count($_SESSION['directory'])): ?>
        <select name="visited_directory" id="visited_directory">
          <?php foreach ($_SESSION['directory'] as $s_key=>$s_directory): ?>
          <option value="<?php echo $s_key ?>" <?php if ($s_key==$current_folder): ?>selected="selected"<?php endif ?>><?php echo $s_directory ?></option>
          <?php endforeach ?>
        </select>
        <?php endif ?>
      </div>
      </div>

        <nav class="hidden-xs col-sm-6">

          <ul class="nav nav-pills">
            <?php if (isset($_SESSION['sl_connect_info']['host'])): ?>
            <li><a href="<?php echo WEB_ROOT_DIRECTORY?>index.php?logout=true" id="logout" class="btn btn-secondary"><?php echo _('Logout') ?><span class="visible-xs glyphicon glyphicon-chevron-right float-right"></span></a></li>
            <?php endif ?>
          </ul>
        </nav>
      </div>
		</div>
	</header>
	<div id="mom">
		<div id="main" class="container">

    <div class="row">
    <?php include __DIR__ . DIRECTORY_SEPARATOR .'ad.php' ?>
    </div>

      <div class="row">
      <div class="col-12">
			<input type="hidden" id="web_root_directory" value="<?php echo WEB_ROOT_DIRECTORY?>" />
			<input type="hidden" id="directory_separator" value="<?php echo DIRECTORY_SEPARATOR ?>" />
			<input type="hidden" id="current_folder" value="<?php echo $current_folder ?>" />
	<div class="table-responsive">

	<table id="file_list" class="table table-striped">
		<colgroup>
			<col style="width:10%">
			<col>
			<col style="width:20%">
			<col style="width:10%">
			<col style="width:220px">
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" class="check_all form-check-input"></th>
				<th><?php echo _('Name') ?></th>
				<th><?php echo _('Size') ?></th>
				<th><?php echo _('Time') ?></th>
				<th class="text-center"><?php echo _('Manage') ?></th>
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
          <div class="form-check">
					<input type="checkbox" name="file_check[]" id="file_check<?php echo $index ?>" value="<?php echo $value['name'] ?>" class="form-check-input">
					<input type="hidden" name="type[]" value="<?php echo $value['type'] ?>" />
					<input type="hidden" name="name[]" value="<?php echo $value['name'] ?>" />
					<?php if ($value['type']=='directory'): ?>
							<label class="form-check-label" for="file_check<?php echo $index ?>" style="vertical-align:top"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_folder.gif" width="16" height="16" alt="<?php echo _('Direcotry') ?>"></label>
					<?php else: ?>
							<label class="form-check-label" for="file_check<?php echo $index ?>" style="vertical-align:top"><img src="<?php echo WEB_ROOT_DIRECTORY?>images/icon_16_file.gif" width="16" height="16" alt="<?php echo _('File') ?>"></label>
					<?php endif ?>
          </div>
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
					<?php echo $value['month'] ?>/<?php echo $value['day'] ?> <?php echo $value['time'] ?>
				</td>
				<td class="hidden-xs text-center">
            <a href="/rename_form.php?dir=<?php echo $current_folder ?>&amp;file=<?php echo $value['name'] ?>&amp;type=<?php echo $value['type'] ?>" class="btn btn-secondary btn-modal" data-target="#myModal" data-toggle="modal"><?php echo _('Rename') ?></a>
            <a href="/delete_confirm_form.php?dir=<?php echo $current_folder ?>&amp;file=<?php echo $value['name'] ?>" class="btn btn-danger"><?php echo _('Delete') ?></a>
				</td>
			</tr>
			<?php endforeach ?>
			<?php else: ?>
			<tr>
        <td>&nbsp;</td>
        <td><?php echo _('No File') ?></td>
				<td colspan="3">&nbsp;</td>
			</tr>
			<?php endif ?>
		</tbody>
		<?php if (count($list)): ?>
    <tfoot>
      <tr>
  			<td><input type="checkbox" class="check_all form-check-input"></td>
        <td>
          <p style="float:left;margin-right:10px;margin-top:8px"><?php echo _('Selected Do') ?></p>
          <ul style="float:left" class="nav nav-pills">
          <li><a href="<?php echo WEB_ROOT_DIRECTORY?>mkzip.php<?php echo $dir_param ?>" id="download" class="btn btn-light disabled"><?php echo _('Download') ?><span class="visible-xs glyphicon-chevron-right pull-right"></span></a>&nbsp;&nbsp;</li>
          <li><a href="<?php echo WEB_ROOT_DIRECTORY?>delete.php<?php echo $dir_param ?>" id="delete" class="btn btn-light disabled"><?php echo _('Delete') ?><span class="visible-xs glyphicon-chevron-right pull-right"></span></a></li>
          </ul>
        </td>
        <td colspan="3">&nbsp;
        </td>
      </tr>
    </tfoot>
    <?php endif ?>
	</table>
	</div>
	</div>
  </div>


<div class="row">
  <ul id="create_menu" class="col-12 col-lg-6">
    <li><a href="<?php echo WEB_ROOT_DIRECTORY?>upload_form.php<?php echo $dir_param ?>" id="upload" class="btn btn-primary" target="_blank"><?php echo _('Upload') ?><span class="visible-xs  glyphicon-chevron-right pull-right"></span></a></li>
    <li><a href="<?php echo WEB_ROOT_DIRECTORY?>mkdir_form.php<?php echo $dir_param ?>" data-target="#myModal" data-toggle="modal" class="btn-modal btn btn-secondary"><?php echo _('New Folder') ?><span class="visible-xs glyphicon-chevron-right pull-right"></span></a></li>
  </ul>

  <?php if ($sl_connect_info['host']=='localhost'): ?>
		<dl class="col-12 col-lg-6 text-end">
			<dt><?php echo _('Free Space') ?></dt>
      <dd><?php echo Config\bytesToSize1024(\disk_free_space(TMP_DIR)) ?></dd>
      <dt><?php echo _('All Space') ?></dt>
      <dd><?php echo Config\bytesToSize1024(\disk_total_space(TMP_DIR)) ?></dd>
		</dl>
		<?php else: ?>
		<dl class="col-12 col-lg-6 text-end">
			<dt><?php echo _('Temp Upload Folder Free Space') ?></dt>
      <dd><?php echo Config\bytesToSize1024(\disk_free_space(TMP_DIR)) ?></dd>
		</dl>
  <?php endif ?>
</div>
    </div>
  </div>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>
		</div>
  </div>
  <div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"></div>
	<script src="<?php echo JS_DIRECTORY ?>jquery.min.js"></script>
	<script src="<?php echo BOOTSTRAP_JS_DIRECTORY?>bootstrap.min.js"></script>
  <script src="<?php echo JS_DIRECTORY ?>lang.js.php"></script>
	<script src="<?php echo JS_DIRECTORY ?>index.js"></script>
</body>
</html>
<?php

} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'error.php';
}
?>
