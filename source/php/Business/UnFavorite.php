<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";

session_start();
$user_id = $_SESSION['id'];

$id = $_POST['id'];

// init connect to cassandra
$connect = new CassandraDA();

//init connect to elastic
$eDA = new ElasticDA();

$statement = new Cassandra\SimpleStatement(
    "select * from file_info where user_id = ".$user_id." and file_id = ".$id
);
        
$result = $connect->get_connection()->execute($statement);

$newInfo = new DataFileInfo();
$newInfo->setUserId($result[0]['user_id']);
$newInfo->setFileId($result[0]['file_id']);
$newInfo->setName($result[0]['name']);
$newInfo->setSize($result[0]['size']);
$newInfo->setDateModify(time());
$newInfo->setDescription($result[0]['description']);
$newInfo->setType($result[0]['type']);
$newInfo->setStatus(1);

$connect->insert('file_info', $newInfo);
$response = $eDA->update_with_status($id, 1);
?>