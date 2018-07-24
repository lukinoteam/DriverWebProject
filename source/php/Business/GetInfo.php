<?php
require_once 'Connectdb.php';
//MARK:- Start session which stored user's info
session_start();

//MARK:- Variables
$email = $_SESSION['account'];
$oldName = "";
$oldGender = "";
$oldDate = "";

//TO-DO: connect sql
$db=MySQLConnectivity:: getInstance();
$conn=$db->getConnection();
//@var: SQL query
$sqlTakeName = "SELECT fullname,gender,dob from users WHERE email = '".$email."'";
$result = $conn->query($sqlTakeName);
//TO-DO: Get user's info from MySQL Database
if($result->num_rows>0){
	//TO-DO: display data reponsed from server
	while($row = $result->fetch_assoc()){
		$GLOBALS['oldName'] = $row['fullname'];
		$GLOBALS['oldGender'] = $row['gender'];
		$GLOBALS['oldDate'] = $row['dob'];
	}
}
$myArr = array($oldName,$oldGender,$oldDate);
$myJSON = json_encode($myArr);
echo $myJSON;
?>