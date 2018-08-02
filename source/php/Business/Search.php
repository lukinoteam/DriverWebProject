<?php
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";


//start session to get user-id
session_start();
$user_id = $_SESSION['id'];

//init connection to elasticsearch
$eDA = new ElasticDA;
$search_content = $_POST['key'];
$search_type = $_POST['type'];

$search_result;

if ($search_type === "Name") {
    $search_result = $eDA->search_with_name($search_content, $user_id);
} else if ($search_type === "Size") {
    $search_result = $eDA->search_with_size($search_content, $user_id);
} else if ($search_type === "Date") {
    $search_result = $eDA->search_with_date($search_content, $user_id);
} else if ($search_type === "Extension") {
    $search_result = $eDA->search_with_type($search_content, $user_id);
} else if ($search_type === "Content") {
    $search_result = $eDA->search_with_content($search_content, $user_id);
}

echo json_encode($search_result);

?>