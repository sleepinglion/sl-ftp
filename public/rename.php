<?php

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
        throw new Exception(_('File Names Not Submit'), 5);
    }

    if (!count($files)) {
        return false;
    }

    $config = new \sl_ftp\config($_SESSION['sl_connect_info']);

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($config -> host, $config -> ssl, $config -> port);
    $ftp -> login($config -> username, $config -> userpass);
    $ftp -> chdir($current_folder);

    foreach ($files as $value) {
        $results[$value['old_name']] = $ftp -> rename($value['old_name'], $value['new_name']);
    }

    if (empty($current_folder)) {
        header('Location: ' . $config -> web_root_directory . 'index.php');
    } else {
        header('Location: ' . $config -> web_root_directory . 'index.php?dir=' . $current_folder);
    }
} catch (Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
