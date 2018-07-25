<?php
	require_once __DIR__ ."/../DataAccess/MySQL/MySQLDA.php";
	session_start();
	$name = $_POST['fieldName'];
	$gender = $_POST['fieldGender'];
	$date = $_POST['fieldDate'];
	$email = $_SESSION['account'];
	$genderInt = 0;
	$oldName = "";
	$oldGender = "";
	$oldDate = "";
	if($GLOBALS['gender']=="MALE"){
	$GLOBALS['genderInt'] = 1;
	}
	else if($GLOBALS['gender']=="FEMALE"){
	$GLOBALS['genderInt']  = 2;
	}
	else {
	$GLOBALS['genderInt']  = 3;
	}
	$query = new MySQLDA();
	//$updateData = "UPDATE users SET fullname = \"".$name."\",gender = ".$genderInt.",dob = CAST("."\"".$date."\""." AS DATE) WHERE email = \"".$email."\"";
	$data = "fullname = \"".$name."\",gender = ".$genderInt.",dob = CAST("."\"".$date."\""."AS DATE) ";
	$condition = "email = \"".$email."\"";
	$table = "users";
	$updateData = $query->update($table,$data,$condition);
	if($updateData){
    	echo "Update account successfully";
    }

?>