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

    $json = false;
    if (isset($_POST['json'])) {
        $json = true;
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

    $download_list = getAllFtpList($ftp, $current_folder, $files);
    $dir = TMP_DIR . DIRECTORY_SEPARATOR . uniqid();
    $zipfileName = 'sl_ftp_'.date('Y_m_d_H_i_s').'.zip';
    $zipfilePath = TMP_DIR . DIRECTORY_SEPARATOR . $zipfileName;
    mkdir($dir);
    getAllFiles($ftp, $dir, $download_list);
    makeZipFile($dir, $zipfilePath);

    if ($json) {
        echo json_encode(array('result' => 'success', 'zip_file' => $sl_connect_info['web_root_directory'] . 'tmp/' . $zipfileName));
    } else {
        printf(_('Successfully Make %s File'), $sl_connect_info['web_root_directory'] . 'tmp/' . $zipfileName);
    }
} catch (\Exception $e) {
    if ($json) {
        echo json_encode(array('reuslt' => 'error', 'message' => $e -> getMessage()));
    } else {
        include __DIR__ . DIRECTORY_SEPARATOR . '500.php';
    }
}

function getAllFtpList($ftp, $current_folder, $files = false)
{
    $list = $ftp -> scanDir($current_folder);

    $downlist = array();
    foreach ($list as $file) {
        if (empty($files)) {
            if ($file['type'] == 'directory') {
                $downlist[$file['name']] = getAllFtpList($ftp, $current_folder . DIRECTORY_SEPARATOR . $file['name']);
            } else {
                $file['full_path'] = $current_folder . DIRECTORY_SEPARATOR . $file['name'];
                $downlist[] = $file;
            }
        } else {
            foreach ($files as $key => $value) {
                if ($file['name'] != $value) {
                    continue;
                }

                if ($file['type'] == 'directory') {
                    $downlist[$file['name']] = getAllFtpList($ftp, $current_folder . DIRECTORY_SEPARATOR . $file['name']);
                } else {
                    $file['full_path'] = $current_folder . DIRECTORY_SEPARATOR . $file['name'];
                    $downlist[] = $file;
                }
            }
        }
    }

    return $downlist;
}

function makeZipFile($rootPath, $filename)
{
    // Initialize archive object
    $zip = new \ZipArchive();
    $zip -> open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootPath), \RecursiveIteratorIterator::LEAVES_ONLY);

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file -> isDir()) {
            // Get real and relative path for current file
            $filePath = $file -> getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip -> addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip -> close();
}

function getAllFiles($ftp, $current_folder, $download_list)
{
    foreach ($download_list as $key => $file) {
        if (isset($file['type'])) {
            $handle = \fopen($current_folder . DIRECTORY_SEPARATOR . $file['name'], 'w');
            $ftp -> fget($handle, $file['full_path'], FTP_BINARY, 0);
            fclose($handle);
        } else {
            $dir = $current_folder . DIRECTORY_SEPARATOR . $key;
            mkdir($dir);
            getAllFiles($ftp, $dir, $file);
        }
    }
}
