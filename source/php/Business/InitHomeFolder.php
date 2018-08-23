<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

function init_home_folder($id)
{
    $user_id = new Cassandra\UUID($id);
    $statement_array = array(
        "INSERT INTO folder_info (user_id, folder_id, name, date_modify, description, status)
            VALUES (" . $user_id . "," . $user_id . ",'HOME',dateof(now()),'DEFAULT', 0);",
        "INSERT INTO folder (folder_id, file_id,status)
            VALUES (" . $user_id . ", " . $user_id . ", 1)",
    );

    foreach ($statement_array as $str) {
        $connect = new CassandraDA();

        $statement = new Cassandra\SimpleStatement($str);
        $connect->get_connection()->execute($statement);
    }
}
