<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ . "/../DataAccess/ElasticDataAccess/ElasticDA.php";

session_start();
$user_id = $_SESSION['id'];
$id = $_POST['id'];
$thumb_id = $_POST['thumb_id'];

// init connect to cassandra
$connect = new CassandraDA();

//init connect to elastic
$eDA = new ElasticDA();

$statement = new Cassandra\SimpleStatement(
    "select * from file_info where user_id = " . $user_id . " and file_id = " . $id
);

$result = $connect->get_connection()->execute($statement);

if ($result[0]['type'] == 7 || $result[0]['type'] == 8 || $result[0]['type'] == 9) {
    $file_thumb = new DataThumbnail();

    $file_thumb->setFileId(new Cassandra\UUID($id));
    $file_thumb->setId(new Cassandra\UUID($thumb_id));

    $connect->deleteRow('thumbnail', $file_thumb);
}

$statement = new Cassandra\SimpleStatement(
    "select * from count_content where file_id = " . $id
);

$count = $connect->get_connection()->execute($statement);

$file_content = new DataFileContent();

$file_content->setFileId(new Cassandra\UUID($id));

for ($i = 0; $i < $count[0]['count']; $i++) {
    $file_content->setPart($i);

    $connect->deleteRow('file_content', $file_content);

}

$file_content_count = new DataCountContent();
$file_content_count->setFileId(new Cassandra\UUID($id));

$connect->deleteRow('count_content', $file_content_count);


$file_info = new DataFileInfo();
$file_info->setUserId(new Cassandra\UUID($user_id));
$file_info->setFileId(new Cassandra\UUID($id));

$connect->deleteRow('file_info', $file_info);
