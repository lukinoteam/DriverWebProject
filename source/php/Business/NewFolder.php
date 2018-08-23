<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";

// init connect to cassandra
$connect = new CassandraDA();

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];
//Elastic DataAccess
$eDA = new ElasticDA();

//get current folder
$current = $_POST['current'];
if ($current == "home"){
    $current = $user_id;
}
$current = new Cassandra\UUID($current);

// get folder ename from client
$folderName = $_POST['folderName'];
$desc = $_POST['desc'];

$newFolder = new DataFolder();

$newFolder->setFolderId(new Cassandra\UUID());
$newFolder->setFileId($newFolder->getFolderId());
$newFolder->setStatus(1);

$connect->insert('folder', $newFolder);

$folder = new DataFolder();
$folder->setFolderId($current);
$folder->setFileId($newFolder->getFolderId());
$folder->setStatus(1);

$connect->insert('folder', $folder);

$folderInfo = new DataFolderInfo();

$folderInfo->setUserId(new Cassandra\UUID($user_id));
$folderInfo->setFolderId($newFolder->getFolderId());
$folderInfo->setDateModify(time());
$folderInfo->setDescription($desc);
$folderInfo->setName($folderName);
$folderInfo->setStatus(1);

$connect->insert('folder_info', $folderInfo);
$eDA->index_folder($newFolder->getFolderId(), 1);

ob_end_clean();
echo json_encode(array($folderInfo->getName(), $folderInfo->getFolderId(), $folderInfo->getDateModify(), $folderInfo->getDescription(), $folderInfo->getStatus()));
