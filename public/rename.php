<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (isset($_POST['dir'])) {
        $current_folder = $_POST['dir'];
    }

    if (isset($_POST['files'])) {
        $files = $_POST['files'];
    }

    if (empty($files)) {
        throw new \Exception(_('File Names Not Submit'), 5);
    }

    if (!count($files)) {
        return false;
    }

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($sl_connect_info['host'], $sl_connect_info['ssl'], $sl_connect_info['port']);
    $ftp -> login($sl_connect_info['username'], $sl_connect_info['userpass']);

    if (!empty($sl_connect_info['pasv'])) {
        $ftp -> pasv($sl_connect_info['pasv']);
    }

    $ftp -> chdir($current_folder);

    foreach ($files as $value) {
        $results[$value['old_name']] = $ftp -> rename($value['old_name'], $value['new_name']);
    }

    if (empty($current_folder)) {
        header('Location: ' . WEB_ROOT_DIRECTORY. 'index.php');
    } else {
        header('Location: ' . WEB_ROOT_DIRECTORY. 'index.php?dir=' . $current_folder);
    }
} catch (\Exception $e) {
    if ($json) {
        echo json_encode(array('result' => 'fail', 'code' => $e -> getCode(), 'message' => $e -> getMessage()));
    } else {
        include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
    }
}
