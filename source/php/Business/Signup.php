<?php
require_once 'Connectdb.php';
//MARK:- Variables
$name = $_POST['fieldName'];
$email = $_POST['fieldEmail'];
$pass = $_POST['fieldPass'];
$gender = $_POST['fieldGender'];
$genderInt = 0;

//MARK:- Operation

//TO-DO: check Is unique email ??
$isUnique = 1;
if($GLOBALS['gender'] == "MALE"){
	$GLOBALS['genderInt'] = 1;
}
else if($GLOBALS['gender'] == "FEMALE"){
	$GLOBALS['genderInt']  = 2;
}
else {
	$GLOBALS['genderInt']  = 3;
}
//@var: User's properties
$dob = $_POST['fieldDate'];
$id = time();
$md5Pass = md5($pass);
$db = MySQLConnectivity::get_instance();
$conn = $db->get_connection();

//MARK: Check connection

//@var: SQL 
$selectSQL = "SELECT email FROM users";
$insertSQL = "INSERT INTO users (id, email, fullname, password, dob, gender) VALUES ('$id','$email', '$name', '$md5Pass', '$dob', '$genderInt')";   
//TO-DO: take a list of email from DB
$result = $conn->query($selectSQL);
if ($result->num_rows > 0) {
    //TO-DO: output data of each row
    while($row = $result->fetch_assoc()) {
        if($row["email"]==$GLOBALS['email']){
            $GLOBALS['isUnique'] = 0;
        }
    }
}
//TO-DO: if the email is unique
if($GLOBALS['isUnique'] == 1) {   
    if($conn->query($insertSQL) == TRUE) {
        echo "true";
    }
    else {
        echo "false";
    }            
}
else {
        echo "Account has already existed";
}
?>