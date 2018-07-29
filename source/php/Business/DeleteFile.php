<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";

$connect = new CassandraDA();
$eDA = new ElasticDA;

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

$id = $_POST['id'];
$type = $_POST['type'];



if ($type == 0){
    $temp_state = "select * from file_info where user_id = ".$user_id." and file_id = ".$id." ";
    $temp_state = str_replace('""', '', $temp_state);

    $temp_state = preg_replace('/[^A-Za-z0-9\-*-_]/', ' ', $temp_state);
    $statement = new Cassandra\SimpleStatement(
        $temp_state
    );
    // var_dump($temp_state);
    
    $result = $connect->get_connection()->execute($statement);
    
    $newInfo = new DataFileInfo();
    $newInfo->setUserId($result[0]['user_id']);
    $newInfo->setFileId($result[0]['file_id']);
    $newInfo->setName($result[0]['name']);
    $newInfo->setSize($result[0]['size']);
    $newInfo->setDateModify(time());
    $newInfo->setDescription($result[0]['description']);
    $newInfo->setType($result[0]['type']);
    $newInfo->setStatus(-1);
    
    $eDA->update_with_status($id, -1);
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
    $newInfo->setName($result[0]['name']);
    $newInfo->setSize($result[0]['size']);
    $newInfo->setDateModify(time());
    $newInfo->setDescription($result[0]['description']);
    $newInfo->setStatus(-1);

    $connect->insert('folder_info', $newInfo);
}
?>