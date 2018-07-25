<?php
require_once __DIR__ ."/../DataAccess/MySQL/MySQLDA.php";
//MARK:- Start session which stored user's info
	session_start();
	$email = $_SESSION['account'];
	$oldName = "";
	$oldGender = "";
	$oldDate = "";
    //select_userdata = "SELECT fullname,gender,dob from users WHERE email = '".$email."'";
	$query = new MySQLDA();
	$attr = "fullname,gender,dob";
	$table = "users";
	$condition = "email = '".$email."'";
	$select_userdata = $query->select($table,$attr,$condition);
    if($select_userdata->num_rows>0){
    	while($row = $select_userdata->fetch_assoc()){
    		$GLOBALS['oldName'] = $row['fullname'];
    		$GLOBALS['oldGender'] = $row['gender'];
    		$GLOBALS['oldDate'] = $row['dob'];
    	}
    }
    $myArr = array($oldName,$oldGender,$oldDate);
    $myJSON = json_encode($myArr);
    echo $myJSON;
?>