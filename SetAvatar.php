<?php
require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
session_start();
$connect = new CassandraDA();
$id = $_SESSION['id'];
$data_avatar = new DataAvatar();
$data_avatar->setUserId(new Cassandra\UUID($id));
$query = "SELECT * from avatar";
$result = $connect -> get_connection() -> execute($query);
if($result[0]['user_id']!=null){
    $image_id = "";
    foreach($result as $row){
        if($data_avatar->getUserId() == $row['user_id']){
            if($row['status']==1){
                $image_id = $row['image_id'];
            }
        }
    }
    if($image_id!=""){
        $pic = "";  
        $select_avatar = "SELECT file_id,blobAsascii(content)as content from file_content";
        $query = $connect->get_connection()->execute($select_avatar);
        foreach($query as $row){
            if($row['file_id']==$GLOBALS['image_id']){
                $pic .= pack("H*",$row['content']);
            }
        }
        echo "data:image/png;base64,".base64_encode($pic);
    }else{
        echo  "img/defaultAva.jpg";
    }
}
else{
    echo "img/defaultAva.jpg";
}
?>