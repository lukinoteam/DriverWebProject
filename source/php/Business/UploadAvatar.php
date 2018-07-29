<?php 
session_start();
require_once __DIR__ ."/../DataAccess/Connectdb.php";
$db=CassandraConnectivity:: get_instance();
$session=$db->get_connection();
$id = $_SESSION['id'];
Class UploadAvatar{
	function insert(){
		//$GLOBALS['session']->execute(new Cassandra\SimpleStatement("Truncate file_content"));

		//$avaName=$_POST['avaName'];
		$image_id=new Cassandra\UUID();
		$user_id=$GLOBALS['id'];

		$GLOBALS['session']->execute(new Cassandra\SimpleStatement("UPDATE avatar set status=0 where image_id<>".$image_id.""));
		$GLOBALS['session']->execute(new Cassandra\SimpleStatement("INSERT INTO avatar(user_id, image_id, status) VALUES (".$user_id.",".$image_id.",1)"));
	
		$part = 1;

		$filepath = $_FILES['file']['tmp_name'];
		$file = fopen($filepath, "r");

		while (!feof($file)) {
    /*a    *  I'm going to read in 1K chunks. You could make this 
    *  larger, but as a rule of thumb I'd keep it to 1/4 of 
    *  your php memory_limit.
    */

   			 $chunk = fread($file, 500000);
   			 $chunk = bin2hex($chunk);

   			 $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
    		"INSERT INTO file_content (file_id, part, content) values (".new Cassandra\UUID().",".$part." , asciiAsBlob('".$chunk."'))"
 			  );
   			 $GLOBALS['session']->execute($statement);  // fully asynchronous and easy parallel execution
   			 $part++;

		}
		$GLOBALS['session']->execute(new Cassandra\SimpleStatement("INSERT INTO count_content(file_id, count) VALUES (".$image_id.", ".($part - 1).")"));
	}
} 
$x= new UploadAvatar();
$x->insert();
?>