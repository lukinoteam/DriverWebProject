<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";

$connect = new CassandraDA();

session_start();
$user_id = $_SESSION['id'];

$id = $_POST['id'];
$type = $_POST['type'];

$newName = $_POST['newName'];


if ($type == 0){
    $statement = new Cassandra\SimpleStatement(
        "select * from file_info where user_id = ".$user_id." and file_id = ".$id." "
    );
    
    $result = $connect->get_connection()->execute($statement);
    
    $newInfo = new DataFileInfo();
    $newInfo->setUserId($result[0]['user_id']);
    $newInfo->setFileId($result[0]['file_id']);
    $newInfo->setName($newName);
    $newInfo->setSize($result[0]['size']);
    $newInfo->setDateModify(time());
    $newInfo->setDescription($result[0]['description']);
    $newInfo->setType($result[0]['type']);
    $newInfo->setStatus($result[0]['status']);
    
    $connect->insert('file_info', $newInfo);
}
else if ($type == 1){
    $statement = new Cassandra\SimpleStatement(
        "select * from folder_info where user_id = ".$user_id." and folder_id = ".$id." "
    );
    
    $result = $connect->get_connection()->execute($statement);
    
    $newInfo = new DataFolderInfo();
    $newInfo->setUserId($result[0]['user_id']);
    $newInfo->setFolderId($result[0]['folder_id']);
    $newInfo->setName($newName);
    $newInfo->setSize($result[0]['size']);
    $newInfo->setDateModify(time());
    $newInfo->setDescription($result[0]['description']);
    $newInfo->setStatus($result[0]['status']);

    
    $connect->insert('folder_info', $newInfo);
}
?>
