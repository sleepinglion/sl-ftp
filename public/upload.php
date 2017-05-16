<?php

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (empty($_POST['dir'])) {
        throw new Exception(_('Current Folder Not Submit'), 4);
    }

    $current_folder = $_POST['dir'];

    $upload_directory=TMP_DIR. DIRECTORY_SEPARATOR . UPLOAD_DIR;

    if (!file_exists($upload_directory)) {
        if (!mkdir($upload_directory)) {
            throw new Exception(print_r(_('Unable To Make %s Directory'), $upload_directory), 7);
        }
    }

    $filename = basename($_FILES['userfile']['name']);
    $uploadfile = $upload_directory . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        throw new Exception(_('Upload File Move Fail'), 8);
    }

    $config = new \sl_ftp\config($_SESSION['sl_connect_info']);

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($config -> host, $config -> ssl, $config -> port);
    $ftp -> login($config -> username, $config -> userpass);
    $ftp -> chdir($current_folder);

    if ($ftp -> put($filename, $uploadfile, FTP_BINARY)) {
        //echo "Successfully written to $uploadfile\n";
    } else {
        $isError = 1;
    }


    $sFileName = $_FILES['userfile']['name'];
    $sFileType = $_FILES['userfile']['type'];
    $sFileSize = \sl_ftp\bytesToSize1024($_FILES['userfile']['size'], 1);

    echo '<div class="s">
		<p>Your file: '.$sFileName.' has been successfully received.</p>
		<p>Type: '.$sFileType.'</p>
		<p>Size: '.$sFileSize.'</p>
	</div>';
} catch (Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
