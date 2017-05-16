<?php

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (isset($_POST['dir'])) {
        $current_folder = $_POST['dir'];
    }

    if (isset($_POST['folder_name'])) {
        $file = $_POST['folder_name'];
    }

    if (empty($file)) {
        throw new Exception(_('Folder Name Not Submit'), 4);
    }

    $config = new \sl_ftp\config($_SESSION['sl_connect_info']);

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($config -> host, $config -> ssl, $config -> port);
    $ftp -> login($config -> username, $config -> userpass);
    $ftp -> chdir($current_folder);

    if ($ftp -> mkdir($file)) {
        echo "successfully created $file\n";
    } else {
        echo "There was a problem while creating $file\n";
    }

    if (empty($current_folder)) {
        header('Location: ' . $config -> web_root_directory . 'index.php');
    } else {
        header('Location: ' . $config -> web_root_directory . 'index.php?dir=' . $current_folder);
    }
} catch (Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
