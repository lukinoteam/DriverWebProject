<?php
	require_once __DIR__ ."/../DataAccess/MySQL/MySQLDA.php";

	
	//MARK:- Start session to store Email and ID
	session_start();

	//MARK:- Variables
	$name = $_POST['fieldName'];
	$gender = $_POST['fieldGender'];
	$date = $_POST['fieldDate'];
	$email = $_SESSION['account'];
	$genderInt = 0;
	$oldName = "";
	$oldGender = "";
	$oldDate = "";

	//MARK:- Functions

	//TO-DO: check Is unique email ??
	//$isUnique = 1;
	if($GLOBALS['gender']=="MALE") {
		$GLOBALS['genderInt'] = 1;
	}
	else if($GLOBALS['gender']=="FEMALE") {
		$GLOBALS['genderInt']  = 2;
	}
	else {
		$GLOBALS['genderInt']  = 3;
	}
	
	//@var: Connectivity's instances
	$db = MySQLConnectivity:: getInstance();
	$conn = $db->getConnection();
	//@var: SQL query
	$sql = "UPDATE users SET fullname = \"".$name."\", gender = ".$genderInt.", dob = CAST("."\"".$date."\""." AS DATE) WHERE email = \"".$email."\"";
	//TO-DO: connect sql
	if($conn->query($sql)==TRUE) {
    	echo "Update account successfully";
    }
?>