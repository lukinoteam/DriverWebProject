<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";
require_once __DIR__ . "/../DataAccess/MySQL/MySQLDA.php";
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";

// init connect to cassandra
$connect = new CassandraDA();

//init connect to mysql
$sqlHelper = new MySQLDA();

//init connect to elastic
$eDA = new ElasticDA();

//start session to get user-id
session_start();
$owner_id = $_SESSION['id'];
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
$response = $eDA->indexing_shared($owner_id, $shared_id, $file_id);
?>
