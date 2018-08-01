<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ . "/../DataAccess/MySQL/MySQLDA.php";

// init connect to cassandra
$connect = new CassandraDA();

//init connect to mysql

$sqlHelper = new MySQLDA();

//start session to get user-id
session_start();
$user_id = $_SESSION['id'];
$current_account = $_SESSION['account'];

//get shared email
$shared_email = $_POST['email'];
$file_id = $_POST['file'];

$table = "users";
$attr = "id";
$condition = 'email = "' . $shared_email . '"';

$shared_id = "";

$result = $sqlHelper->select($table, $attr, $condition);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $shared_id .= $row['id'];
    }
}

$fileInfo = new DataFileInfo();
$fileInfo->setUserId(new Cassandra\Uuid($shared_id));
$fileInfo->setFileId(new Cassandra\Uuid($file_id));
$fileInfo->setName("");
$fileInfo->setSize(0);
$fileInfo->setDateModify(time());
$fileInfo->setDescription("Shared from " . $current_account);
$fileInfo->setStatus(12);

$connect->insert('file_info', $fileInfo);

?>
