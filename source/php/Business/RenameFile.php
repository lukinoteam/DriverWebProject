<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";

//init connect to cassandra
$connect = new CassandraDA();

//start session to get user id
session_start();
$user_id = $_SESSION['id'];

//init connect to elastic
$eDA = new ElasticDA();

$id = $_POST['id'];
$type = $_POST['type'];

$newName = $_POST['newName'];


if ($type == 0){
    //file
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
    
    $eDA->update_with_name($id, $newName);
    $connect->insert('file_info', $newInfo);
}
else if ($type == 1){
    //folder
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

    $eDA->update_folder_with_name($id, $newName);
    $connect->insert('folder_info', $newInfo);
}
?>
