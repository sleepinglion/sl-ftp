<?php

namespace SleepingLion\SL_FTP;

try {
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    require __DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    $_SESSION['DELETE_FOLDER'] = array();

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
    $ftp -> pasv($sl_connect_info['pasv']);

    $delete_list = getAllDeleteList($ftp, $current_folder, $files);

    $files_results = deleteAllFiles($ftp, $current_folder, $delete_list);
    $folders_results = deleteAllFolders($ftp);

    echo json_encode(array('result' => 'success', 'delete_file_result' => $files_results, 'delete_folder_result' => $folders_results));
} catch (\Exception $e) {
    echo json_encode(array('result' => 'fail', 'code' => $e -> getCode(), 'message' => $e -> getMessage()));
}

function getAllDeleteList($ftp, $current_folder, $files = false)
{
    $list = $ftp -> scanDir($current_folder);

    $deletelist = array();
    foreach ($list as $file) {
        if (empty($files)) {
            $file['full_path'] = $current_folder . DIRECTORY_SEPARATOR . $file['name'];
            if ($file['type'] == 'directory') {
                $deletelist[$file['full_path']] = getAllDeleteList($ftp, $current_folder . DIRECTORY_SEPARATOR . $file['name']);
            } else {
                $deletelist[] = $file;
            }
        } else {
            foreach ($files as $key => $value) {
                if ($file['name'] != $value) {
                    continue;
                }

                $file['full_path'] = $current_folder . DIRECTORY_SEPARATOR . $file['name'];
                if ($file['type'] == 'directory') {
                    $deletelist[$file['full_path']] = getAllDeleteList($ftp, $current_folder . DIRECTORY_SEPARATOR . $file['name']);
                } else {
                    $deletelist[] = $file;
                }
            }
        }
    }

    return $deletelist;
}

function deleteAllFolders($ftp)
{
    $folders = array_reverse($_SESSION['DELETE_FOLDER']);

    $result = array();
    foreach ($folders as $index => $value) {
        $result[$value] = $ftp -> remove($value, true);
    }

    unset($_SESSION['DELETE_FOLDER']);
    return $result;
}

function deleteAllFiles($ftp, $current_folder, $delete_list)
{
    $result = array();
    $delete_directory = array();
    foreach ($delete_list as $key => $file) {
        if (isset($file['type'])) {
            $result[$file['full_path']] = $ftp -> remove($file['full_path'], true);
        } else {
            $dir = $current_folder . DIRECTORY_SEPARATOR . $key;
            $_SESSION['DELETE_FOLDER'][] = $key;
            $result[$file['full_path']] = deleteAllFiles($ftp, $dir, $file);
        }
    }

    return $result;
}
