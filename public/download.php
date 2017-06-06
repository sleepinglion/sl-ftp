<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    if (isset($_GET['file'])) {
        $file = $_GET['file'];
    }

    $filename = basename($file);
    $local_file = TMP_DIR . DIRECTORY_SEPARATOR . $filename;
    $server_file = $file;

    $ftp = new \FtpClient\FtpClient();
    $ftp -> connect($sl_connect_info['host'], $sl_connect_info['ssl'], $sl_connect_info['port']);
    $ftp -> login($sl_connect_info['username'], $sl_connect_info['userpass']);
    $list = $ftp -> scanDir(pathinfo($file, PATHINFO_DIRNAME));

    $file_exists = false;
    foreach ($list as $index => $value) {
        if ($value['name'] == $filename) {
            $file_exists = true;
        }
    }

    if (empty($file_exists)) {
        throw new \Exception(_('File Not Exists'), 1);
    }

    if ($ftp -> get($local_file, $server_file, FTP_BINARY)) {
        $isError = 0;
    } else {
        $isError = 1;
    }

    if ($isError == 0) {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($local_file) . "\"");
        // quotes required for spacing in filename
        header("Content-Length: " . filesize($local_file));

        flush();

        $fp = @fopen($local_file, "r");
        while (!feof($fp)) {
            echo @fread($fp, 65536);
            @flush();
        }
        @fclose($fp);

        unlink($local_file);
    }
} catch (\Exception $e) {
    include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
}
