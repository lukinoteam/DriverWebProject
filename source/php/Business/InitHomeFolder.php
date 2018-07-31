<?php
require_once __DIR__ . "/../DataAccess/Cassandra/CassandraDA.php";

function init_home_folder($id)
{
    $user_id = new Cassandra\UUID($id);
    $statement_array = array(
        "INSERT INTO folder_info (user_id, folder_id, name, size, date_modify, description, status)
            VALUES (" . $user_id . ",415fe87d-9b3b-4a4c-8f64-b380d2b39240,'HOME',0,dateof(now()),'DEFAULT', 0);",
        "INSERT INTO folder (folder_id, file_id)
            VALUES (415fe87d-9b3b-4a4c-8f64-b380d2b39240, 415fe87d-9b3b-4a4c-8f64-b380d2b39240)",
    );

    foreach ($statement_array as $str){
        $connect = new CassandraDA();

        $statement = new Cassandra\SimpleStatement($str);
        $connect->get_connection()->execute($statement);
    }

}
