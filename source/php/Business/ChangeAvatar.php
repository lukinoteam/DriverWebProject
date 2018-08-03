<?php
    require_once __DIR__ ."/../DataAccess/Cassandra/CassandraDA.php";
   // require_once "DownFile.php";

	session_start();
	$avatar = $_FILES['avatar'];
	$id = $_SESSION['id'];
	$avatar = file_get_contents($avatar['tmp_name']);
    $avatar = bin2hex($avatar);
    
    $connect = new CassandraDA();
    // generate id for new file
    $data_avatar = new DataAvatar();
    $data_avatar->setImageId(new Cassandra\UUID());
    $data_avatar->setUserId(new Cassandra\UUID($id));
    $data_avatar->setStatus(1);
    $data_file_content = new DataFileContent();
    $data_file_content ->setFileId($data_avatar->getImageId());
    //update status = 0 to old avatars,then insert the new one which status = 1; 
    $query = "SELECT user_id , image_id FROM avatar";
    $update =$connect->get_connection()->execute($query);
	foreach ($update as $row) {
		$connect->get_connection() -> execute(new Cassandra\SimpleStatement("UPDATE avatar SET status = 0 WHERE user_id =".$id."and image_id =".$row['image_id']));
	}
    $connect->insert('avatar',$data_avatar);
    $data_file_content->setPart(1);
    $data_file_content->setContent($avatar);
    $connect->insert('file_content',$data_file_content);
	//$result = $session -> execute(new Cassandra\SimpleStatement("SELECT id_owner,is_available,blobAsascii(content) as content FROM data"));
    $image_id="";
    $select = "SELECT * from avatar";
    $result = $connect->get_connection()->execute($select);
    //get image id
    foreach($result as $row){
        if($row['user_id']==$id){
            if($row['status']==1){
                $image_id = $row['image_id'];
            }
        }
    }
    $pic = "";
    $select_avatar = "SELECT file_id,blobAsascii(content)as content from file_content";
    $query = $connect->get_connection()->execute($select_avatar);
    foreach($query as $row){
        if($row['file_id']==$GLOBALS['image_id']){
            $pic = pack("H*",$row['content']);
        }
    }
    echo "data:image/png;base64,".base64_encode($pic);
    //$pic = "data:image/png;base64,".down_file($image_id, $id);
?>