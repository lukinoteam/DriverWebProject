<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
require_once 'ThumbGenerator.php';

// init connect to cassandra
$connect = new CassandraDA();

// empty table (for testing only)
// $connect->truncate('file_content');
// $connect->truncate('count_content');

// get file and filename from client
$fileName = $_POST['name'];
$filePath = $_FILES['file']['tmp_name'];
$fileDesc = $_POST['desc'];
$current = new Cassandra\UUID($_POST['current']);

// open file for streaming
$file = fopen($filePath, "r");

// generate id for new file
$data = new DataFileContent();
$data->setFileId(new Cassandra\UUID());

$count = new DataCountContent();
$count->setFileId($data->getFileId());

$part = 0;


while (!feof($file)) {
    /*
     *  Read in 500kb chunks. Could make this
     *  larger, but as a rule of thumb, keep it to 1/4 of
     *  php memory_limit.
     */

    $chunk = fread($file, 500000);
    $chunk = bin2hex($chunk);

    $data->setPart($part);
    $data->setContent($chunk);
    $connect->insert('file_content', $data);
    $connect->updateCount($count->getFileId());

    $part++;
}

$ext = pathinfo($fileName, PATHINFO_EXTENSION);
$ext = strtolower($ext);

$fileInfo = new DataFileInfo();
$fileInfo->setUserId(new Cassandra\UUID('825af7a2-e66c-4b5b-9289-5e203939ae04'));
$fileInfo->setFileId($data->getFileId());
$fileInfo->setName($fileName);
$fileInfo->setSize(filesize($filePath));
$fileInfo->setDateModify(time());
$fileInfo->setDescription($fileDesc);
$fileInfo->setStatus(1);

$thumb = new DataThumbnail();
$thumb->setFileId($data->getFileId());
$thumb->setId(new Cassandra\UUID());
$thumb->setImage(make_thumb($filePath, $ext));
$thumb->setStatus(1);

switch ($ext) {
    case 'jpg':
    case 'jpeg':
        $fileInfo->setType(7);
        $thumb->setType(7);
        break;
    case 'png':
        $fileInfo->setType(8);
        $thumb->setType(8);
        break;
    default:
        $fileInfo->setType(14);
        $thumb->setType(14);
}

$connect->insert('file_info', $fileInfo);
$connect->insert('thumbnail', $thumb);

$folder = new DataFolder();
$folder->setFolderId($current);
$folder->setFileId($data->getFileId());

$connect->insert('folder', $folder);

$statement = new Cassandra\SimpleStatement(
    "select size from folder_info where user_id = 825af7a2-e66c-4b5b-9289-5e203939ae04 and folder_id = ".$current
);

$result = $connect->get_connection()->execute($statement);
$newSize = $result[0]['size'] + $fileInfo->getSize();

$statement = new Cassandra\SimpleStatement(
    "insert into folder_info (user_id, folder_id, size) 
    values (825af7a2-e66c-4b5b-9289-5e203939ae04, ".$current.", ".$newSize.")"
);

$connect->get_connection()->execute($statement);

fclose($file);
