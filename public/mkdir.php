<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (empty($_POST['folder_name'])) {
        throw new \Exception(_('Folder Name Not Submit'), 4);
    }

    if (isset($_POST['dir'])) {
        $current_folder = $_POST['dir'];
    }

    if (isset($_POST['folder_name'])) {
        $file = $_POST['folder_name'];
    }

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($sl_connect_info['host'], $sl_connect_info['ssl'], $sl_connect_info['port']);
    $ftp -> login($sl_connect_info['username'], $sl_connect_info['userpass']);
    $ftp -> chdir($current_folder);

    if (!$ftp -> mkdir($file)) {
        throw new \Exception(sprintf(_("There was a problem while creating %s"), $file), 1);
    }

    if (empty($current_folder)) {
        header('Location: ' . WEB_ROOT_DIRECTORY . 'index.php');
    } else {
        header('Location: ' . WEB_ROOT_DIRECTORY . 'index.php?dir=' . $current_folder);
    }
} catch (\Exception $e) {
    if ($json) {
        echo json_encode(array('result' => 'fail', 'code' => $e -> getCode(), 'message' => $e -> getMessage()));
    } else {
        include __DIR__ . DIRECTORY_SEPARATOR . 'error.php';
    }
}
