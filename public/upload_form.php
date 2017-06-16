<?php

namespace SleepingLion\SL_FTP;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

function convertPHPSizeToBytes($sSize)
{
    if (is_numeric($sSize)) {
        return $sSize;
    }
    $sSuffix = substr($sSize, -1);
    $iValue = substr($sSize, 0, -1);
    switch (strtoupper($sSuffix)) {
        case 'P':
            $iValue *= 1024;
        case 'T':
            $iValue *= 1024;
        case 'G':
            $iValue *= 1024;
        case 'M':
            $iValue *= 1024;
        case 'K':
            $iValue *= 1024;
            break;
    }
    return $iValue;
}

function getMaximumFileUploadSize()
{
    return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));
}

if (isset($_GET['dir'])) {
    $current_folder = $_GET['dir'];
}

if (empty($current_folder)) {
    $current_folder = '.';
}

$max_uploads_filesize = getMaximumFileUploadSize();
$max_uploads_files = ini_get('max_file_uploads');
?>
<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<meta charset="utf-8">
	<title><?php printf(_('Upload Files %s'), $current_folder) ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<link href="<?php echo ASSET_DIRECTORY ?>images/favicon.ico" type="image/x-icon" rel="shortcut icon">
	<link href="<?php echo ASSET_DIRECTORY ?>css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
	<link href="<?php echo ASSET_DIRECTORY ?>css/uploads.css" media="all" type="text/css" rel="stylesheet">
</head>
<body>
	<article class="container">
	<div class="contr"><h2><?php echo _('Drag and Drop Your File On Drop Area') ?></h2><p><?php \printf(_('Files Size under %s AND File Count under %s'), Config\bytesToSize1024($max_uploads_filesize), $max_uploads_files) ?></p></div>
	<div class="upload_form_cont">
	<div id="dropArea" title="ggg"><?php echo _('Drop Area') ?></div>
	<div class="info">
	<div><?php echo _('Files Left') ?>: <span id="count">0</span></div>
	<div><input id="url" type="hidden" value="<?php echo WEB_ROOT_DIRECTORY ?>upload.php"/></div>
	<h2><?php echo _('Result') ?>:</h2>
	<div id="result"></div>
	<canvas width="500" height="20"></canvas>
	</div>
	</div>
	</article>
	<input type="hidden" id="max_file_uploads" value="<?php echo $max_uploads_filesize ?>" />
	<article>
		<h3><a href="" id="unable_dd_link"><?php echo _('Unable To Use Drag and Drop') ?></a></h3>
		<form id="upload_form" enctype="multipart/form-data" id="upload_form" action="<?php echo WEB_ROOT_DIRECTORY ?>upload.php" method="post" class="form-inline">
			<div class="form-group">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE ?>" />
				<input type="hidden" name="dir" value="<?php echo $current_folder ?>" />
				<input type="file" name="userfile" required="required" />
			</div>
			<div class="form-group">
				<input type="submit" value="<?php echo _('Upload') ?>" class="btn btn-primary" />
			</div>
		</form>
	</article>
	<script src="<?php echo ASSET_DIRECTORY ?>js/jquery-2.1.1.min.js"></script>
	<script src="<?php echo ASSET_DIRECTORY ?>js/uploads.js"></script>
</body>
</html>
