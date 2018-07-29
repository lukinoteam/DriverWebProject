<?php
require_once __DIR__ ."/../DataAccess/ElasticDataAccess/ElasticDA.php";
$eDA = new ElasticDA;
$search_content = $_POST['key'];
$search_type = $_POST['type'];

$search_result;

if ($search_type === "Name") {
    $search_result = $eDA->search_with_name($search_content);
} else if ($search_type === "Size") {
    $search_result = $eDA->search_with_size($search_content);
} else if ($search_type === "Date") {
    $search_result = $eDA->search_with_date($search_content);
} else if ($search_type === "Extension") {
    $search_result = $eDA->search_with_type($search_content);
} else if ($search_type === "Content") {
    $search_result = $eDA->search_with_content($search_content);
}

var_dump($search_result);

?>