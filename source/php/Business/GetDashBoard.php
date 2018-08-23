<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

function get_count_of_type($user_id, $type)
{
    $connect = new CassandraDA();

    $statement = new Cassandra\SimpleStatement(
        "select * from count_type where user_id = " . $user_id . " and type_id = '" . $type . "'"
    );

    $result = $connect->get_connection()->execute($statement);
    return ($result[0]['count'] == null) ? 0 : $result[0]['count'];
}

function get_size_of_type($user_id, $type)
{
    $connect = new CassandraDA();

    $statement = new Cassandra\SimpleStatement(
        "select * from count_size where user_id = " . $user_id . " and type_id = '" . $type . "'"
    );

    $result = $connect->get_connection()->execute($statement);
    return ($result[0]['size'] == null) ? 0 : $result[0]['size'];
}

session_start();
$user_id = $_SESSION['id'];

$dash_board_info = array();
$total_size = 0;
$total_size += get_size_of_type($user_id, "Word");
$total_size += get_size_of_type($user_id, "Excel");
$total_size += get_size_of_type($user_id, "Image");
$total_size += get_size_of_type($user_id, "Video");
$total_size += get_size_of_type($user_id, "Audio");
$total_size += get_size_of_type($user_id, "Others");

array_push($dash_board_info, get_count_of_type($user_id, "Word"), get_count_of_type($user_id, "Excel"), get_count_of_type($user_id, "Image"), get_count_of_type($user_id, "Video"),get_count_of_type($user_id, "Audio"),get_count_of_type($user_id, "Others"), $total_size);

echo json_encode ($dash_board_info);